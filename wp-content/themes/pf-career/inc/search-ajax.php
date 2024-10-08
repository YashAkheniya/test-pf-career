<?php
/**
 * Search and AJAX Functionality
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Enqueue scripts for custom live search
 */
function enqueue_product_filter_script() {
    wp_enqueue_script('custom-live-search', get_stylesheet_directory_uri() . '/js/ajax-search.js', array('jquery'), time(), true);
    wp_localize_script('custom-live-search', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    wp_localize_script('custom-live-search', 'homeUrl', array('url' => site_url()));
    wp_localize_script('custom-live-search', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'url' => site_url()));
}
add_action('wp_enqueue_scripts', 'enqueue_product_filter_script');

/**
 * Shortcode for custom search form with live results and icon
 */
function custom_search_form_with_live_results_and_icon_shortcode() {
    $home_url = esc_url(site_url());
    $output = '<div class="custom-search-container">';
    $output .= '<form id="search-form" action="' . $home_url . '/job-search" method="get" autocomplete="off">';
    $output .= '<input type="text" id="live-search-input" name="custom-serarch" placeholder="Search Jobs by Keyword" />';
    $output .= '<button id="search-icons" type="submit" >';
    $output .= '<img decoding="async" src="' . get_stylesheet_directory_uri() . '/assets/images/search-icon-shape.svg" alt="Search" />';
    $output .= '</button>';
    $output .= '<div id="live-search-results-new1"></div>';
    $output .= '</form>';
    $output .= '</div>';

    return $output;
}
add_shortcode('custom_search_with_live_results_and_icon', 'custom_search_form_with_live_results_and_icon_shortcode');

/**
 * Handle live search AJAX request
 */
function custom_live_search() {
    $search_query = $_POST['search_query'];

    $args = array(
        'taxonomy'   => 'jobs_department',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
        'search'     => $search_query,
    );

    $categories = get_terms($args);

    if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            echo '<a href="/job-search?custom-serarch='. $category->name.'">' . esc_html($category->name) . '</a><br>';
        }
    } else {
        echo '<p>No categories found matching "' . esc_html($search_query) . '".</p>';
    }

    wp_die();
}
add_action('wp_ajax_custom_live_search', 'custom_live_search');
add_action('wp_ajax_nopriv_custom_live_search', 'custom_live_search');

/**
 * Handle job listing filter AJAX request
 */
function job_listing_filter() {
    $job_departments = $_POST['job_departments'];
    $jobs_locations = $_POST['jobs_locations'];

    $meta_qury = [
        'relation' => 'AND',
    ];
    if (!empty($job_departments)) {
        $job_department_term = get_term_by('name', $job_departments, 'jobs_department');
        if ($job_department_term) {
            $meta_qury[] = array(
                'taxonomy' => 'jobs_department',
                'field'    => 'term_id',
                'terms'    => array($job_department_term->term_id),
                'operator' => 'IN',
            );
        }
    }
    if (!empty($jobs_locations)) {
        $meta_qury[] = array(
            'taxonomy' => 'jobs_location',
            'field'    => 'slug',
            'terms'    => explode(",", $jobs_locations),
            'operator' => 'IN',
        );
    }
    $job_args = array(
        'post_type' => 'jobs',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'tax_query'   => $meta_qury,
    );
    if (!empty($job_departments)) {
        $job_args['s'] = $job_departments; 
    }
    $jobs_posts_query = new WP_Query($job_args);

    if ($jobs_posts_query->have_posts()) {
        while ($jobs_posts_query->have_posts()) {
            $jobs_posts_query->the_post();
            // Output job card HTML
            include(get_stylesheet_directory() . '/template-parts/job-card.php');
        }
        wp_reset_postdata();
    } else {
        echo 'No results found.';
    }

    wp_die();
}
add_action('wp_ajax_job_listing_filter', 'job_listing_filter');
add_action('wp_ajax_nopriv_job_listing_filter', 'job_listing_filter');

/**
 * Handle job listing location count AJAX request
 */
function job_listing_location_count() {
    if (isset($_POST['job_departments']) && !empty($_POST['job_departments'])) {
        $meta_qury = [
            'relation' => 'AND',
        ];
        $job_department = sanitize_text_field($_POST['job_departments']);
        if (!empty($job_department)) {
            $job_department_term = get_term_by('name', $job_department, 'jobs_department');
            if ($job_department_term) {
                $meta_qury[] = array(
                    'taxonomy' => 'jobs_department',
                    'field'    => 'term_id',
                    'terms'    => array($job_department_term->term_id),
                    'operator' => 'IN',
                );
            }
        }

        $args = array(
            'post_type'      => 'jobs',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'tax_query'      => $meta_qury,
        );
        $args['s'] = $job_department;  
        $query = new WP_Query($args);

        $location_counts = array();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $locations = wp_get_post_terms(get_the_ID(), 'jobs_location', array('fields' => 'slugs'));
                foreach ($locations as $location) {
                    if (isset($location_counts[$location])) {
                        $location_counts[$location]++;
                    } else {
                        $location_counts[$location] = 1;
                    }
                }
            }
        }
        wp_send_json_success($location_counts);
    } else {
        wp_send_json_error('Invalid department');
    }

    wp_die();
}
add_action('wp_ajax_job_listing_location_count', 'job_listing_location_count');
add_action('wp_ajax_nopriv_job_listing_location_count', 'job_listing_location_count');