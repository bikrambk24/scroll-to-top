<?php
/*
Plugin Name: Scroll to Top Plugin
Description: Adds a customizable "Scroll to Top" button to the WordPress website.
Version: 1.0
Author: Bikram Bk
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function stt_enqueue_scripts() {
    wp_enqueue_style( 'stt-style', plugin_dir_url( __FILE__ ) . 'css/scroll-to-top.css' );
    wp_enqueue_script( 'stt-script', plugin_dir_url( __FILE__ ) . 'js/scroll-to-top.js', array( 'jquery' ), '1.0', true );

    $options = get_option( 'stt_settings' );
    wp_localize_script( 'stt-script', 'sttOptions', array(
        'buttonImage' => $options['button_image'] ?? '',
    ) );
}
add_action( 'wp_enqueue_scripts', 'stt_enqueue_scripts' );

function stt_add_button() {
    echo '<div id="stt-button"></div>';
}
add_action( 'wp_footer', 'stt_add_button' );

function stt_add_settings_page() {
    add_options_page( 
        'Scroll to Top Settings', 
        'Scroll to Top', 
        'manage_options', 
        'stt-settings', 
        'stt_settings_page'
    );
}
add_action( 'admin_menu', 'stt_add_settings_page' );

function stt_settings_page() {
    if ( isset( $_POST['stt_save_settings'] ) ) {
        check_admin_referer( 'stt_save_settings' );

        $options = array(
            'button_image' => sanitize_text_field( $_POST['button_image'] ),
        );
        update_option( 'stt_settings', $options );

        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $options = get_option( 'stt_settings' );
    ?>
    <div class="wrap">
        <h1>Scroll to Top Settings</h1>
        <form method="post">
            <?php wp_nonce_field( 'stt_save_settings' ); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="button_image">Button Image URL</label></th>
                    <td>
                        <input type="text" id="button_image" name="button_image" value="<?php echo esc_attr( $options['button_image'] ?? '' ); ?>" class="regular-text">
                        <p class="description">Enter the URL of the image you want to use for the button.</p>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="stt_save_settings" id="submit" class="button button-primary" value="Save Changes">
            </p>
        </form>
    </div>
    <?php
}

function stt_add_default_styles() {
    $css = "
        #stt-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background: url('" . plugin_dir_url( __FILE__ ) . "images/default-arrow.png') no-repeat center center;
            background-size: cover;
            display: none;
            cursor: pointer;
            z-index: 9999;
        }
    ";
    wp_add_inline_style( 'stt-style', $css );
}
add_action( 'wp_enqueue_scripts', 'stt_add_default_styles' );

register_activation_hook( __FILE__, function() {
    if ( ! file_exists( plugin_dir_path( __FILE__ ) . 'images' ) ) {
        mkdir( plugin_dir_path( __FILE__ ) . 'images', 0755, true );
    }
    copy( plugin_dir_path( __FILE__ ) . 'default-arrow.png', plugin_dir_path( __FILE__ ) . 'images/default-arrow.png' );
} );