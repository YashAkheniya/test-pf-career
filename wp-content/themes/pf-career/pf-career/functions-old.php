<?php

/**
 * Recommended way to include parent theme styles.
 * (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
 *
 */

add_action('wp_enqueue_scripts', 'pf_career_style');
function pf_career_style()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
}

/**
 * Your code goes below.
 */


// Register Custom Post Type for Jobs
function create_jobs_post_type()
{
    $labels = array(
        'name'                  => _x('Jobs', 'Post Type General Name', 'text_domain'),
        'singular_name'         => _x('Job', 'Post Type Singular Name', 'text_domain'),
        'menu_name'             => __('Jobs', 'text_domain'),
        'name_admin_bar'        => __('Job', 'text_domain'),
        'archives'              => __('Job Archives', 'text_domain'),
        'attributes'            => __('Job Attributes', 'text_domain'),
        'parent_item_colon'     => __('Parent Job:', 'text_domain'),
        'all_items'             => __('All Jobs', 'text_domain'),
        'add_new_item'          => __('Add New Job', 'text_domain'),
        'add_new'               => __('Add New', 'text_domain'),
        'new_item'              => __('New Job', 'text_domain'),
        'edit_item'             => __('Edit Job', 'text_domain'),
        'update_item'           => __('Update Job', 'text_domain'),
        'view_item'             => __('View Job', 'text_domain'),
        'view_items'            => __('View Jobs', 'text_domain'),
        'search_items'          => __('Search Job', 'text_domain'),
        'not_found'             => __('Not found', 'text_domain'),
        'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
        'featured_image'        => __('Featured Image', 'text_domain'),
        'set_featured_image'    => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image'    => __('Use as featured image', 'text_domain'),
        'insert_into_item'      => __('Insert into job', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this job', 'text_domain'),
        'items_list'            => __('Jobs list', 'text_domain'),
        'items_list_navigation' => __('Jobs list navigation', 'text_domain'),
        'filter_items_list'     => __('Filter jobs list', 'text_domain'),
    );
    $args = array(
        'label'                 => __('Job', 'text_domain'),
        'description'           => __('Job listings', 'text_domain'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type('jobs', $args);
}
add_action('init', 'create_jobs_post_type', 0);

// Add Meta Boxes for Job Custom Fields
function add_jobs_meta_boxes()
{
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

// HTML for the Custom Fields Meta Box
function jobs_meta_box_html($post)
{
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

// Save the Meta Box Data
function save_jobs_meta_boxes($post_id)
{
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


// Register the 'jobs_department' custom taxonomy for the 'jobs' post type
function create_jobs_taxonomy()
{
    register_taxonomy(
        'jobs_department', // Taxonomy slug
        'jobs',         // Post type it is associated with
        array(
            'label' => __('Job Department'),
            'rewrite' => array('slug' => 'jobs-department'),
            'hierarchical' => true, // Departments are hierarchical
            'show_admin_column' => true,
            'show_in_rest' => true, // Enable for REST API and Gutenberg
        )
    );
}
add_action('init', 'create_jobs_taxonomy');

// Register the 'jobs_location' custom taxonomy and associate it with 'jobs' post type only
function register_jobs_location_taxonomy()
{
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
        'hierarchical'      => true, // Locations are hierarchical
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'job-location'),
        'show_in_rest'      => true, // Enable for REST API and Gutenberg
    );

    // Register 'jobs_location' only for 'jobs' post type
    register_taxonomy('jobs_location', array('jobs'), $args);
}
add_action('init', 'register_jobs_location_taxonomy');

// Function to fetch departments from Greenhouse API and create job departments
function fetch_and_create_departments_as_categories()
{
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

// Function to fetch job locations from Greenhouse API and create job locations
function fetch_and_create_locations_as_categories()
{
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

// Function to fetch API data and create job posts dynamically
function fetch_and_create_jobs()
{
    $api_url = 'https://boards-api.greenhouse.io/v1/boards/propertyfinder/jobs/?content=true';
    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        error_log('Error fetching jobs from API: ' . $response->get_error_message());
        return;
    }

    $api_data = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($api_data['jobs']) && !empty($api_data['jobs'])) {
        foreach ($api_data['jobs'] as $job) {
            $existing_job = get_posts(array(
                'post_type'  => 'jobs',
                'meta_key'   => '_job_id',
                'meta_value' => $job['id'],
                'posts_per_page' => 1,
            ));

            if ($existing_job) {
                continue; // Skip if job already exists
            }

            $job_content = html_entity_decode($job['content']);
            $job_content = wp_kses_post($job_content);

            $new_post = array(
                'post_title'    => wp_strip_all_tags($job['title']),
                'post_content'  => $job_content,
                'post_status'   => 'publish',
                'post_type'     => 'jobs',
                'post_author'   => 1,
            );

            $post_id = wp_insert_post($new_post);

            if ($post_id) {
                update_post_meta($post_id, '_job_id', $job['id']);
                update_post_meta($post_id, '_job_title', $job['title']);
                update_post_meta($post_id, '_department_name', $job['departments'][0]['name']);
                update_post_meta($post_id, '_job_location', $job['location']['name']);
                update_post_meta($post_id, '_job_url', esc_url($job['absolute_url']));
                update_post_meta($post_id, '_social_url', esc_url($job['internal_job_link']));

                // Get the location from the job post
                $job_location_meta = wp_strip_all_tags($job['location']['name']);

                // Fetch all job location terms
                $locations = get_terms(array(
                    'taxonomy' => 'jobs_location',
                    'hide_empty' => false,
                ));

                // Split the job location meta into words
                $location_words = explode(' ', $job_location_meta);
                $matched_term_ids = [];

                // Check for matches
                foreach ($locations as $location) {
                    foreach ($location_words as $word) {
                        if (stripos($location->name, $word) !== false) {
                            $matched_term_ids[] = $location->term_id;
                            break; // Stop checking other words for this term if a match is found
                        }
                    }
                }

                // Assign matched terms to the job post
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



// Schedule event to fetch jobs every hour
function schedule_jobs_fetch_event()
{
    if (! wp_next_scheduled('fetch_and_create_jobs_event')) {
        wp_schedule_event(time(), 'hourly', 'fetch_and_create_jobs_event');
    }
}
add_action('wp', 'schedule_jobs_fetch_event');

// Hook the fetch function to the scheduled event
add_action('fetch_and_create_jobs_event', 'fetch_and_create_jobs');

// Flush rewrite rules on activation
function flush_rewrite_on_activation()
{
    create_jobs_taxonomy();
    register_jobs_location_taxonomy();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'flush_rewrite_on_activation');

// custom searchbar with view more button end

function enqueue_product_filter_script()
{
    wp_enqueue_script('custom-live-search', get_stylesheet_directory_uri() . '/js/ajax-search.js', array('jquery'), time(), true);
    wp_localize_script('custom-live-search', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    wp_localize_script('custom-live-search', 'homeUrl', array('url' => site_url()));
    wp_localize_script('custom-live-search', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'url' => site_url()));
}
add_action('wp_enqueue_scripts', 'enqueue_product_filter_script');

function custom_search_form_with_live_results_and_icon_shortcode()
{
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


// Function to handle live search AJAX request
function custom_live_search()
{
    $search_query = $_POST['search_query'];

    // $query = new WP_Query(array(
    //     's' => $search_query,
    //     'posts_per_page' => 6,
    //     'post_status' => 'publish',
    //     'post_type' => 'jobs',
    // ));

    $args = array(
        'taxonomy'   => 'jobs_department',  // The taxonomy you want to search (in this case, 'category')
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,  // Show even if no posts are associated with the category
        'search'     => $search_query,  // This searches category names that match the input
    );

    $categories = get_terms($args);

    if (!empty($categories) && !is_wp_error($categories)) {
        
        foreach ($categories as $category) {
            echo '<a href="/job-search?custom-serarch='. $category->name.'">' . esc_html($category->name) . '</a><br>';
        }
    } else {
        echo '<p>No categories found matching "' . esc_html($search_query) . '".</p>';
    }
    // if ($query->have_posts()) {
    //     while ($query->have_posts()) {
    //         $query->the_post();
    //         echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a><br>';
    //     }
    //     wp_reset_postdata();
    // } else {
    //     echo 'No results found.';
    // }

    wp_die();
}
add_action('wp_ajax_custom_live_search', 'custom_live_search');
add_action('wp_ajax_nopriv_custom_live_search', 'custom_live_search');

function job_listing_filter()
{
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
    ?>
            <div class="job-card">
                <div class="job-card-header">
                    <div class="job-card-header-cnt">
                        <h3><?= get_the_title() ?></h3>
                        <?php
                        $jobs_locations = get_the_terms(get_the_ID(), 'jobs_location');
                        if (!empty($jobs_locations) && !is_wp_error($jobs_locations)) {
                            $jobs_locations_list = wp_list_pluck($jobs_locations, 'name');
                            $locations = implode(', ', $jobs_locations_list);
                        } else {
                            $locations = 'No locations found';
                        }
                        ?>
                        <div class="job-location">
                            <button class="save-button" data-job-id="<?= get_the_ID() ?>">
                                <span class="heart-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19.6651 5.84554C18.7391 4.98447 17.5173 4.50527 16.2479 4.50527C14.9785 4.50527 13.7567 4.98447 12.8307 5.84554L11.9914 6.64816L11.1321 5.83563C10.2067 4.97742 8.98696 4.5 7.71993 4.5C6.45291 4.5 5.23321 4.97742 4.30775 5.83563C2.40932 7.60932 2.72906 10.3442 4.29776 12.2665C6.73361 14.8088 9.30165 17.2233 11.9914 19.5C11.9914 19.5 17.9864 14.3573 19.6651 12.2764C21.3437 10.1955 21.5435 7.61923 19.6651 5.84554Z" fill="none" stroke="none" stroke-width="2" stroke-miterlimit="10"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.62871 5.10238C4.73987 4.07197 6.20273 3.5 7.72085 3.5C9.23897 3.5 10.7018 4.07197 11.813 5.10238L11.8201 5.10899L11.9884 5.26822L12.1506 5.11315C13.2624 4.0793 14.7278 3.50527 16.2488 3.50527C17.7697 3.50527 19.2351 4.07937 20.3469 5.11322L20.3525 5.11843C21.4968 6.19888 22.0309 7.55837 21.9996 8.96797C21.9688 10.3564 21.3935 11.7276 20.4443 12.9043C19.5459 14.0179 17.5633 15.8786 15.8706 17.4101C15.011 18.1877 14.2048 18.8995 13.6137 19.4166C13.3181 19.6752 13.0759 19.8854 12.9075 20.0312C12.8233 20.104 12.7575 20.1608 12.7127 20.1994L12.6438 20.2587C12.6437 20.2587 12.6434 20.259 11.9923 19.5L12.6438 20.2587L11.9966 20.8138L11.3462 20.2633C8.62996 17.9641 6.03655 15.5258 3.57661 12.9583L3.54907 12.9296L3.52391 12.8987C1.74542 10.7193 1.21547 7.35704 3.62598 5.10492L3.62871 5.10238ZM11.9877 18.1812C12.0823 18.0987 12.1857 18.0085 12.2968 17.9113C12.8823 17.3991 13.6797 16.6951 14.5288 15.927C16.2532 14.3669 18.1074 12.6158 18.8876 11.6486C19.617 10.7444 19.981 9.78694 20.0001 8.9236C20.0188 8.08247 19.7144 7.26793 18.9822 6.57526C18.2424 5.88861 17.2653 5.50527 16.2488 5.50527C15.2332 5.50527 14.257 5.88791 13.5174 6.57337L11.9962 8.0281L10.4497 6.56575C9.71034 5.88172 8.73523 5.5 7.72085 5.5C6.70555 5.5 5.72961 5.8824 4.99003 6.56758C3.61331 7.85526 3.71372 9.94454 5.04936 11.6045C7.25537 13.9051 9.57079 16.0998 11.9877 18.1812Z" fill="#757575"></path>
                                    </svg></span> Save
                            </button>
                            <span>
                                <svg
                                    width="12"
                                    height="16"
                                    viewBox="0 0 12 16"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6 15.5C5.79116 15.4982 5.58499 15.4528 5.39471 15.3667C5.20443 15.2806 5.03423 15.1557 4.895 15C3.07 13 7.50879e-08 9.195 7.50879e-08 6.68C-0.0227161 5.06517 0.596394 3.50733 1.72137 2.34861C2.84634 1.18989 4.3852 0.525012 6 0.5C7.6148 0.525012 9.15366 1.18989 10.2786 2.34861C11.4036 3.50733 12.0227 5.06517 12 6.68C12 9.18 8.93 12.975 7.105 15.005C6.96533 15.1598 6.79492 15.2837 6.60467 15.3689C6.41441 15.4542 6.20847 15.4988 6 15.5ZM6 1.5C4.65084 1.52629 3.36709 2.0862 2.42992 3.05711C1.49276 4.02801 0.978577 5.33075 1 6.68C1 8.25 2.735 11.11 5.64 14.335C5.68664 14.3834 5.74256 14.4219 5.80442 14.4482C5.86627 14.4745 5.93279 14.488 6 14.488C6.06721 14.488 6.13373 14.4745 6.19559 14.4482C6.25744 14.4219 6.31336 14.3834 6.36 14.335C9.265 11.11 11 8.25 11 6.68C11.0214 5.33075 10.5072 4.02801 9.57008 3.05711C8.63292 2.0862 7.34916 1.52629 6 1.5Z"
                                        fill="#333333"
                                        fill-opacity="0.5" />
                                    <path
                                        d="M6 9.5C5.40666 9.5 4.82664 9.32405 4.33329 8.99441C3.83994 8.66477 3.45543 8.19623 3.22836 7.64805C3.0013 7.09987 2.94189 6.49667 3.05765 5.91473C3.1734 5.33279 3.45912 4.79824 3.87868 4.37868C4.29824 3.95912 4.83279 3.6734 5.41473 3.55765C5.99667 3.44189 6.59987 3.5013 7.14805 3.72836C7.69623 3.95543 8.16477 4.33994 8.49441 4.83329C8.82405 5.32664 9 5.90666 9 6.5C9 7.29565 8.68393 8.05871 8.12132 8.62132C7.55871 9.18393 6.79565 9.5 6 9.5ZM6 4.5C5.60444 4.5 5.21776 4.6173 4.88886 4.83706C4.55996 5.05683 4.30362 5.36918 4.15224 5.73463C4.00087 6.10009 3.96126 6.50222 4.03843 6.89018C4.1156 7.27814 4.30608 7.63451 4.58579 7.91422C4.86549 8.19392 5.22186 8.3844 5.60982 8.46157C5.99778 8.53874 6.39992 8.49914 6.76537 8.34776C7.13082 8.19639 7.44318 7.94004 7.66294 7.61114C7.8827 7.28224 8 6.89556 8 6.5C8 5.96957 7.78929 5.46086 7.41421 5.08579C7.03914 4.71072 6.53043 4.5 6 4.5Z"
                                        fill="#333333"
                                        fill-opacity="0.5" />
                                </svg>
                                <?= $locations  ?>
                            </span>
                        </div>
                    </div>
                    <?php
                    $job_departments = get_the_terms(get_the_ID(), 'jobs_department');
                    if (!empty($job_departments) && !is_wp_error($job_departments)) {
                        $job_department_list = wp_list_pluck($job_departments, 'name');
                        $department = implode(', ', $job_department_list);
                    } else {
                        $department = 'No department found';
                    }
                    ?>
                    <span class="job-department"><?= $department ?></span>
                </div>
                <div class="job-card-body">
                    <?= wp_trim_words(get_the_content(), 60, '...') ?>
                </div>
                <div class="job-card-footer">
                    <a href="<?= get_permalink() ?>" class="ftr-btn">
                        Read More
                        <svg
                            width="12"
                            height="11"
                            viewBox="0 0 12 11"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.7929 0H11.4515V10.2068H10.1343V2.24832L1.71022 10.6724L0.779053 9.74125L9.2027 1.31715H1.24464V0H10.7929Z"
                                fill="#333333" />
                        </svg>
                    </a>
                </div>
            </div>
            <hr />
<?php
        }
        wp_reset_postdata();
    } else {
        echo 'No results found.';
    }

    wp_die();
}
add_action('wp_ajax_job_listing_filter', 'job_listing_filter');
add_action('wp_ajax_nopriv_job_listing_filter', 'job_listing_filter');


function job_listing_location_count()
{
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
