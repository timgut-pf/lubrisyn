<?php
/**
 * One-off importer for WP Store Locator (wpsl_stores) using a CSV.
 *
 * This script is designed for CLI usage only, e.g.:
 *   php wpsl-import-from-csv.php --csv="assets/csv/lubrisyn-store-locator.csv" --country="United States" --country-iso="US" --geocode=1
 *
 * It maps YOUR current CSV headers:
 * - Company name        -> post_title + wpsl_* meta
 * - Website URL         -> wpsl_url
 * - Street Address      -> wpsl_address
 * - City                -> wpsl_city
 * - State/Region        -> wpsl_state
 * - Postal Code         -> wpsl_zip
 * - Industry            -> wpsl_store_category term(s)
 *
 * Your CSV does NOT include:
 * - country (so we default)
 * - lat/lng (so optional geocoding is needed to populate map coordinates)
 */

if ( php_sapi_name() !== 'cli' ) {
	exit( "Run this script from the command line (CLI).\n" );
}

$opts = getopt( '', [
	'csv::',
	'country::',
	'country-iso::',
	'geocode::',
	'max::',
	'dry-run::',
] );

$theme_dir = __DIR__;
$csv_rel = isset( $opts['csv'] ) ? $opts['csv'] : 'assets/csv/lubrisyn-store-locator.csv';
$csv_path = $theme_dir . '/' . ltrim( $csv_rel, '/' );

$country = isset( $opts['country'] ) ? (string) $opts['country'] : 'United States';
$country_iso = isset( $opts['country-iso'] ) ? (string) $opts['country-iso'] : 'US';

$geocode = isset( $opts['geocode'] ) ? (int) $opts['geocode'] : 1;
$max = isset( $opts['max'] ) ? max( 1, (int) $opts['max'] ) : 0; // 0 = no limit
$dry_run = array_key_exists( 'dry-run', $opts ) ? ( (string) $opts['dry-run'] === '0' ? false : true ) : false;

if ( ! file_exists( $csv_path ) ) {
	exit( "CSV not found: {$csv_path}\n" );
}

// Bootstrap WordPress.
$wp_load = dirname( __DIR__, 3 ) . '/wp-load.php';
if ( ! file_exists( $wp_load ) ) {
	exit( "wp-load.php not found: {$wp_load}\n" );
}
require_once $wp_load;

global $wpdb;

if ( ! function_exists( 'get_page_by_title' ) ) {
	exit( "WordPress bootstrap failed.\n" );
}

// Load WPSL geocoder class if we need it.
$geocode_obj = null;
if ( $geocode ) {
	$wpsl_geocode_file = WP_CONTENT_DIR . '/plugins/wp-store-locator/admin/class-geocode.php';
	if ( ! file_exists( $wpsl_geocode_file ) ) {
		exit( "WPSL geocode class not found at {$wpsl_geocode_file}\n" );
	}
	require_once $wpsl_geocode_file;

	$geocode_obj = class_exists( 'WPSL_Geocode' ) ? new WPSL_Geocode() : null;
	if ( ! $geocode_obj ) {
		exit( "Could not instantiate WPSL_Geocode.\n" );
	}

	// Prevent fatal errors if geocoding fails and the plugin tries to use $wpsl_admin globals.
	// We only need these methods to exist; they can be no-ops for a one-off import.
	if ( ! isset( $GLOBALS['wpsl_admin'] ) ) {
		$GLOBALS['wpsl_admin'] = new stdClass();
		$GLOBALS['wpsl_admin']->notices = new class() {
			public function save( $type, $msg ) {
				// no-op for CLI import
			}
		};
		$GLOBALS['wpsl_admin']->metaboxes = new class() {
			public function set_post_pending( $post_id ) {
				wp_update_post([
					'ID' => (int) $post_id,
					'post_status' => 'pending',
				]);
			}
		};
	}
}

$fp = fopen( $csv_path, 'r' );
if ( ! $fp ) {
	exit( "Failed to open CSV: {$csv_path}\n" );
}

$header = fgetcsv( $fp );
if ( ! is_array( $header ) ) {
	exit( "Failed to read CSV header.\n" );
}

$header = array_map(
	function ( $h ) {
		$h = (string) $h;
		$h = trim( $h );
		// Strip UTF-8 BOM if present (common in CSV exports).
		$h = preg_replace( '/^\xEF\xBB\xBF/', '', $h );
		return $h;
	},
	$header
);

// Map CSV columns -> WPSL / WP fields.
$col = function ( $name ) use ( $header ) {
	foreach ( $header as $i => $h ) {
		if ( strcasecmp( $h, $name ) === 0 ) {
			return $i;
		}
	}
	return null;
};

$idx_name = $col( 'Company name' );
$idx_url = $col( 'Website URL' );
$idx_address = $col( 'Street Address' );
$idx_city = $col( 'City' );
$idx_state = $col( 'State/Region' );
$idx_zip = $col( 'Postal Code' );
$idx_category = $col( 'Industry' );

