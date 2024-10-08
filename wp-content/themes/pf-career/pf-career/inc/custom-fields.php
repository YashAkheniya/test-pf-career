<?php
/**
 * Custom Fields
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add Meta Boxes for Job Custom Fields
 */
function add_jobs_meta_boxes() {
    add_meta_box(
        'jobs_meta_box', // Unique ID
        'Job Details',   // Box title
        'jobs_meta_box_html',  // Content callback
        'jobs',          // Post type
        'normal',        // Context (normal, side, advanced)
        'high'           // Priority
    );
}
add_action('add_meta_boxes', 'add_jobs_meta_boxes');

/**
 * HTML for the Custom Fields Meta Box
 */
function jobs_meta_box_html($post) {
    $job_title = get_post_meta($post->ID, '_job_title', true);
    $department_name = get_post_meta($post->ID, '_department_name', true);
    $job_location = get_post_meta($post->ID, '_job_location', true);
    $job_url = get_post_meta($post->ID, '_job_url', true);
    $social_url = get_post_meta($post->ID, '_social_url', true);

    ?>
    <label for="job_title">Job Title:</label>
    <input type="text" name="job_title" value="<?php echo esc_attr($job_title); ?>" size="25" /><br><br>

    <label for="department_name">Department Name:</label>
    <input type="text" name="department_name" value="<?php echo esc_attr($department_name); ?>" size="25" /><br><br>

    <label for="job_location">Job Location:</label>
    <input type="text" name="job_location" value="<?php echo esc_attr($job_location); ?>" size="25" /><br><br>

    <label for="job_url">Job URL:</label>
    <input type="url" name="job_url" value="<?php echo esc_attr($job_url); ?>" size="25" /><br><br>

    <label for="social_url">Social URL:</label>
    <input type="url" name="social_url" value="<?php echo esc_attr($social_url); ?>" size="25" /><br><br>
    <?php
}

/**
 * Save the Meta Box Data
 */
function save_jobs_meta_boxes($post_id) {
    if (array_key_exists('job_title', $_POST)) {
        update_post_meta(
            $post_id,
            '_job_title',
            sanitize_text_field($_POST['job_title'])
        );
    }
    if (array_key_exists('department_name', $_POST)) {
        update_post_meta(
            $post_id,
            '_department_name',
            sanitize_text_field($_POST['department_name'])
        );
    }
    if (array_key_exists('job_location', $_POST)) {
        update_post_meta(
            $post_id,
            '_job_location',
            sanitize_text_field($_POST['job_location'])
        );
    }
    if (array_key_exists('job_url', $_POST)) {
        update_post_meta(
            $post_id,
            '_job_url',
            esc_url($_POST['job_url'])
        );
    }
    if (array_key_exists('social_url', $_POST)) {
        update_post_meta(
            $post_id,
            '_social_url',
            esc_url($_POST['social_url'])
        );
    }
}
add_action('save_post', 'save_jobs_meta_boxes');