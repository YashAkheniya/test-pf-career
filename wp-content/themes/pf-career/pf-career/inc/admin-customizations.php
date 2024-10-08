<?php
/**
 * Admin Customizations
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add Job ID column to Job Post Type in Admin
 */
function add_jobs_custom_columns($columns) {
    $columns['job_id'] = __('Job ID');
    return $columns;
}
add_filter('manage_jobs_posts_columns', 'add_jobs_custom_columns');

/**
 * Populate the Job ID column
 */
function manage_jobs_custom_columns($column, $post_id) {
    if ($column == 'job_id') {
        $job_id = get_post_meta($post_id, '_job_id', true);
        echo esc_html($job_id);
    }
}
add_action('manage_jobs_posts_custom_column', 'manage_jobs_custom_columns', 10, 2);

/**
 * Make Job ID column sortable
 */
function jobs_sortable_columns($columns) {
    $columns['job_id'] = 'job_id';
    return $columns;
}
add_filter('manage_edit-jobs_sortable_columns', 'jobs_sortable_columns');