$missing = [];
foreach ( [
	'Company name' => $idx_name,
	'Street Address' => $idx_address,
	'City' => $idx_city,
] as $k => $v ) {
	if ( $v === null ) {
		$missing[] = $k;
	}
}

if ( $missing ) {
	exit( "Missing required CSV columns: " . implode( ', ', $missing ) . "\n" );
}

$row_num = 0;
$created = 0;
$updated = 0;
$failed = 0;

while ( ( $row = fgetcsv( $fp ) ) !== false ) {
	$row_num++;
	if ( empty( $row ) ) {
		continue;
	}

	$company = isset( $row[ $idx_name ] ) ? trim( (string) $row[ $idx_name ] ) : '';
	if ( $company === '' ) {
		continue;
	}

	$website_url = $idx_url !== null && isset( $row[ $idx_url ] ) ? trim( (string) $row[ $idx_url ] ) : '';
	$street = $idx_address !== null && isset( $row[ $idx_address ] ) ? trim( (string) $row[ $idx_address ] ) : '';
	$city = $idx_city !== null && isset( $row[ $idx_city ] ) ? trim( (string) $row[ $idx_city ] ) : '';
	$state = $idx_state !== null && isset( $row[ $idx_state ] ) ? trim( (string) $row[ $idx_state ] ) : '';
	$zip = $idx_zip !== null && isset( $row[ $idx_zip ] ) ? trim( (string) $row[ $idx_zip ] ) : '';
	$industry = $idx_category !== null && isset( $row[ $idx_category ] ) ? trim( (string) $row[ $idx_category ] ) : '';

	// Keep a safety fallback for required meta fields.
	if ( $street === '' || $city === '' ) {
		$failed++;
		echo "[{$row_num}] Skip: missing address/city for {$company}\n";
		continue;
	}

	$existing = get_page_by_title( $company, OBJECT, 'wpsl_stores' );
	$post_id = $existing ? (int) $existing->ID : 0;

	$meta = [
		'wpsl_address' => $street,
		'wpsl_address2' => '',
		'wpsl_city' => $city,
		'wpsl_state' => $state,
		'wpsl_zip' => $zip,
		'wpsl_country' => $country,
		'wpsl_country_iso' => $country_iso,
		'wpsl_url' => $website_url,
	];

	if ( $dry_run ) {
		// Skip persistence, but still count what we would do.
		$post_id = $post_id ?: -1;
	} else {
		if ( $post_id ) {
			wp_update_post([
				'ID' => $post_id,
				'post_title' => $company,
				'post_status' => 'publish',
			]);
			$updated++;
		} else {
			$post_id = wp_insert_post([
				'post_type' => 'wpsl_stores',
				'post_title' => $company,
				'post_status' => 'publish',
			], true );
			if ( is_wp_error( $post_id ) || ! $post_id ) {
				$failed++;
				echo "[{$row_num}] FAIL create {$company}: " . ( is_wp_error( $post_id ) ? $post_id->get_error_message() : 'unknown error' ) . "\n";
				continue;
			}
			$created++;
		}

		// Write meta.
		foreach ( $meta as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}

		// Assign taxonomy term based on "Industry".
		if ( $industry !== '' ) {
			$term_name = $industry;
			$term_slug = sanitize_title( $term_name );
			if ( $term_slug !== '' ) {
				if ( ! term_exists( $term_slug, 'wpsl_store_category' ) ) {
					wp_insert_term( $term_name, 'wpsl_store_category', [ 'slug' => $term_slug ] );
				}
				wp_set_object_terms( $post_id, [ $term_slug ], 'wpsl_store_category', false );
			}
		}
	}

	// Geocode if lat/lng are missing and requested.
	// Your CSV doesn't include lat/lng, so we geocode via the plugin's Google setup.
	if ( $geocode && ! $dry_run ) {
		$current_lat = get_post_meta( $post_id, 'wpsl_lat', true );
		$current_lng = get_post_meta( $post_id, 'wpsl_lng', true );
		if ( empty( $current_lat ) || empty( $current_lng ) ) {
			$store_data = [
				'address' => $street,
				'city' => $city,
				'state' => $state,
				'zip' => $zip,
				'country' => $country,
				'lat' => '',
				'lng' => '',
			];

			$geocode_obj->check_geocode_data( $post_id, $store_data );
		}
	}

	if ( $max > 0 && $created + $updated >= $max ) {
		break;
	}

	echo "[{$row_num}] " . ( $dry_run ? 'WOULD ' : '' ) . "OK: {$company}\n";
}

fclose( $fp );

echo "\nDone.\n";
echo "Created: {$created}\n";
echo "Updated: {$updated}\n";
echo "Failed:  {$failed}\n";

