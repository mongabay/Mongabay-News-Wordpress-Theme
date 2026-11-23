<?php

const MONGABAY_MEGA_MENU_OPTION = 'mongabay_mega_menu';

function mongabay_mega_menu_image_url($image_url)
{
  if (!is_string($image_url) || $image_url === '') {
    return '';
  }

  $attachment_id = attachment_url_to_postid($image_url);
  if ($attachment_id) {
    $medium_url = wp_get_attachment_image_url($attachment_id, 'medium');
    if ($medium_url) {
      return $medium_url;
    }
  }

  return $image_url;
}

function mongabay_mega_menu_sanitize_items($items, $depth = 0, &$item_count = 0)
{
  if (!is_array($items) || $depth > 8 || $item_count >= 150) {
    return array();
  }

  $sanitized_items = array();
  foreach ($items as $item) {
    if (!is_array($item) || $item_count >= 150) {
      continue;
    }

    $raw_label = $item['label'] ?? '';
    $label = sanitize_text_field(is_scalar($raw_label) ? (string) $raw_label : '');
    if ($label === '') {
      continue;
    }

    $item_count++;
    $raw_url = $item['url'] ?? '';
    $raw_description = $item['description'] ?? '';
    $raw_image = $item['image'] ?? '';
    $is_title = $depth > 0 && !empty($item['is_title']);
    $raw_image_span = $item['image_span'] ?? 6;
    $image_span = is_scalar($raw_image_span) && is_numeric($raw_image_span)
      ? min(12, max(1, (int) $raw_image_span))
      : 6;
    $raw_image_row = $item['image_row'] ?? 0;
    $image_row = is_scalar($raw_image_row) && is_numeric($raw_image_row)
      ? min(6, max(0, (int) $raw_image_row))
      : 0;
    $children = $depth < 8
      ? mongabay_mega_menu_sanitize_items($item['children'] ?? array(), $depth + 1, $item_count)
      : array();

    $sanitized_items[] = array(
      'label' => $label,
      'url' => $is_title ? '' : esc_url_raw(is_scalar($raw_url) ? (string) $raw_url : ''),
      'description' => $is_title ? '' : sanitize_textarea_field(is_scalar($raw_description) ? (string) $raw_description : ''),
      'image' => $is_title ? '' : esc_url_raw(is_scalar($raw_image) ? (string) $raw_image : ''),
      'is_title' => $is_title,
      'image_span' => $image_span,
      'image_row' => $image_row,
      'children' => $children,
    );
  }

  return $sanitized_items;
}

function mongabay_mega_menu_admin_page()
{
  if (!current_user_can('manage_options')) {
    wp_die(esc_html__('You are not allowed to manage the mega menu.', 'mongabay'));
  }

  $items = get_option(MONGABAY_MEGA_MENU_OPTION, array());
  if (!is_array($items)) {
    $items = array();
  }
  $item_count = 0;
  $items = mongabay_mega_menu_sanitize_items($items, 0, $item_count);
?>
  <div class="wrap mongabay-mega-menu-admin">
    <h1><?php esc_html_e('Mega Menu', 'mongabay'); ?></h1>
    <p><?php esc_html_e('Configure the menu structure, order, links, descriptions, and image cards. Nested items can be titles that group their sub-items into a column. All menu data is saved together in one WordPress setting.', 'mongabay'); ?></p>
    <p><?php esc_html_e('Items can be nested up to 8 levels. The menu supports up to 150 items.', 'mongabay'); ?></p>
    <?php if (isset($_GET['updated'])) : ?>
      <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Mega menu saved.', 'mongabay'); ?></p></div>
    <?php endif; ?>
    <?php if (isset($_GET['imported'])) : ?>
      <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Mega menu imported.', 'mongabay'); ?></p></div>
    <?php endif; ?>
    <?php if (isset($_GET['import_error'])) : ?>
      <div class="notice notice-error is-dismissible"><p>
        <?php
        $import_errors = array(
          'upload' => __('The uploaded file could not be read. Please choose a JSON file and try again.', 'mongabay'),
          'size' => __('The uploaded file is too large. Mega menu imports must not exceed 1 MB.', 'mongabay'),
          'json' => __('The uploaded file is not valid JSON.', 'mongabay'),
          'format' => __('The uploaded file is not a supported mega menu export.', 'mongabay'),
        );
        $raw_import_error = wp_unslash($_GET['import_error']);
        $import_error = sanitize_key(is_scalar($raw_import_error) ? (string) $raw_import_error : '');
        echo esc_html($import_errors[$import_error] ?? __('The mega menu could not be imported.', 'mongabay'));
        ?>
      </p></div>
    <?php endif; ?>
    <section class="mongabay-mega-menu-tools" aria-labelledby="mongabay-mega-menu-tools-heading">
      <h2 id="mongabay-mega-menu-tools-heading"><?php esc_html_e('Export or Import', 'mongabay'); ?></h2>
      <p><?php esc_html_e('Export the current menu as a JSON file or import a previously exported mega menu. Importing replaces the current menu.', 'mongabay'); ?></p>
      <div class="mongabay-mega-menu-tools-actions">
        <?php
        $export_url = wp_nonce_url(
          admin_url('admin-post.php?action=mongabay_export_mega_menu'),
          'mongabay_export_mega_menu'
        );
        ?>
        <a class="button" href="<?php echo esc_url($export_url); ?>"><?php esc_html_e('Export Mega Menu', 'mongabay'); ?></a>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
          <input type="hidden" name="action" value="mongabay_import_mega_menu">
          <?php wp_nonce_field('mongabay_import_mega_menu', 'mongabay_import_mega_menu_nonce'); ?>
          <label for="mongabay-mega-menu-import"><?php esc_html_e('Mega menu JSON file', 'mongabay'); ?>
            <input id="mongabay-mega-menu-import" type="file" name="mongabay_mega_menu_file" accept=".json,application/json" required>
          </label>
          <button class="button button-secondary" type="submit"><?php esc_html_e('Import Mega Menu', 'mongabay'); ?></button>
        </form>
      </div>
    </section>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="mongabay-mega-menu-editor">
      <input type="hidden" name="action" value="mongabay_save_mega_menu">
      <?php wp_nonce_field('mongabay_save_mega_menu', 'mongabay_mega_menu_nonce'); ?>
      <?php mongabay_mega_menu_render_editor_list($items, 'menu'); ?>
      <p><button type="button" class="button mongabay-mega-menu-editor-icon-button" data-mega-menu-action="add" aria-label="<?php esc_attr_e('Add top-level item', 'mongabay'); ?>" title="<?php esc_attr_e('Add top-level item', 'mongabay'); ?>"><span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span></button></p>
      <?php submit_button(__('Save Mega Menu', 'mongabay')); ?>
    </form>
    <p><?php esc_html_e('The component is displayed wherever the theme calls mongabay_mega_menu();.', 'mongabay'); ?></p>
    <template id="mongabay-mega-menu-item-template">
      <?php mongabay_mega_menu_render_editor_item(array(), '__PATH__'); ?>
    </template>
  </div>
<?php
}
add_action('admin_menu', function () {
  add_theme_page(
    __('Mega Menu', 'mongabay'),
    __('Mega Menu', 'mongabay'),
    'manage_options',
    'mongabay-mega-menu',
    'mongabay_mega_menu_admin_page'
  );
});

