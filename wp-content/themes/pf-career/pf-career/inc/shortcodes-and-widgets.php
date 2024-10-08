<?php
/**
 * Shortcodes and Widgets
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Shortcode for Expanding Card Why Property Finder
 */
function expanding_card_section_shortcode() {
    ob_start();

    $posts_in = get_field('select_posts');
    $field_groups = acf_get_field_groups();
    foreach ($field_groups as $field_group) {
        if ($field_group['title'] === 'Expanding Tabs') {
            // Include the HTML and PHP code for the expanding card section
            include(get_stylesheet_directory() . '/template-parts/expanding-card-section.php');
        }
    }

    return ob_get_clean();
}
add_shortcode('expanding_card_section', 'expanding_card_section_shortcode');

/**
 * Shortcode for Jobs Departments List
 */
function jobs_departments_list_shortcode($atts) {
    $atts = shortcode_atts(array(
        'include' => '32,11,16,19,10,13',
    ), $atts);

    $args = array(
        'taxonomy' => 'jobs_department',
        'hide_empty' => false,
        'include' => array_map('trim', explode(',', $atts['include'])),
    );

    $departments = get_terms($args);

    if (empty($departments) || is_wp_error($departments)) {
        return 'No departments found.';
    }

    ob_start();
    include(get_stylesheet_directory() . '/template-parts/jobs-departments-slider.php');
    return ob_get_clean();
}
add_shortcode('jobs_departments_list', 'jobs_departments_list_shortcode');

/**
 * Shortcode for Jobs Departments Cards
 */
function jobs_departments_cards_shortcode($atts) {
    $atts = shortcode_atts(array(
        'exclude' => '',
    ), $atts);

    $args = array(
        'taxonomy' => 'jobs_department',
        'hide_empty' => false,
        'include' => array_map('trim', explode(',', $atts['include'])),
    );

    $departments = get_terms($args);

    if (empty($departments) || is_wp_error($departments)) {
        return 'No departments found.';
    }

    // Sort departments by name alphabetically (A first, B second, etc.)
    usort($departments, function($a, $b) {
        return strcmp($a->name, $b->name);
    });

    ob_start();
    include(get_stylesheet_directory() . '/template-parts/jobs-departments-cards.php');
    return ob_get_clean();
}
add_shortcode('jobs_departments_cards', 'jobs_departments_cards_shortcode');

/**
 * Enqueue Slick Slider
 */
function enqueue_slick_slider() {
    wp_enqueue_style('slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
    wp_enqueue_script('slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_slick_slider');