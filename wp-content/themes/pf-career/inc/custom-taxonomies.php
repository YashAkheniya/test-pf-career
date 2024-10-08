<?php
/**
 * Custom Taxonomies
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Register the 'jobs_department' custom taxonomy for the 'jobs' post type
 */
function create_jobs_taxonomy() {
    register_taxonomy(
        'jobs_department',
        'jobs',
        array(
            'label' => __('Job Department'),
            'rewrite' => array('slug' => 'jobs-department'),
            'hierarchical' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
        )
    );
}
add_action('init', 'create_jobs_taxonomy');

/**
 * Register the 'jobs_location' custom taxonomy and associate it with 'jobs' post type
 */
function register_jobs_location_taxonomy() {
    $labels = array(
        'name'              => _x('Job Locations', 'taxonomy general name'),
        'singular_name'     => _x('Job Location', 'taxonomy singular name'),
        'search_items'      => __('Search Job Locations'),
        'all_items'         => __('All Job Locations'),
        'parent_item'       => __('Parent Job Location'),
        'parent_item_colon' => __('Parent Job Location:'),
        'edit_item'         => __('Edit Job Location'),
        'update_item'       => __('Update Job Location'),
        'add_new_item'      => __('Add New Job Location'),
        'new_item_name'     => __('New Job Location Name'),
        'menu_name'         => __('Job Location'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'job-location'),
        'show_in_rest'      => true,
    );

    register_taxonomy('jobs_location', array('jobs'), $args);
}
add_action('init', 'register_jobs_location_taxonomy');