function mongabay_mega_menu_render_editor_list($items, $path)
{
  $is_panel_item = $path !== 'menu' && substr_count($path, '[children]') === 1;
?>
  <div class="mongabay-mega-menu-editor-list" data-path="<?php echo esc_attr($path); ?>">
    <?php foreach ($items as $index => $item) : ?>
      <?php mongabay_mega_menu_render_editor_item($item, $path . '[' . $index . ']', $path === 'menu', $is_panel_item); ?>
    <?php endforeach; ?>
  </div>
<?php
}

function mongabay_mega_menu_render_editor_item($item, $path, $is_top_level = false, $is_panel_item = false)
{
  $item = wp_parse_args($item, array(
    'label' => '',
    'url' => '',
    'description' => '',
    'image' => '',
    'is_title' => false,
    'image_span' => 6,
    'image_row' => 0,
    'children' => array(),
  ));
?>
  <div class="mongabay-mega-menu-editor-item<?php echo $is_top_level ? ' is-top-level' : ''; ?>">
    <div class="mongabay-mega-menu-editor-heading">
      <strong data-item-summary data-empty-label="<?php esc_attr_e('New menu item', 'mongabay'); ?>"><?php echo esc_html($item['label'] ?: __('New menu item', 'mongabay')); ?></strong>
      <button type="button" class="button mongabay-mega-menu-editor-icon-button" data-mega-menu-action="collapse" data-collapse-label="<?php esc_attr_e('Collapse', 'mongabay'); ?>" data-expand-label="<?php esc_attr_e('Expand', 'mongabay'); ?>" data-collapse-icon="dashicons-arrow-up-alt2" data-expand-icon="dashicons-arrow-down-alt2" aria-label="<?php esc_attr_e('Collapse', 'mongabay'); ?>" title="<?php esc_attr_e('Collapse', 'mongabay'); ?>" aria-expanded="true">
        <span class="dashicons dashicons-arrow-up-alt2" aria-hidden="true"></span>
      </button>
    </div>
    <div class="mongabay-mega-menu-editor-fields">
      <label><?php esc_html_e('Label', 'mongabay'); ?>
        <input type="text" data-field="label" name="<?php echo esc_attr($path); ?>[label]" value="<?php echo esc_attr($item['label']); ?>" required>
      </label>
      <?php if (!$is_top_level) : ?>
        <label class="mongabay-mega-menu-editor-title-toggle" data-title-toggle-control><?php esc_html_e('Title only (plain text; groups sub-items into a column)', 'mongabay'); ?>
          <input type="checkbox" data-field="is_title" name="<?php echo esc_attr($path); ?>[is_title]" value="1"<?php checked(!empty($item['is_title'])); ?>>
        </label>
      <?php endif; ?>
      <label data-title-incompatible><?php esc_html_e('URL', 'mongabay'); ?>
        <input type="text" data-field="url" name="<?php echo esc_attr($path); ?>[url]" value="<?php echo esc_attr($item['url']); ?>" placeholder="/path/ or https://">
      </label>
      <label data-title-incompatible><?php esc_html_e('Description', 'mongabay'); ?>
        <textarea data-field="description" name="<?php echo esc_attr($path); ?>[description]" rows="2"><?php echo esc_textarea($item['description']); ?></textarea>
      </label>
      <div class="mongabay-mega-menu-editor-image-field" data-title-incompatible>
        <span class="mongabay-mega-menu-editor-image-label"><?php esc_html_e('Cover image (optional; displays this item as a feature card)', 'mongabay'); ?></span>
        <input type="url" data-field="image" name="<?php echo esc_attr($path); ?>[image]" value="<?php echo esc_attr($item['image']); ?>" readonly>
        <div class="mongabay-mega-menu-editor-image-actions">
          <button type="button" class="button" data-mega-menu-action="select-image" data-media-title="<?php esc_attr_e('Select a cover image', 'mongabay'); ?>" data-media-button="<?php esc_attr_e('Use this image', 'mongabay'); ?>"><?php esc_html_e('Choose from Media Library', 'mongabay'); ?></button>
          <button type="button" class="button" data-mega-menu-action="clear-image"><?php esc_html_e('Remove image', 'mongabay'); ?></button>
        </div>
        <img class="mongabay-mega-menu-editor-image-preview"<?php if (!empty($item['image'])) : ?> src="<?php echo esc_url($item['image']); ?>"<?php endif; ?> alt=""<?php echo empty($item['image']) ? ' hidden' : ''; ?>>
      </div>
      <?php if ($is_panel_item || $path === '__PATH__') : ?>
        <label data-title-incompatible data-image-span-control><?php esc_html_e('Image grid span (columns, 1-12)', 'mongabay'); ?>
          <select data-field="image_span" name="<?php echo esc_attr($path); ?>[image_span]">
            <?php for ($span = 1; $span <= 12; $span++) : ?>
              <option value="<?php echo esc_attr($span); ?>"<?php selected((int) $item['image_span'], $span); ?>><?php echo esc_html($span); ?></option>
            <?php endfor; ?>
          </select>
        </label>
        <label data-title-incompatible data-image-row-control><?php esc_html_e('Image grid row', 'mongabay'); ?>
          <select data-field="image_row" name="<?php echo esc_attr($path); ?>[image_row]">
            <option value="0"<?php selected((int) $item['image_row'], 0); ?>><?php esc_html_e('Auto', 'mongabay'); ?></option>
            <?php for ($row = 1; $row <= 6; $row++) : ?>
              <option value="<?php echo esc_attr($row); ?>"<?php selected((int) $item['image_row'], $row); ?>><?php echo esc_html($row); ?></option>
            <?php endfor; ?>
          </select>
        </label>
      <?php endif; ?>
      <div class="mongabay-mega-menu-editor-actions">
        <button type="button" class="button mongabay-mega-menu-editor-icon-button" data-mega-menu-action="add-child" aria-label="<?php esc_attr_e('Add sub-item', 'mongabay'); ?>" title="<?php esc_attr_e('Add sub-item', 'mongabay'); ?>"><span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span></button>
        <button type="button" class="button mongabay-mega-menu-editor-icon-button" data-mega-menu-action="duplicate" aria-label="<?php esc_attr_e('Duplicate item', 'mongabay'); ?>" title="<?php esc_attr_e('Duplicate item', 'mongabay'); ?>"><span class="dashicons dashicons-admin-page" aria-hidden="true"></span></button>
        <button type="button" class="button mongabay-mega-menu-editor-icon-button" data-mega-menu-action="up" aria-label="<?php esc_attr_e('Move item up', 'mongabay'); ?>" title="<?php esc_attr_e('Move item up', 'mongabay'); ?>"><span class="dashicons dashicons-arrow-up-alt2" aria-hidden="true"></span></button>
        <button type="button" class="button mongabay-mega-menu-editor-icon-button" data-mega-menu-action="down" aria-label="<?php esc_attr_e('Move item down', 'mongabay'); ?>" title="<?php esc_attr_e('Move item down', 'mongabay'); ?>"><span class="dashicons dashicons-arrow-down-alt2" aria-hidden="true"></span></button>
        <button type="button" class="button mongabay-mega-menu-editor-icon-button is-destructive" data-mega-menu-action="remove" aria-label="<?php esc_attr_e('Remove item', 'mongabay'); ?>" title="<?php esc_attr_e('Remove item', 'mongabay'); ?>"><span class="dashicons dashicons-trash" aria-hidden="true"></span></button>
      </div>
    </div>
    <div class="mongabay-mega-menu-editor-children">
      <?php mongabay_mega_menu_render_editor_list($item['children'], $path . '[children]'); ?>
    </div>
  </div>
<?php
}

