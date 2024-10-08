<?php
/**
 * API Integration Functions
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Fetch departments from Greenhouse API and create job departments
 */
function fetch_and_create_departments_as_categories() {
    $url = 'https://boards-api.greenhouse.io/v1/boards/propertyfinder/departments';
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        error_log('Error fetching departments from API: ' . $response->get_error_message());
        return;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (!empty($data['departments'])) {
        foreach ($data['departments'] as $department) {
            $department_name = wp_strip_all_tags($department['name']);
            $term = term_exists($department_name, 'jobs_department');

            if ($term === 0 || $term === null) {
                $term = wp_insert_term($department_name, 'jobs_department');
                error_log('Created new department: ' . $department_name);
            } else {
                error_log('Department already exists: ' . $department_name);
            }

            if (!empty($department['child_ids'])) {
                foreach ($department['child_ids'] as $child_id) {
                    $child_department = array_filter($data['departments'], function ($dep) use ($child_id) {
                        return $dep['id'] === $child_id;
                    });

                    if ($child_department) {
                        $child_department = array_shift($child_department);
                        $child_name = wp_strip_all_tags($child_department['name']);
                        $child_term = term_exists($child_name, 'jobs_department');

                        if ($child_term === 0 || $child_term === null) {
                            $child_term = wp_insert_term($child_name, 'jobs_department', array(
                                'parent' => $term['term_id']
                            ));
                            error_log('Created new child department: ' . $child_name);
                        }
                    }
                }
            }
        }
    }
}
add_action('init', 'fetch_and_create_departments_as_categories');

/**
 * Fetch job locations from Greenhouse API and create job locations
 */
function fetch_and_create_locations_as_categories() {
    $url = 'https://boards-api.greenhouse.io/v1/boards/propertyfinder/offices';
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        error_log('Error fetching locations from API: ' . $response->get_error_message());
        return;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (!empty($data['offices'])) {
        foreach ($data['offices'] as $office) {
            $location_name = wp_strip_all_tags($office['location']);
            $term = term_exists($location_name, 'jobs_location');

            if ($term === 0 || $term === null) {
                $term = wp_insert_term($location_name, 'jobs_location');
                if (!is_wp_error($term)) {
                    error_log('Created new location: ' . $location_name);
                } else {
                    error_log('Error creating location: ' . $term->get_error_message());
                }
            } else {
                error_log('Location already exists: ' . $location_name);
            }
        }
    } else {
        error_log('No location data found in the API response.');
    }
}
add_action('init', 'fetch_and_create_locations_as_categories');

/**
 * Fetch API data and create job posts dynamically
 */
function fetch_and_create_jobs() {
    $api_url = 'https://boards-api.greenhouse.io/v1/boards/propertyfinder/jobs/?content=true';
    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        error_log('Error fetching jobs from API: ' . $response->get_error_message());
        return;
    }

    $api_data = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($api_data['jobs']) && !empty($api_data['jobs'])) {
        foreach ($api_data['jobs'] as $job) {
            // Check if job already exists
            $existing_job = get_posts(array(
                'post_type'  => 'jobs',
                'meta_key'   => '_job_id',
                'meta_value' => $job['id'],
                'posts_per_page' => 1,
            ));

            if ($existing_job) {
                continue; // Skip if job already exists
            }

            // Sanitize and prepare job content
            $job_content = html_entity_decode($job['content']);
            $job_content = wp_kses_post($job_content);

            // Convert the job post's date from API to a format WordPress can use
            $job_publish_date = !empty($job['updated_at']) ? date('Y-m-d H:i:s', strtotime($job['updated_at'])) : current_time('mysql');

            // Create new job post
            $new_post = array(
                'post_title'    => wp_strip_all_tags($job['title']),
                'post_content'  => $job_content,
                'post_status'   => 'publish',
                'post_type'     => 'jobs',
                'post_author'   => 1, // You can change the author ID if needed
                'post_date'     => $job_publish_date, // Set the post's publish date and time
            );

            $post_id = wp_insert_post($new_post);

            if ($post_id) {
                // Save Job meta data
                update_post_meta($post_id, '_job_id', $job['id']);
                update_post_meta($post_id, '_job_title', $job['title']);
                update_post_meta($post_id, '_department_name', $job['departments'][0]['name']);
                update_post_meta($post_id, '_job_location', $job['location']['name']);
                update_post_meta($post_id, '_job_url', esc_url($job['absolute_url']));
                update_post_meta($post_id, '_social_url', esc_url($job['internal_job_link']));

                // Assign location taxonomy based on partial match
                $job_location_meta = wp_strip_all_tags($job['location']['name']);
                $locations = get_terms(array(
                    'taxonomy' => 'jobs_location',
                    'hide_empty' => false,
                ));
                $location_words = explode(' ', $job_location_meta);
                $matched_term_ids = [];

                foreach ($locations as $location) {
                    foreach ($location_words as $word) {
                        if (stripos($location->name, $word) !== false) {
                            $matched_term_ids[] = $location->term_id;
                            break; // Stop checking other words for this term if a match is found
                        }
                    }
                }

                if (!empty($matched_term_ids)) {
                    wp_set_post_terms($post_id, $matched_term_ids, 'jobs_location', true);
                    error_log('Assigned matching locations to post: ' . $post_id);
                } else {
                    error_log('No matching locations found for: ' . $job_location_meta);
                }

                // Assign department taxonomy
                if (!empty($job['departments'][0]['name'])) {
                    $department_name = wp_strip_all_tags($job['departments'][0]['name']);
                    $term = term_exists($department_name, 'jobs_department');

                    if ($term !== 0 && $term !== null) {
                        wp_set_post_terms($post_id, $term['term_id'], 'jobs_department', false);
                        error_log('Assigned department: ' . $department_name . ' to post: ' . $post_id);
                    } else {
                        error_log('Department not found for: ' . $department_name);
                    }
                }
            }
        }
    }
}
add_action('wp_loaded', 'fetch_and_create_jobs');