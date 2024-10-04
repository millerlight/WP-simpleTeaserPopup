<?php
/*
Plugin Name: Simple Teaser Popup
Description: Displays a popup with customizable text, background image, and other settings.
Version: 1.0
Author: Ulle
*/

// Register admin menu
function simple_teaser_create_menu() {
    add_menu_page(
        'Simple Teaser Einstellungen',
        'Popup Einstellungen',
        'administrator',
        'simple-teaser-settings',
        'simple_teaser_settings_page_markup',
        'dashicons-format-image'
    );
    add_action('admin_init', 'simple_teaser_register_settings');
}
add_action('admin_menu', 'simple_teaser_create_menu');

// Register settings
function simple_teaser_register_settings() {
    register_setting('simple-teaser-settings-group', 'simple_teaser_text');
    register_setting('simple-teaser-settings-group', 'simple_teaser_image');
    register_setting('simple-teaser-settings-group', 'simple_teaser_heading_size');
    register_setting('simple-teaser-settings-group', 'simple_teaser_fade_color1');
    register_setting('simple-teaser-settings-group', 'simple_teaser_fade_color2');
    register_setting('simple-teaser-settings-group', 'simple_teaser_text_block');
    register_setting('simple-teaser-settings-group', 'simple_teaser_text_block_size');
    register_setting('simple-teaser-settings-group', 'simple_teaser_text_block_color');
    register_setting('simple-teaser-settings-group', 'simple_teaser_button_text');
    register_setting('simple-teaser-settings-group', 'simple_teaser_button_link');
    register_setting('simple-teaser-settings-group', 'simple_teaser_button_bg_color');
    register_setting('simple-teaser-settings-group', 'simple_teaser_button_text_color');
    register_setting('simple-teaser-settings-group', 'simple_teaser_pages');
}