function mongabay_save_mega_menu()
{
  if (!current_user_can('manage_options')) {
    wp_die(esc_html__('You are not allowed to manage the mega menu.', 'mongabay'));
  }

  check_admin_referer('mongabay_save_mega_menu', 'mongabay_mega_menu_nonce');

  $submitted_items = isset($_POST['menu']) && is_array($_POST['menu'])
    ? wp_unslash($_POST['menu'])
    : array();
  $item_count = 0;
  $items = mongabay_mega_menu_sanitize_items($submitted_items, 0, $item_count);
  update_option(MONGABAY_MEGA_MENU_OPTION, $items, false);

  wp_safe_redirect(add_query_arg(
    array('page' => 'mongabay-mega-menu', 'updated' => '1'),
    admin_url('themes.php')
  ));
  exit;
}
add_action('admin_post_mongabay_save_mega_menu', 'mongabay_save_mega_menu');

function mongabay_export_mega_menu()
{
  if (!current_user_can('manage_options')) {
    wp_die(esc_html__('You are not allowed to manage the mega menu.', 'mongabay'));
  }

  check_admin_referer('mongabay_export_mega_menu');

  $items = get_option(MONGABAY_MEGA_MENU_OPTION, array());
  if (!is_array($items)) {
    $items = array();
  }
  $item_count = 0;
  $items = mongabay_mega_menu_sanitize_items($items, 0, $item_count);
  $export = array(
    'format' => 'mongabay-mega-menu',
    'version' => 1,
    'menu' => $items,
  );
  $json = wp_json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  if ($json === false) {
    wp_die(esc_html__('The mega menu could not be exported.', 'mongabay'));
  }

  nocache_headers();
  header('Content-Type: application/json; charset=utf-8');
  header('Content-Disposition: attachment; filename="mongabay-mega-menu.json"');
  header('X-Content-Type-Options: nosniff');
  echo $json;
  exit;
}
add_action('admin_post_mongabay_export_mega_menu', 'mongabay_export_mega_menu');

