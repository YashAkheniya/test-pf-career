<?php
/**
 * Custom Post Types
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Register Custom Post Type for Jobs
 */
function create_jobs_post_type() {
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

/**
 * Register Custom Post Type for Team
 */
function create_team_post_type() {
    $labels = array(
        'name'                  => _x('Team', 'Post Type General Name', 'text_domain'),
        'singular_name'         => _x('Team Member', 'Post Type Singular Name', 'text_domain'),
        'menu_name'             => __('Team', 'text_domain'),
        'name_admin_bar'        => __('Team Member', 'text_domain'),
        'archives'              => __('Team Archives', 'text_domain'),
        'attributes'            => __('Team Member Attributes', 'text_domain'),
        'parent_item_colon'     => __('Parent Team Member:', 'text_domain'),
        'all_items'             => __('All Team Members', 'text_domain'),
        'add_new_item'          => __('Add New Team Member', 'text_domain'),
        'add_new'               => __('Add New', 'text_domain'),
        'new_item'              => __('New Team Member', 'text_domain'),
        'edit_item'             => __('Edit Team Member', 'text_domain'),
        'update_item'           => __('Update Team Member', 'text_domain'),
        'view_item'             => __('View Team Member', 'text_domain'),
        'view_items'            => __('View Team Members', 'text_domain'),
        'search_items'          => __('Search Team Member', 'text_domain'),
        'not_found'             => __('Not found', 'text_domain'),
        'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
        'featured_image'        => __('Profile Picture', 'text_domain'),
        'set_featured_image'    => __('Set profile picture', 'text_domain'),
        'remove_featured_image' => __('Remove profile picture', 'text_domain'),
        'use_featured_image'    => __('Use as profile picture', 'text_domain'),
        'insert_into_item'      => __('Insert into team member', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this team member', 'text_domain'),
        'items_list'            => __('Team members list', 'text_domain'),
        'items_list_navigation' => __('Team members list navigation', 'text_domain'),
        'filter_items_list'     => __('Filter team members list', 'text_domain'),
    );
    $args = array(
        'label'                 => __('Team Member', 'text_domain'),
        'description'           => __('Team member profiles', 'text_domain'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-groups',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );
    register_post_type('team', $args);
}
add_action('init', 'create_team_post_type', 0);