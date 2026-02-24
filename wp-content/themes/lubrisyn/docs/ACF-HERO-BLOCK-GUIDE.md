# ACF Blocks: Full Workflow & Reference

This guide covers how the LubriSyn theme uses **Advanced Custom Fields (ACF)** for blocks: field groups in the ACF UI (saved as JSON), block registration via the **blocks folder** pattern, and how the two connect.

---

## Full workflow: Adding a new ACF block

Use this sequence every time you add a new block. Order matters: create the block files first so the block exists in the inserter, then create the field group and point it at that block.

### 1. Create the block folder and `block.json`

- In the theme, create a folder: **`blocks/{block-slug}/`**  
  Use a short slug (e.g. `benefits`, `reviews`, `shop-promo-banner`). This slug will be the block’s “name” and must match the field group location later.

- Inside that folder, create **`block.json`** with at least:

```json
{
  "name": "acf/YOUR-BLOCK-SLUG",
  "title": "Your Block Title (shown in inserter)",
  "description": "Short description for the block inserter.",
  "category": "layout",
  "icon": "dashicon-name",
  "keywords": ["keyword1", "keyword2"],
  "acf": {
    "mode": "preview",
    "renderTemplate": "block.php"
  },
  "supports": {
    "anchor": true
  }
}
```

- Replace **`YOUR-BLOCK-SLUG`** with the **same** slug as the folder (e.g. folder `blocks/benefits/` → `"name": "acf/benefits"`).
- **`acf.renderTemplate`** must point to the PHP file that will render the block (usually `block.php` in the same folder).
- **`block.json` does not create or link to any ACF field group** — that’s done in the ACF UI in step 4.

### 2. Create the render template (`block.php`)

- In the same folder, create **`block.php`** (or the file name you set in `acf.renderTemplate`).
- Output your block HTML and use **`get_field( 'field_name' )`** for each piece of content. Field names must match the ACF field names you will create in step 4.

Example:

```php
<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$headline = get_field( 'headline' );
$intro   = get_field( 'intro_text' );
$items   = get_field( 'items' ) ?: []; // repeater
?>
<section class="wp-block-acf-my-block">
  <?php if ( $headline ) : ?><h2><?php echo esc_html( $headline ); ?></h2><?php endif; ?>
  <?php if ( $intro ) : ?><p><?php echo esc_html( $intro ); ?></p><?php endif; ?>
  <?php foreach ( $items as $item ) : ?>
    <div><?php echo esc_html( $item['label'] ?? '' ); ?></div>
  <?php endforeach; ?>
</section>
```

- No need to register the block in `functions.php`. **`inc/blocks.php`** auto-discovers every `blocks/*/block.json` and registers it on `init`.

### 3. (Optional) Add block CSS or JS

- If the block needs styles or scripts, add **`block.css`** and/or **`block.js`** in the same folder.
- Reference them in **`block.json`** so WordPress enqueues them:

```json
"style": ["file:./block.css"],
"script": ["file:./block.js"]
```

- Paths are relative to the `block.json` file.

### 4. Create the field group in the ACF UI

- In WP admin go to **Custom Fields** → **Add New** (or edit an existing group).
- **Title:** e.g. “Benefits Block” or “Shop Promo Banner w/Icons”.
- **Location:**  
  - **Block** → **is equal to** → **[Your block title]**  
  The dropdown lists registered blocks; choose the one whose title matches the `title` in your `block.json`. This saves as `acf/your-block-slug` in JSON. The block must already exist (steps 1–2) for it to appear in the list.
- Add your fields (Text, Image, Repeater, Link, etc.). Use **Field Name** (lowercase, no spaces) exactly as you use it in `get_field( 'field_name' )` in `block.php`.
- Click **Update**. ACF will write a JSON file into **`acf-json/`** in the theme (e.g. `group_xxxxx.json`). Commit that file so other environments get the same field group.

### 5. Sync (if you see “Sync available”)

- If the field group was previously defined in code or you imported JSON, you may see **Sync available** on the field group row. Click **Sync** so the database matches the JSON.

### 6. Use the block on a page

- Edit any page (or template) that uses the block editor.
- In the block inserter, search for your block by title or keyword. Add it and fill the fields in the sidebar. The block will render using your `block.php` and the saved field values.

---

## How registration works (no edits to `functions.php`)

- **`functions.php`** loads **`inc/blocks.php`** once.
- **`inc/blocks.php`** runs on `init`, scans **`blocks/`** for subfolders, and for each folder that contains a **`block.json`** calls **`register_block_type( $path_to_block.json )`** (core WordPress).
- When **`block.json`** includes an **`acf`** key, **ACF 6+** treats that block as an ACF block: when the block is selected, ACF shows the field group whose **Location** is “Block = this block.”
- So: **block.json** = block exists in Gutenberg and is marked as an ACF block. **Field group in ACF UI** = which fields appear in the sidebar. The link is the field group’s **Location** rule, not anything in `block.json`.

---

## Summary checklist (new block)

| Step | What to do |
|------|------------|
| 1 | Create `blocks/{slug}/block.json` with `name`: `acf/{slug}` and `acf.renderTemplate`: `block.php`. |
| 2 | Create `blocks/{slug}/block.php`; use `get_field( 'field_name' )` with names you’ll use in ACF. |
| 3 | (Optional) Add `block.css` / `block.js` and reference in `block.json`. |
| 4 | In ACF → Custom Fields, create a field group; set Location to **Block** = your block; add fields; Update (JSON saved to `acf-json/`). |
| 5 | Sync if ACF shows “Sync available”. |
| 6 | Add the block on a page and fill fields. |

---

## Hero block (legacy pattern)

The **LubriSyn Hero** block is not in the `blocks/` folder. It is still registered in **`functions.php`** with **`acf_register_block_type()`** and lives under **`acf-blocks/lubrisyn-hero/`** (render template, CSS, JS there). Its field group is in the ACF UI and in **`acf-json/group_lubrisyn_hero.json`**. You can keep it as-is or later move it into **`blocks/lubrisyn-hero/`** with a `block.json` + `block.php` and remove the hero registration from `functions.php` for a single pattern.

---

## Why ACF UI + JSON (not only code)

| In code only | With ACF UI + Local JSON |
|--------------|---------------------------|
| Field group only in PHP; non-devs can’t change structure | Field group editable in **Custom Fields**; JSON in theme for version control |
| Changes require code edits and redeploy | Add/change fields in UI; Save updates JSON; deploy JSON with theme |
| No single exportable definition for staging/production | Same field groups everywhere via `acf-json/` |

**In code:** block registration (or block.json), render template, CSS/JS.  
**In ACF:** field group (which fields, types, labels, defaults). Stored in UI and in **`acf-json/`**.

---

## Local JSON (default)

ACF saves and loads field groups from the theme’s **`acf-json`** folder by default. No filters in `functions.php` are required. Ensure the folder exists and is writable; when you click **Update** on a field group, ACF writes or updates the corresponding `.json` file there.
