<?php
/**
 * LubriSyn - WP Store Locator CSV Importer (admin screen)
 *
 * Purpose: Import a CSV of store locations into the WP Store Locator plugin
 * (post type `wpsl_stores`) without the paid "CSV Manager" add-on.
 *
 * Access: WordPress admin only.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// PhP 8.0 compatibility: define a quick helper for escaping.
if ( ! function_exists( 'lubrisyn_e' ) ) {
	function lubrisyn_e( $v ) {
		echo esc_html( $v );
	}
}

function lubrisyn_wpsl_importer_admin_menu() {
	add_management_page(
		'LubiSyn WPSL Import',
		'LubiSyn WPSL Import',
		'manage_options',
		'lubrisyn-wpsl-importer',
		'lubrisyn_wpsl_importer_admin_page'
	);
}
add_action( 'admin_menu', 'lubrisyn_wpsl_importer_admin_menu' );

function lubrisyn_wpsl_importer_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Insufficient permissions.', 'lubrisyn' ) );
	}

	$theme_dir  = get_stylesheet_directory(); // active theme
	$default_csv_rel = 'assets/csv/lubrisyn-store-locator.csv';
	$default_csv_abs = trailingslashit( $theme_dir ) . $default_csv_rel;

	$last_action = isset( $_GET['lubrisyn_wpsl_importer'] ) ? sanitize_text_field( wp_unslash( $_GET['lubrisyn_wpsl_importer'] ) ) : '';

	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'WP Store Locator CSV Import', 'lubrisyn' ); ?></h1>

		<?php if ( $last_action === 'started' ) : ?>
			<p><strong>Import started.</strong> Check results below.</p>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php
			wp_nonce_field( 'lubrisyn_wpsl_importer', 'lubrisyn_wpsl_importer_nonce' );
			?>
			<input type="hidden" name="action" value="lubrisyn_wpsl_importer_run" />

			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row">
							<label for="csv_path"><?php esc_html_e( 'CSV path (relative to theme)', 'lubrisyn' ); ?></label>
						</th>
						<td>
							<input
								type="text"
								name="csv_path"
								id="csv_path"
								style="min-width: 520px;"
								value="<?php echo esc_attr( $default_csv_rel ); ?>"
							/>
							<p class="description">
								Example: <code>assets/csv/lubrisyn-store-locator.csv</code>
							</p>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="country_iso"><?php esc_html_e( 'Country ISO', 'lubrisyn' ); ?></label>
						</th>
						<td>
							<input type="text" name="country_iso" id="country_iso" value="US" />
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="country"><?php esc_html_e( 'Country name', 'lubrisyn' ); ?></label>
						</th>
						<td>
							<input type="text" name="country" id="country" value="United States" />
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="geocode"><?php esc_html_e( 'Geocode missing lat/lng', 'lubrisyn' ); ?></label>
						</th>
						<td>
							<select name="geocode" id="geocode">
								<option value="0"><?php esc_html_e( 'No (fast, no map coords)', 'lubrisyn' ); ?></option>
								<option value="1" selected><?php esc_html_e( 'Yes (slower; uses Google Geocode via plugin)', 'lubrisyn' ); ?></option>
							</select>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="max_rows"><?php esc_html_e( 'Max rows (0 = all)', 'lubrisyn' ); ?></label>
						</th>
						<td>
							<input type="number" name="max_rows" id="max_rows" value="0" min="0" />
							<p class="description">
								Use this to test on a few rows first.
							</p>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="dry_run"><?php esc_html_e( 'Dry run (no changes)', 'lubrisyn' ); ?></label>
						</th>
						<td>
							<select name="dry_run" id="dry_run">
								<option value="1" selected><?php esc_html_e( 'Yes (preview only)', 'lubrisyn' ); ?></option>
								<option value="0"><?php esc_html_e( 'No (write posts/meta/terms)', 'lubrisyn' ); ?></option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>

			<?php submit_button( 'Run Import' ); ?>
		</form>

		<?php
		// Results area is printed by the handler (admin-post) to avoid mixing output.
		if ( isset( $_GET['lubrisyn_wpsl_importer_result'] ) ) {
			$payload = base64_decode( sanitize_text_field( wp_unslash( $_GET['lubrisyn_wpsl_importer_result'] ) ) );
			$data = json_decode( $payload, true );
			if ( is_array( $data ) ) {
				echo '<hr />';
				echo '<h2>Results</h2>';
				echo '<pre style="white-space: pre-wrap;">' . esc_html( print_r( $data, true ) ) . '</pre>';
			}
		}
		?>
	</div>
	<?php
}

add_action( 'admin_post_lubrisyn_wpsl_importer_run', 'lubrisyn_wpsl_importer_run' );
function lubrisyn_wpsl_importer_run() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Insufficient permissions.', 'lubrisyn' ) );
	}

	if ( ! isset( $_POST['lubrisyn_wpsl_importer_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['lubrisyn_wpsl_importer_nonce'] ) ), 'lubrisyn_wpsl_importer' ) ) {
		wp_die( esc_html__( 'Invalid nonce.', 'lubrisyn' ) );
	}

	@set_time_limit( 0 );

	$theme_dir = get_stylesheet_directory();
	$csv_path_rel = isset( $_POST['csv_path'] ) ? sanitize_text_field( wp_unslash( $_POST['csv_path'] ) ) : '';
	$country_iso = isset( $_POST['country_iso'] ) ? sanitize_text_field( wp_unslash( $_POST['country_iso'] ) ) : 'US';
	$country = isset( $_POST['country'] ) ? sanitize_text_field( wp_unslash( $_POST['country'] ) ) : 'United States';
	$geocode = isset( $_POST['geocode'] ) ? (int) $_POST['geocode'] : 1;
	$max_rows = isset( $_POST['max_rows'] ) ? max( 0, (int) $_POST['max_rows'] ) : 0;
	$dry_run = isset( $_POST['dry_run'] ) ? (int) $_POST['dry_run'] : 1;
	$dry_run = $dry_run === 1;

	$allowed_prefix = trailingslashit( $theme_dir ) . 'assets/csv/';
	$csv_abs = realpath( trailingslashit( $theme_dir ) . ltrim( $csv_path_rel, '/' ) );

	if ( ! $csv_abs || strpos( $csv_abs, realpath( trailingslashit( $theme_dir ) . 'assets/csv' ) ) !== 0 ) {
		wp_die( esc_html__( 'CSV path must be inside the theme assets/csv directory.', 'lubrisyn' ) );
	}

	if ( ! file_exists( $csv_abs ) ) {
		wp_die( esc_html__( 'CSV file not found: ' . $csv_abs, 'lubrisyn' ) );
	}

	$results = lubrisyn_wpsl_importer_do_import(
		$csv_abs,
		$country,
		$country_iso,
		$geocode,
		$max_rows,
		$dry_run
	);

	$payload = base64_encode( wp_json_encode( $results ) );
	$redirect = add_query_arg(
		[
			'page' => 'lubrisyn-wpsl-importer',
			'lubrisyn_wpsl_importer_result' => $payload,
			'lubrisyn_wpsl_importer' => 'done',
		],
		admin_url( 'admin.php' )
	);

	wp_safe_redirect( $redirect );
	exit;
}

function lubrisyn_wpsl_importer_do_import( $csv_abs, $country, $country_iso, $geocode, $max_rows, $dry_run ) {
	$fp = fopen( $csv_abs, 'r' );
	if ( ! $fp ) {
		return [ 'error' => 'Failed to open CSV.' ];
	}

	$header = fgetcsv( $fp );
	if ( ! is_array( $header ) ) {
		fclose( $fp );
		return [ 'error' => 'Failed to read CSV header.' ];
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

	$required = [
		'Company name' => $idx_name,
		'Street Address' => $idx_address,
		'City' => $idx_city,
	];
	foreach ( $required as $k => $v ) {
		if ( $v === null ) {
			fclose( $fp );
			return [ 'error' => 'Missing required CSV columns: ' . $k ];
		}
	}

	// Geocoder (optional).
	$geocode_obj = null;
	if ( $geocode ) {
		if ( class_exists( 'WPSL_Geocode' ) ) {
			$geocode_obj = new WPSL_Geocode();
		} else {
			$geocode_file = WP_CONTENT_DIR . '/plugins/wp-store-locator/admin/class-geocode.php';
			if ( file_exists( $geocode_file ) ) {
				require_once $geocode_file;
				if ( class_exists( 'WPSL_Geocode' ) ) {
					$geocode_obj = new WPSL_Geocode();
				}
			}
		}
	}

	if ( $geocode && ! $geocode_obj ) {
		return [ 'error' => 'Could not initialize WPSL_Geocode. Make sure WP Store Locator is installed and configured.' ];
	}

	$created = 0;
	$updated = 0;
	$failed = 0;
	$would = 0;
	$processed = 0;

	$row_num = 1; // header already consumed
	while ( ( $row = fgetcsv( $fp ) ) !== false ) {
		$row_num++;
		if ( empty( $row ) ) {
			continue;
		}

		$company = isset( $row[ $idx_name ] ) ? trim( (string) $row[ $idx_name ] ) : '';
		if ( $company === '' ) {
			continue;
		}

		$processed++;
		if ( $max_rows > 0 && ( $created + $updated ) >= $max_rows ) {
			break;
		}

		$website_url = $idx_url !== null && isset( $row[ $idx_url ] ) ? trim( (string) $row[ $idx_url ] ) : '';
		$street = isset( $row[ $idx_address ] ) ? trim( (string) $row[ $idx_address ] ) : '';
		$city = isset( $row[ $idx_city ] ) ? trim( (string) $row[ $idx_city ] ) : '';
		$state = $idx_state !== null && isset( $row[ $idx_state ] ) ? trim( (string) $row[ $idx_state ] ) : '';
		$zip = $idx_zip !== null && isset( $row[ $idx_zip ] ) ? trim( (string) $row[ $idx_zip ] ) : '';
		$industry = $idx_category !== null && isset( $row[ $idx_category ] ) ? trim( (string) $row[ $idx_category ] ) : '';

		if ( $street === '' || $city === '' ) {
			$failed++;
			continue;
		}

		$existing = get_page_by_title( $company, OBJECT, 'wpsl_stores' );
		$post_id = $existing ? (int) $existing->ID : 0;

		if ( $dry_run ) {
			$would++;
			continue;
		}

		// Create/update store.
		if ( $post_id ) {
			$updated++;
			wp_update_post([
				'ID' => $post_id,
				'post_title' => $company,
				'post_status' => 'publish',
			]);
		} else {
			$new_id = wp_insert_post([
				'post_type' => 'wpsl_stores',
				'post_title' => $company,
				'post_status' => 'publish',
			], true );

			if ( is_wp_error( $new_id ) || ! $new_id ) {
				$failed++;
				continue;
			}

			$post_id = (int) $new_id;
			$created++;
		}

		// Required meta (matches plugin metabox fields)
		update_post_meta( $post_id, 'wpsl_address', $street );
		update_post_meta( $post_id, 'wpsl_address2', '' );
		update_post_meta( $post_id, 'wpsl_city', $city );
		update_post_meta( $post_id, 'wpsl_state', $state );
		update_post_meta( $post_id, 'wpsl_zip', $zip );
		update_post_meta( $post_id, 'wpsl_country', $country );
		update_post_meta( $post_id, 'wpsl_country_iso', $country_iso );
		update_post_meta( $post_id, 'wpsl_url', $website_url );

		// Category taxonomy.
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

		// Geocode if needed.
		if ( $geocode ) {
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
				// Note: this does update wpsl_lat/lng via plugin method.
				$geocode_obj->check_geocode_data( $post_id, $store_data );
			}
		}
	}

	fclose( $fp );

	return [
		'csv' => basename( $csv_abs ),
		'processed' => $processed,
		'dry_run' => $dry_run,
		'created' => $created,
		'updated' => $updated,
		'failed' => $failed,
		'would_do' => $would,
	];
}