function mongabay_import_mega_menu()
{
  if (!current_user_can('manage_options')) {
    wp_die(esc_html__('You are not allowed to manage the mega menu.', 'mongabay'));
  }

  check_admin_referer('mongabay_import_mega_menu', 'mongabay_import_mega_menu_nonce');

  $file = $_FILES['mongabay_mega_menu_file'] ?? null;
  if (
    !is_array($file)
    || !isset($file['error'], $file['tmp_name'], $file['size'])
    || $file['error'] !== UPLOAD_ERR_OK
    || !is_string($file['tmp_name'])
    || !is_numeric($file['size'])
    || !is_uploaded_file($file['tmp_name'])
    || !is_readable($file['tmp_name'])
  ) {
    mongabay_mega_menu_import_redirect('upload');
  }
  if ($file['size'] > 1024 * 1024) {
    mongabay_mega_menu_import_redirect('size');
  }

  $json = file_get_contents($file['tmp_name']);
  if ($json === false) {
    mongabay_mega_menu_import_redirect('upload');
  }

  $import = json_decode($json, true, 32);
  if (json_last_error() !== JSON_ERROR_NONE) {
    mongabay_mega_menu_import_redirect('json');
  }
  if (
    !is_array($import)
    || ($import['format'] ?? '') !== 'mongabay-mega-menu'
    || ($import['version'] ?? null) !== 1
    || !isset($import['menu'])
    || !is_array($import['menu'])
  ) {
    mongabay_mega_menu_import_redirect('format');
  }

  $item_count = 0;
  $items = mongabay_mega_menu_sanitize_items($import['menu'], 0, $item_count);
  update_option(MONGABAY_MEGA_MENU_OPTION, $items, false);

  wp_safe_redirect(add_query_arg(
    array('page' => 'mongabay-mega-menu', 'imported' => '1'),
    admin_url('themes.php')
  ));
  exit;
}
add_action('admin_post_mongabay_import_mega_menu', 'mongabay_import_mega_menu');

function mongabay_mega_menu_import_redirect($error)
{
  wp_safe_redirect(add_query_arg(
    array('page' => 'mongabay-mega-menu', 'import_error' => sanitize_key($error)),
    admin_url('themes.php')
  ));
  exit;
}