// Admin settings page markup
function simple_teaser_settings_page_markup() {
    ?>
    <div class="wrap">
        <h1>Popup Einstellungen</h1>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
            <input type="hidden" name="action" value="simple_teaser_save">
            <?php wp_nonce_field('simple_teaser_save_nonce'); ?>

            <table class="form-table">
                <!-- Popup Text -->
                <tr valign="top">
                    <th scope="row">Popup Text</th>
                    <td><input type="text" name="simple_teaser_text" value="<?php echo esc_attr(get_option('simple_teaser_text')); ?>" style="width: 100%;" /></td>
                </tr>

                <!-- Text Size -->
                <tr valign="top">
                    <th scope="row">Text Größe (in Pixel)</th>
                    <td><input type="number" name="simple_teaser_heading_size" value="<?php echo esc_attr(get_option('simple_teaser_heading_size', '36')); ?>" /></td>
                </tr>

                <!-- Fading Colors -->
                <tr valign="top">
                    <th scope="row">Farbe 1 (Fading)</th>
                    <td><input type="color" name="simple_teaser_fade_color1" value="<?php echo esc_attr(get_option('simple_teaser_fade_color1', '#ff0000')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Farbe 2 (Fading)</th>
                    <td><input type="color" name="simple_teaser_fade_color2" value="<?php echo esc_attr(get_option('simple_teaser_fade_color2', '#ff6600')); ?>" /></td>
                </tr>

                <!-- Bild-Upload -->
                <tr valign="top">
                    <th scope="row">Hintergrundbild (vom lokalen Rechner hochladen)</th>
                    <td>
                        <input type="file" name="simple_teaser_image_upload" />
                        <p>Aktuelles Bild: 
                        <?php if (get_option('simple_teaser_image')) : ?>
                            <img src="<?php echo esc_url(get_option('simple_teaser_image')); ?>" style="max-width: 300px;" />
                        <?php endif; ?>
                        </p>
                    </td>
                </tr>

                <!-- Content-Textblock -->
                <tr valign="top">
                    <th scope="row">Content-Text</th>
                    <td><textarea name="simple_teaser_text_block" rows="4" cols="50"><?php echo esc_textarea(get_option('simple_teaser_text_block')); ?></textarea></td>
                </tr>

                <!-- Textblock Schriftgröße -->
                <tr valign="top">
                    <th scope="row">Textblock Schriftgröße (in Pixel)</th>
                    <td><input type="number" name="simple_teaser_text_block_size" value="<?php echo esc_attr(get_option('simple_teaser_text_block_size', '16')); ?>" /></td>
                </tr>

                <!-- Textblock Schriftfarbe -->
                <tr valign="top">
                    <th scope="row">Textblock Schriftfarbe</th>
                    <td><input type="color" name="simple_teaser_text_block_color" value="<?php echo esc_attr(get_option('simple_teaser_text_block_color', '#000000')); ?>" /></td>
                </tr>

                <!-- Button Text -->
                <tr valign="top">
                    <th scope="row">Button Text</th>
                    <td><input type="text" name="simple_teaser_button_text" value="<?php echo esc_attr(get_option('simple_teaser_button_text', 'Mehr Informationen')); ?>" style="width: 100%;" /></td>
                </tr>

                <!-- Button Link -->
                <tr valign="top">
                    <th scope="row">Button Link (URL)</th>
                    <td><input type="url" name="simple_teaser_button_link" value="<?php echo esc_attr(get_option('simple_teaser_button_link')); ?>" style="width: 100%;" /></td>
                </tr>

                <!-- Button Background Color -->
                <tr valign="top">
                    <th scope="row">Button Hintergrundfarbe</th>
                    <td><input type="color" name="simple_teaser_button_bg_color" value="<?php echo esc_attr(get_option('simple_teaser_button_bg_color', '#ff6600')); ?>" /></td>
                </tr>

                <!-- Button Text Color -->
                <tr valign="top">
                    <th scope="row">Button Textfarbe</th>
                    <td><input type="color" name="simple_teaser_button_text_color" value="<?php echo esc_attr(get_option('simple_teaser_button_text_color', '#ffffff')); ?>" /></td>
                </tr>

                <!-- Seiten auswählen -->
                <tr valign="top">
                    <th scope="row">Seiten auswählen, auf denen das Popup angezeigt werden soll</th>
                    <td>
                        <?php
                        $selected_pages = get_option('simple_teaser_pages', []);

                        if (!is_array($selected_pages)) {
                            $selected_pages = [];
                        }

                        $pages = get_pages();
                        foreach ($pages as $page) {
                            $checked = in_array($page->ID, $selected_pages) ? 'checked="checked"' : '';
                            echo '<input type="checkbox" name="simple_teaser_pages[]" value="' . esc_attr($page->ID) . '" ' . $checked . '> ' . esc_html($page->post_title) . '<br>';
                        }
                        ?>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Handle file upload and text save
function simple_teaser_handle_file_upload() {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'simple_teaser_save_nonce')) {
        wp_die('Sicherheitsüberprüfung fehlgeschlagen.');
    }

    // Process image upload
    if (!empty($_FILES['simple_teaser_image_upload']['name'])) {
        $uploadedfile = $_FILES['simple_teaser_image_upload'];
        $upload_overrides = array('test_form' => false);

        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

        if ($movefile && !isset($movefile['error'])) {
            update_option('simple_teaser_image', $movefile['url']);
        } else {
            echo "Fehler beim Hochladen: " . $movefile['error'];
        }
    }

    if (isset($_POST['simple_teaser_text'])) {
        update_option('simple_teaser_text', sanitize_text_field($_POST['simple_teaser_text']));
    }

    if (isset($_POST['simple_teaser_heading_size'])) {
        update_option('simple_teaser_heading_size', sanitize_text_field($_POST['simple_teaser_heading_size']));
    }
    if (isset($_POST['simple_teaser_fade_color1'])) {
        update_option('simple_teaser_fade_color1', sanitize_text_field($_POST['simple_teaser_fade_color1']));
    }
    if (isset($_POST['simple_teaser_fade_color2'])) {
        update_option('simple_teaser_fade_color2', sanitize_text_field($_POST['simple_teaser_fade_color2']));
    }
    if (isset($_POST['simple_teaser_text_block'])) {
        update_option('simple_teaser_text_block', sanitize_textarea_field($_POST['simple_teaser_text_block']));
    }
    if (isset($_POST['simple_teaser_text_block_size'])) {
        update_option('simple_teaser_text_block_size', sanitize_text_field($_POST['simple_teaser_text_block_size']));
    }
    if (isset($_POST['simple_teaser_text_block_color'])) {
        update_option('simple_teaser_text_block_color', sanitize_text_field($_POST['simple_teaser_text_block_color']));
    }
    if (isset($_POST['simple_teaser_button_text'])) {
        update_option('simple_teaser_button_text', sanitize_text_field($_POST['simple_teaser_button_text']));
    }
    if (isset($_POST['simple_teaser_button_link'])) {
        update_option('simple_teaser_button_link', esc_url_raw($_POST['simple_teaser_button_link']));
    }
    if (isset($_POST['simple_teaser_button_bg_color'])) {
        update_option('simple_teaser_button_bg_color', sanitize_text_field($_POST['simple_teaser_button_bg_color']));
    }
    if (isset($_POST['simple_teaser_button_text_color'])) {
        update_option('simple_teaser_button_text_color', sanitize_text_field($_POST['simple_teaser_button_text_color']));
    }
    if (isset($_POST['simple_teaser_pages'])) {
        update_option('simple_teaser_pages', array_map('intval', $_POST['simple_teaser_pages']));
    }

    wp_redirect(admin_url('admin.php?page=simple-teaser-settings&status=success'));
    exit;
}
add_action('admin_post_simple_teaser_save', 'simple_teaser_handle_file_upload');

