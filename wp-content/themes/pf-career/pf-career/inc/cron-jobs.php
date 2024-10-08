<?php
/**
 * Cron Jobs and Miscellaneous Functions
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add custom intervals for cron schedules
 */
function custom_cron_schedules( $schedules ) {
    $schedules['every_30_seconds'] = array(
        'interval' => 30,
        'display'  => __( 'Every 30 Seconds' )
    );
    $schedules['every_minute'] = array(
        'interval' => 60,
        'display'  => __('Every Minute')
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'custom_cron_schedules' );

/**
 * Schedule the jobs fetch event
 */
function schedule_jobs_fetch_event() {
    if ( ! wp_next_scheduled( 'fetch_and_create_jobs_event' ) ) {
        wp_schedule_event( time(), 'every_30_seconds', 'fetch_and_create_jobs_event' );
        pf_add_log_entry('Scheduled fetch_and_create_jobs_event', 'cron');
    }
}
add_action( 'wp', 'schedule_jobs_fetch_event' );

/**
 * Schedule the API call for job updates
 */
function schedule_api_check_job_updates() {
    if (!wp_next_scheduled('check_job_updates_event')) {
        wp_schedule_event(time(), 'every_minute', 'check_job_updates_event');
        pf_add_log_entry('Scheduled check_job_updates_event', 'cron');
    }
}
add_action('wp', 'schedule_api_check_job_updates');

/**
 * Unschedule the job updates event on theme deactivation
 */
function unschedule_api_check_job_updates() {
    $timestamp = wp_next_scheduled('check_job_updates_event');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'check_job_updates_event');
    }
}
register_deactivation_hook(__FILE__, 'unschedule_api_check_job_updates');

/**
 * Hook the fetch functions to the scheduled events
 * These functions are defined in api-integration.php
 */
function pf_log_fetch_and_create_jobs() {
    pf_add_log_entry('Running fetch_and_create_jobs', 'cron');
    fetch_and_create_jobs();
}
add_action( 'fetch_and_create_jobs_event', 'pf_log_fetch_and_create_jobs' );

function pf_log_fetch_and_update_jobs() {
    pf_add_log_entry('Running fetch_and_update_jobs', 'cron');
    fetch_and_update_jobs();
}
add_action('check_job_updates_event', 'pf_log_fetch_and_update_jobs');

// add_action( 'fetch_and_create_jobs_event', 'fetch_and_create_jobs' );
// add_action('check_job_updates_event', 'fetch_and_update_jobs');

/**
 * Flush rewrite rules on activation
 */
function flush_rewrite_on_activation() {
    // These functions are now in separate files, so we need to include them here
    require_once get_stylesheet_directory() . '/inc/custom-post-types.php';
    require_once get_stylesheet_directory() . '/inc/custom-taxonomies.php';
    
    create_jobs_post_type();
    create_jobs_taxonomy();
    register_jobs_location_taxonomy();
    flush_rewrite_rules();
}

/**
 * ACF JSON save point
 */
function my_acf_json_save_point( $path ) {
    $path = get_stylesheet_directory() . '/acf-json';
    return $path;
}
add_filter('acf/settings/save_json', 'my_acf_json_save_point');

/**
 * Allow SVG uploads
 */
function allow_svg_uploads($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'allow_svg_uploads');

/**
 * Fix SVG display in admin
 */
function fix_svg_display() {
    echo '<style type="text/css">
        .attachment-svg { width: 100%; height: auto; }
    </style>';
}
add_action('admin_head', 'fix_svg_display');