function mongabay_mega_menu_admin_assets($hook_suffix)
{
  if ($hook_suffix !== 'appearance_page_mongabay-mega-menu') {
    return;
  }

  $style_path = get_template_directory() . '/css/mega-menu-admin.css';
  $script_path = get_template_directory() . '/js/lib/mega-menu-admin.js';
  wp_enqueue_media();
  wp_enqueue_style('mongabay-mega-menu-admin', get_template_directory_uri() . '/css/mega-menu-admin.css', array(), file_exists($style_path) ? filemtime($style_path) : '1.0.0');
  wp_enqueue_script('mongabay-mega-menu-admin', get_template_directory_uri() . '/js/lib/mega-menu-admin.js', array('media-editor', 'media-views'), file_exists($script_path) ? filemtime($script_path) : '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'mongabay_mega_menu_admin_assets');

function mongabay_mega_menu_render_nested_items($items, $text_only = false)
{
  if (empty($items)) {
    return;
  }
?>
  <ul class="mongabay-mega-menu__nested-list">
    <?php foreach ($items as $item) : ?>
      <li class="mongabay-mega-menu__nested-item">
        <?php if (!empty($item['is_title'])) : ?>
          <h3 class="mongabay-mega-menu__nested-title"><?php echo esc_html($item['label']); ?></h3>
          <?php mongabay_mega_menu_render_nested_items($item['children'] ?? array(), $text_only); ?>
        <?php elseif (!$text_only && !empty($item['image'])) : ?>
          <?php if (!empty($item['url'])) : ?>
            <a class="mongabay-mega-menu__nested-card" href="<?php echo esc_url($item['url']); ?>">
              <img src="<?php echo esc_url(mongabay_mega_menu_image_url($item['image'])); ?>" alt="" loading="lazy">
              <span><?php echo esc_html($item['label']); ?></span>
            </a>
          <?php else : ?>
            <div class="mongabay-mega-menu__nested-card">
              <img src="<?php echo esc_url(mongabay_mega_menu_image_url($item['image'])); ?>" alt="" loading="lazy">
              <span><?php echo esc_html($item['label']); ?></span>
            </div>
          <?php endif; ?>
        <?php elseif (!empty($item['url'])) : ?>
          <a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a>
        <?php else : ?>
          <span><?php echo esc_html($item['label']); ?></span>
        <?php endif; ?>
        <?php if (empty($item['is_title'])) : ?>
          <?php if (!$text_only && !empty($item['description'])) : ?>
            <p><?php echo esc_html($item['description']); ?></p>
          <?php endif; ?>
          <?php mongabay_mega_menu_render_nested_items($item['children'] ?? array(), $text_only); ?>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php
}

function mongabay_mega_menu_render_panel_items($items)
{
  $feature_items = array();
  $title_items = array();
  $link_items = array();
  foreach ($items as $item) {
    if (!empty($item['image'])) {
      $feature_items[] = $item;
    } elseif (!empty($item['is_title'])) {
      $title_items[] = $item;
    } else {
      $link_items[] = $item;
    }
  }
?>
  <div class="mongabay-mega-menu__panel-layout<?php echo !empty($feature_items) ? ' has-features' : ''; ?><?php echo !empty($feature_items) && (!empty($title_items) || !empty($link_items)) ? ' has-secondary' : ''; ?>">
    <?php if (!empty($feature_items)) : ?>
      <div class="mongabay-mega-menu__feature-grid">
        <?php foreach ($feature_items as $item) : ?>
          <div class="mongabay-mega-menu__card" style="--mega-menu-item-span: <?php echo (int) $item['image_span']; ?>; --mega-menu-item-row: <?php echo !empty($item['image_row']) ? (int) $item['image_row'] : 'auto'; ?>;">
            <img src="<?php echo esc_url(mongabay_mega_menu_image_url($item['image'])); ?>" alt="" loading="lazy">
            <?php if (!empty($item['url'])) : ?>
              <a class="mongabay-mega-menu__card-link" href="<?php echo esc_url($item['url']); ?>">
                <span><?php echo esc_html($item['label']); ?></span>
                <?php if (!empty($item['description'])) : ?>
                  <span class="mongabay-mega-menu__card-description"><?php echo esc_html($item['description']); ?></span>
                <?php endif; ?>
              </a>
            <?php else : ?>
              <div class="mongabay-mega-menu__card-content">
                <span class="mongabay-mega-menu__card-title"><?php echo esc_html($item['label']); ?></span>
                <?php if (!empty($item['description'])) : ?>
                  <p><?php echo esc_html($item['description']); ?></p>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <?php mongabay_mega_menu_render_nested_items($item['children'] ?? array()); ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($feature_items) && (!empty($title_items) || !empty($link_items))) : ?>
      <aside class="mongabay-mega-menu__panel-secondary">
        <?php foreach ($title_items as $item) : ?>
          <section class="mongabay-mega-menu__title-column">
            <h2><?php echo esc_html($item['label']); ?></h2>
            <?php mongabay_mega_menu_render_nested_items($item['children'] ?? array()); ?>
          </section>
        <?php endforeach; ?>
        <?php if (!empty($link_items)) : ?>
          <ul class="mongabay-mega-menu__link-list">
            <?php foreach ($link_items as $item) : ?>
              <li class="mongabay-mega-menu__link-item">
                <?php if (!empty($item['url'])) : ?>
                  <a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a>
                <?php else : ?>
                  <span><?php echo esc_html($item['label']); ?></span>
                <?php endif; ?>
                <?php if (!empty($item['description'])) : ?>
                  <p><?php echo esc_html($item['description']); ?></p>
                <?php endif; ?>
                <?php mongabay_mega_menu_render_nested_items($item['children'] ?? array()); ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </aside>
    <?php else : ?>
      <?php foreach ($title_items as $item) : ?>
        <section class="mongabay-mega-menu__title-column">
          <h2><?php echo esc_html($item['label']); ?></h2>
          <?php mongabay_mega_menu_render_nested_items($item['children'] ?? array()); ?>
        </section>
      <?php endforeach; ?>
      <?php if (!empty($link_items)) : ?>
        <ul class="mongabay-mega-menu__link-list">
          <?php foreach ($link_items as $item) : ?>
            <li class="mongabay-mega-menu__link-item">
              <?php if (!empty($item['url'])) : ?>
                <a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a>
              <?php else : ?>
                <span><?php echo esc_html($item['label']); ?></span>
              <?php endif; ?>
              <?php if (!empty($item['description'])) : ?>
                <p><?php echo esc_html($item['description']); ?></p>
              <?php endif; ?>
              <?php mongabay_mega_menu_render_nested_items($item['children'] ?? array()); ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    <?php endif; ?>
  </div>
<?php
}

function mongabay_mega_menu_render_navbar_panel($items, $panel_id)
{
  $donate_url = get_enabled_features('donate') ? get_donate_link() : get_subscribe_link_local(get_current_blog_id());
  $link_columns = array(
    array(
      array(get_menu_item('footerabout', 'url'), get_menu_item('footerabout', 'title')),
      array(get_subscribe_link_local(get_current_blog_id()), __('Subscribe page', 'mongabay')),
      array('https://www.mongabay.com/advertising', __('Advertising', 'mongabay')),
      array('https://reforestation.app/', __('Reforestation App', 'mongabay')),
    ),
    array(
      array(home_url('/' . ltrim((string) get_menu_item('footerteam', 'slug'), '/')), get_menu_item('footerteam', 'title')),
      array(home_url('/' . ltrim((string) get_menu_item('submissions', 'slug'), '/')), __('Submissions', 'mongabay')),
      array('https://www.wildmadagascar.org/', __('Wild Madagascar', 'mongabay')),
      array('https://www.planetaryhealthcheck.org/', __('Planetary Health Check', 'mongabay')),
    ),
    array(
      array(home_url('/' . ltrim((string) get_menu_item('footercontact', 'slug'), '/')), get_menu_item('footercontact', 'title')),
      array('https://www.mongabay.com/privacy', __('Privacy Policy', 'mongabay')),
      array('https://kids.mongabay.com/', __('For Kids', 'mongabay')),
      array('https://www.conservationeffectiveness.org/', __('Conservation Effectiveness', 'mongabay')),
    ),
    array(
      array($donate_url, get_enabled_features('donate') ? __('Donate', 'mongabay') : __('Subscribe', 'mongabay')),
      array('https://www.mongabay.com/terms', __('Terms of Use', 'mongabay')),
      array('https://mongabay.org/', __('Mongabay.org', 'mongabay')),
      array('https://studio.mongabay.com/', __('Mongabay Data Studio', 'mongabay')),
    ),
  );
  $current_host = wp_parse_url(home_url('/'), PHP_URL_HOST);
  $languages = array(
    array('https://news.mongabay.com', 'news.mongabay.com', 'English'),
    array('https://es.mongabay.com', 'es.mongabay.com', 'Español'),
    array('https://fr.mongabay.com', 'fr.mongabay.com', 'Français'),
    array('https://www.mongabay.co.id', 'www.mongabay.co.id', 'Bahasa Indonesia'),
    array('https://india.mongabay.com', 'india.mongabay.com', 'India (English)'),
    array('https://hindi.mongabay.com', 'hindi.mongabay.com', 'हिंदी'),
    array('https://bengali.mongabay.com', 'bengali.mongabay.com', 'বাংলা'),
    array('https://swahili.mongabay.com', 'swahili.mongabay.com', 'Swahili'),
  );
?>
  <section class="mongabay-mega-menu__navbar-panel" id="<?php echo esc_attr($panel_id); ?>" aria-label="<?php esc_attr_e('Explore menu', 'mongabay'); ?>" hidden>
    <div class="mongabay-mega-menu__navbar-cards">
      <?php foreach ($items as $item) :
        $card_url = $item['url'];
        if ($card_url === '' && !empty($item['children'])) {
          foreach ($item['children'] as $child) {
            if (!empty($child['url'])) {
              $card_url = $child['url'];
              break;
            }
          }
        }
      ?>
        <?php if ($card_url !== '') : ?>
          <a class="mongabay-mega-menu__navbar-card" href="<?php echo esc_url($card_url); ?>">
        <?php else : ?>
          <div class="mongabay-mega-menu__navbar-card">
        <?php endif; ?>
          <h2><?php echo esc_html($item['label']); ?></h2>
          <?php if (!empty($item['description'])) : ?>
            <p><?php echo esc_html($item['description']); ?></p>
          <?php endif; ?>
          <span class="icon icon-arrow-right" aria-hidden="true"></span>
        <?php if ($card_url !== '') : ?>
          </a>
        <?php else : ?>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <ul class="mongabay-mega-menu__mobile-items">
      <?php foreach ($items as $item) : ?>
        <li class="mongabay-mega-menu__mobile-item">
          <?php if (!empty($item['children'])) : ?>
            <details>
              <summary><?php echo esc_html($item['label']); ?><span class="mongabay-mega-menu__chevron" aria-hidden="true"></span></summary>
              <?php if (!empty($item['url'])) : ?>
                <a class="mongabay-mega-menu__mobile-parent-link" href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a>
              <?php endif; ?>
              <?php mongabay_mega_menu_render_nested_items($item['children'], true); ?>
            </details>
          <?php elseif (!empty($item['url'])) : ?>
            <a class="mongabay-mega-menu__mobile-heading" href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a>
          <?php else : ?>
            <span class="mongabay-mega-menu__mobile-heading"><?php echo esc_html($item['label']); ?></span>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <div class="mongabay-mega-menu__navbar-links">
      <?php foreach ($link_columns as $column) : ?>
        <ul>
          <?php foreach ($column as $link) : ?>
            <li><a href="<?php echo esc_url($link[0]); ?>"><?php echo esc_html($link[1]); ?></a></li>
          <?php endforeach; ?>
        </ul>
      <?php endforeach; ?>
    </div>
    <div class="mongabay-mega-menu__navbar-languages">
      <span class="icon icon-globe" aria-hidden="true"></span>
      <ul>
        <?php foreach ($languages as $language) :
          $is_current_language = $current_host === $language[1];
        ?>
          <li><a href="<?php echo esc_url($language[0]); ?>"<?php echo $is_current_language ? ' class="is-current" aria-current="page"' : ''; ?>><?php echo esc_html($language[2]); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>
<?php
}

function mongabay_mega_menu()
{
  $items = get_option(MONGABAY_MEGA_MENU_OPTION, array());
  if (!is_array($items)) {
    $items = array();
  }

  $item_count = 0;
  $items = mongabay_mega_menu_sanitize_items($items, 0, $item_count);
  $menu_id = wp_unique_id('mongabay-mega-menu-');
  $style_path = get_template_directory() . '/css/mega-menu.css';
  $script_path = get_template_directory() . '/js/lib/mega-menu.js';
  wp_enqueue_style('mongabay-mega-menu', get_template_directory_uri() . '/css/mega-menu.css', array(), file_exists($style_path) ? filemtime($style_path) : '1.0.0');
  wp_enqueue_script('mongabay-mega-menu', get_template_directory_uri() . '/js/lib/mega-menu.js', array(), file_exists($script_path) ? filemtime($script_path) : '1.0.0', true);
  wp_print_styles(array('mongabay-mega-menu'));
?>
  <nav class="mongabay-mega-menu" aria-label="<?php esc_attr_e('Main menu', 'mongabay'); ?>">
    <div class="mongabay-mega-menu__header">
      <div class="mongabay-mega-menu__primary">
        <button type="button" id="secondary-menu" class="mongabay-mega-menu__icon-button mongabay-mega-menu__list-toggle" aria-label="<?php esc_attr_e('Open menu', 'mongabay'); ?>" data-open-label="<?php esc_attr_e('Open menu', 'mongabay'); ?>" data-close-label="<?php esc_attr_e('Close menu', 'mongabay'); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr($menu_id . '-navbar-panel'); ?>">
          <span class="icon icon-menu" aria-hidden="true"></span>
        </button>
        <ul class="mongabay-mega-menu__tabs">
          <?php foreach ($items as $index => $item) : ?>
            <?php $panel_id = $menu_id . '-panel-' . $index; ?>
            <li class="mongabay-mega-menu__tab">
              <?php if (!empty($item['children'])) : ?>
                <button type="button" class="mongabay-mega-menu__trigger" aria-expanded="false" aria-controls="<?php echo esc_attr($panel_id); ?>">
                  <?php echo esc_html($item['label']); ?>
                  <span class="mongabay-mega-menu__chevron" aria-hidden="true"></span>
                </button>
              <?php elseif (!empty($item['url'])) : ?>
                <a class="mongabay-mega-menu__trigger" href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a>
              <?php else : ?>
                <span class="mongabay-mega-menu__trigger"><?php echo esc_html($item['label']); ?></span>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="mongabay-mega-menu__branding">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="theme-light">
          <img src="<?php echo esc_url(get_template_directory_uri() . '/img/logo/mongabay_logo_news_black.svg'); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
        </a>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="theme-dark">
          <img src="<?php echo esc_url(get_template_directory_uri() . '/img/logo/mongabay_logo_news_white.svg'); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
        </a>
      </div>
      <div class="mongabay-mega-menu__actions">
        <button type="button" class="mongabay-mega-menu__icon-button mongabay-mega-menu__settings-toggle" data-settings-toggle aria-expanded="false" aria-controls="<?php echo esc_attr($menu_id . '-settings'); ?>" aria-label="<?php esc_attr_e('Settings', 'mongabay'); ?>">
          <span class="icon icon-cog" aria-hidden="true"></span>
        </button>
        <a class="mongabay-mega-menu__icon-button mongabay-mega-menu__search" href="<?php echo esc_url(home_url('/?s=')); ?>" aria-label="<?php esc_attr_e('Search', 'mongabay'); ?>">
          <span class="icon icon-search" aria-hidden="true"></span>
        </a>
        <a class="mongabay-mega-menu__donate" href="<?php echo esc_url(get_enabled_features('donate') ? get_donate_link() : get_subscribe_link_local(get_current_blog_id())); ?>">
          <?php echo esc_html(get_enabled_features('donate') ? __('Donate', 'mongabay') : __('Subscribe', 'mongabay')); ?>
        </a>
      </div>
    </div>

    <section class="mongabay-mega-menu__settings" id="<?php echo esc_attr($menu_id . '-settings'); ?>" aria-label="<?php esc_attr_e('Settings', 'mongabay'); ?>" hidden>
      <div class="mongabay-mega-menu__settings-grid">
        <section class="mongabay-mega-menu__settings-card">
          <h2><?php esc_html_e('Theme', 'mongabay'); ?></h2>
          <div class="mongabay-mega-menu__theme-options" role="group" aria-label="<?php esc_attr_e('Theme', 'mongabay'); ?>">
            <button type="button" data-theme-choice="light"><?php esc_html_e('Light', 'mongabay'); ?></button>
            <button type="button" data-theme-choice="dark"><?php esc_html_e('Dark', 'mongabay'); ?></button>
            <button type="button" data-theme-choice="system"><?php esc_html_e('System', 'mongabay'); ?></button>
          </div>
        </section>
        <section class="mongabay-mega-menu__settings-card">
          <h2><?php esc_html_e('Accessibility', 'mongabay'); ?></h2>
          <a href="<?php echo esc_url(home_url('/accessibility/')); ?>"><?php esc_html_e('Accessibility declaration', 'mongabay'); ?></a>
          <button type="button" class="mongabay-mega-menu__accessibility-toggle" data-accessibility-choice="monochrome" aria-pressed="false"><?php esc_html_e('Monochrome', 'mongabay'); ?></button>
          <button type="button" class="mongabay-mega-menu__accessibility-toggle" data-accessibility-choice="mute-media" aria-pressed="false"><?php esc_html_e('Mute media', 'mongabay'); ?></button>
          <button type="button" class="mongabay-mega-menu__accessibility-toggle" data-accessibility-choice="enlarge-buttons" aria-pressed="false"><?php esc_html_e('Enlarge buttons', 'mongabay'); ?></button>
        </section>
        <section class="mongabay-mega-menu__settings-card">
          <h2><span class="icon icon-globe" aria-hidden="true"></span><?php esc_html_e('Language', 'mongabay'); ?></h2>
          <div class="mongabay-mega-menu__languages">
            <?php
            $current_host = wp_parse_url(home_url('/'), PHP_URL_HOST);
            $languages = array(
              'news.mongabay.com' => array('https://news.mongabay.com', 'English'),
              'india.mongabay.com' => array('https://india.mongabay.com', 'India (English)'),
              'es.mongabay.com' => array('https://es.mongabay.com', 'Español'),
              'hindi.mongabay.com' => array('https://hindi.mongabay.com', 'हिंदी'),
              'fr.mongabay.com' => array('https://fr.mongabay.com', 'Français'),
              'bengali.mongabay.com' => array('https://bengali.mongabay.com', 'বাংলা'),
              'brasil.mongabay.com' => array('https://brasil.mongabay.com', 'Brasil (Portuguese)'),
              'swahili.mongabay.com' => array('https://swahili.mongabay.com', 'Swahili'),
              'www.mongabay.co.id' => array('https://www.mongabay.co.id', 'Bahasa Indonesia'),
            );
            foreach ($languages as $host => $language) :
              $is_current_language = $current_host === $host;
            ?>
              <a href="<?php echo esc_url($language[0]); ?>"<?php echo $is_current_language ? ' class="is-current" aria-current="page"' : ''; ?>><?php echo esc_html($language[1]); ?></a>
            <?php endforeach; ?>
          </div>
        </section>
      </div>
    </section>

    <button type="button" id="theme-switch" class="mongabay-mega-menu__theme-switch" tabindex="-1" aria-hidden="true"></button>

    <?php mongabay_mega_menu_render_navbar_panel($items, $menu_id . '-navbar-panel'); ?>

    <?php foreach ($items as $index => $item) : ?>
      <?php if (empty($item['children'])) continue; ?>
      <?php $panel_id = $menu_id . '-panel-' . $index; ?>
      <section class="mongabay-mega-menu__panel" id="<?php echo esc_attr($panel_id); ?>" aria-label="<?php echo esc_attr($item['label']); ?>" hidden>
        <?php mongabay_mega_menu_render_panel_items($item['children']); ?>
      </section>
    <?php endforeach; ?>
  </nav>
<?php
}