// Enqueue CSS and JS
function simple_teaser_enqueue_scripts() {
    wp_enqueue_style('simple-teaser-style', plugins_url('style.css', __FILE__));
    wp_enqueue_script('simple_teaser-script', plugins_url('popup.js', __FILE__), array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'simple_teaser_enqueue_scripts');

// Display popup on selected pages
function simple_teaser_show_popup() {
    if (is_page()) {
        $selected_pages = get_option('simple_teaser_pages', []);

        if (!is_array($selected_pages)) {
            $selected_pages = [];
        }

        if (!empty($selected_pages) && in_array(get_the_ID(), $selected_pages)) {
            $popup_text = get_option('simple_teaser_text', 'Simple Teaser Popup');
            $popup_image = get_option('simple_teaser_image');
            $heading_size = get_option('simple_teaser_heading_size', '36');
            $fade_color1 = get_option('simple_teaser_fade_color1', '#ff0000');
            $fade_color2 = get_option('simple_teaser_fade_color2', '#ff6600');
            $text_block = get_option('simple_teaser_text_block', 'Hier kommt der Popup-Text rein.');
            $text_block_size = get_option('simple_teaser_text_block_size', '16');
            $text_block_color = get_option('simple_teaser_text_block_color', '#000000');
            $button_text = get_option('simple_teaser_button_text', 'Mehr Informationen');
            $button_link = get_option('simple_teaser_button_link', '#');
            $button_bg_color = get_option('simple_teaser_button_bg_color', '#ff6600');
            $button_text_color = get_option('simple_teaser_button_text_color', '#ffffff');

            ?>
            <!-- Popup HTML -->
            <div id="simple-teaser-popup" style="background-image: url('<?php echo esc_url($popup_image); ?>'); display: none; background-color: #04536c;">
                <style>
                    @keyframes colorChange {
                        from { color: <?php echo esc_attr($fade_color1); ?>; }
                        to { color: <?php echo esc_attr($fade_color2); ?>; }
                    }
                </style>
                <button class="close-popup" style="position: absolute; top: 4px; right: 4px;">&times;</button>
                <div class="popup-content">
                    <!-- Popup Title with Dynamic Animation -->
                    <h2 style="font-size: <?php echo esc_attr($heading_size); ?>px; animation: colorChange 2s infinite alternate;">
                        <?php echo esc_html($popup_text); ?>
                    </h2>

                    <!-- Content-Textblock -->
                    <div class="popup-text-block" style="color: <?php echo esc_attr($text_block_color); ?>; font-size: <?php echo esc_attr($text_block_size); ?>px; margin: 24px;">
                        <?php echo wp_kses_post(nl2br($text_block)); ?>
                    </div>
                </div>

                <!-- Weiterleitungs-Button -->
                <a href="<?php echo esc_url($button_link); ?>" class="more-info-button" style="background-color: <?php echo esc_attr($button_bg_color); ?>; color: <?php echo esc_attr($button_text_color); ?>;">
                    <?php echo esc_html($button_text); ?>
                </a>
            </div>
            <?php
        }
    }
}
add_action('wp_footer', 'simple_teaser_show_popup');
