<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Define Constants
 */
define( 'HELLO_ELEMENTOR_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function hello_elementor_child_enqueue_styles() {
    wp_enqueue_style(
        'hello-elementor-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [
            'hello-elementor-theme-style',
        ],
        HELLO_ELEMENTOR_CHILD_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_styles', 20 );

/**
 * Include custom functions files
 */
$custom_includes = [
    '/inc/custom-post-types.php',
    '/inc/custom-fields.php',
    '/inc/custom-taxonomies.php',
    '/inc/api-integration.php',
    '/inc/admin-customizations.php',
    '/inc/search-ajax.php',
    '/inc/shortcodes-and-widgets.php',
    '/inc/logging-functions.php',
    '/inc/admin-log-page.php',
    '/inc/cron-jobs.php',
];

foreach ( $custom_includes as $file ) {
    $filepath = get_stylesheet_directory() . $file;
    if ( file_exists( $filepath ) ) {
        require_once $filepath;
    }
}

/**
 * Theme Setup
 */
function hello_elementor_child_theme_setup() {
    // Add theme support for various features here if needed
    // Example: add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'hello_elementor_child_theme_setup' );