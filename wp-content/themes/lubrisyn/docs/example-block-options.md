# block.json options reference

Reference for the options used in **block.json** (WordPress block metadata + ACF). See **`example.blocks.json`** in this folder for a full example. Paths in `style` / `script` etc. are relative to the folder containing `block.json`.

---

## Required for ACF blocks

| Property | Type | Description |
|----------|------|-------------|
| **`name`** | string | Unique block id. Must be `acf/{slug}` and match the folder name (e.g. `acf/example-block`). |
| **`title`** | string | Label shown in the block inserter. |
| **`acf`** | object | If present, ACF treats this as an ACF block and shows the field group when the block is selected. |
| **`acf.renderTemplate`** | string | Path to the PHP file that renders the block (e.g. `block.php`). Relative to `block.json` or absolute. |

---

## Core metadata (WordPress)

| Property | Type | Description |
|----------|------|-------------|
| **`$schema`** | string | Optional. Schema URL for editor validation: `https://schemas.wp.org/trunk/block.json`. |
| **`apiVersion`** | number | Block API version (e.g. `2` or `3`). Optional; default is 1. |
| **`description`** | string | Short description shown in the block inserter. |
| **`category`** | string | Block category: `text`, `media`, `design`, `widgets`, `theme`, `embed`, or `layout`. |
| **`icon`** | string | Dashicon name (e.g. `megaphone`, `cover-image`, `admin-comments`) or SVG. |
| **`keywords`** | array | Keywords for search in the block inserter. |
| **`version`** | string | Block version (e.g. `1.0.0`). |
| **`textdomain`** | string | Text domain for translations. |

---

## ACF block options (`acf` object)

| Property | Type | Description |
|----------|------|-------------|
| **`mode`** | string | `"auto"` \| `"preview"` \| `"edit"`. Preview shows frontend preview; edit form can appear in sidebar. Default `preview`. |
| **`renderTemplate`** | string | Path to PHP render template (e.g. `block.php`). |
| **`renderCallback`** | string | Optional. PHP function name instead of a template file. |
| **`blockVersion`** | number | ACF block version (default `2`). Version 1 is deprecated. |
| **`postTypes`** | array | Limit block to post types, e.g. `["post", "page"]`. Omit for all. |
| **`validate`** | boolean | Whether to validate ACF fields (default `true`). |
| **`usePostMeta`** | boolean | Store in post meta instead of block attributes (default `false`). Limits to one per page, no widgets/site editor. |
| **`hideFieldsInSidebar`** | boolean | (ACF 6.6.2+) Hide fields in sidebar, show only in expanded panel (v3 blocks). |
| **`autoInlineEditing`** | boolean | (ACF 6.7+) Enable inline editing for fields (v3 blocks). |

---

## Styles (CSS)

| Property | Type | Description |
|----------|------|-------------|
| **`style`** | string \| array | Frontend and editor. Use `"file:./block.css"` (path relative to `block.json`). Array allows multiple files or handles. |
| **`editorStyle`** | string \| array | Editor only. Use `"file:./editor.css"` for editor-specific styles. |

Paths: `file:./block.css` loads `block.css` in the same folder as `block.json`.

---

## Scripts (JavaScript)

| Property | Type | Description |
|----------|------|-------------|
| **`script`** | string \| array | Loaded on frontend and in editor. Use `"file:./block.js"`. |
| **`viewScript`** | string \| array | Frontend only (when block is on the page). Use `"file:./view.js"`. |
| **`editorScript`** | string \| array | Editor only. Use `"file:./editor.js"` for block editor behavior. |

Use **`viewScript`** when the script is only needed on the front (e.g. carousel, dropdown). Use **`script`** when the same JS is needed in editor preview and frontend.

---

## Block behavior (`supports`)

| Property | Type | Description |
|----------|------|-------------|
| **`align`** | true \| array | Allow alignment. `true` = all; `["wide", "full"]` = only wide/full. |
| **`anchor`** | boolean | Allow custom anchor (id) for the block. |
| **`className`** | boolean | Allow custom CSS class in block settings. |
| **`customClassName`** | boolean | Same as className (preferred name). |
| **`html`** | boolean | Allow block to be saved as static HTML (usually `false` for ACF). |
| **`inserter`** | boolean | Show in block inserter (default `true`). |
| **`multiple`** | boolean | Allow multiple instances (default `true`). |
| **`reusable`** | boolean | Allow saving as reusable block. |
| **`spacing`** | object | Enable spacing controls: `blockGap`, `margin`, `padding`. |
| **`typography`** | object | Enable font size, line height, etc. |

---

## Other optional properties

| Property | Type | Description |
|----------|------|-------------|
| **`example`** | object | Optional preview attributes for the inserter. |
| **`parent`** | array | Block names that can contain this block (e.g. `["core/group"]`). |
| **`ancestor`** | array | Block names that must be an ancestor. |
| **`allowedBlocks`** | array | Block names allowed inside this block. |
| **`styles`** | array | Block style variations (name, label, isDefault). |
| **`variations`** | array | Block variations with preset attributes. |

---

## Minimal ACF block (blocks folder)

Smallest useful `block.json` for a block in `blocks/my-block/`:

```json
{
  "name": "acf/my-block",
  "title": "My Block",
  "description": "Short description.",
  "category": "layout",
  "icon": "star-filled",
  "keywords": ["my", "block"],
  "acf": {
    "mode": "preview",
    "renderTemplate": "block.php"
  },
  "supports": {
    "anchor": true
  }
}
```

With frontend CSS and JS:

```json
{
  "name": "acf/my-block",
  "title": "My Block",
  "description": "Short description.",
  "category": "layout",
  "icon": "star-filled",
  "keywords": ["my", "block"],
  "acf": {
    "mode": "preview",
    "renderTemplate": "block.php"
  },
  "style": ["file:./block.css"],
  "viewScript": ["file:./view.js"],
  "supports": {
    "anchor": true
  }
}
```
