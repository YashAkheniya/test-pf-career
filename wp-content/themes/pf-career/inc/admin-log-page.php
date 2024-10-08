<?php
/**
 * Admin Log Page
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add the Activity Log page to the admin menu
 */
function pf_add_activity_log_page() {
    add_menu_page(
        'Activity Log',
        'Activity Log',
        'manage_options',
        'pf-activity-log',
        'pf_activity_log_page_content',
        'dashicons-list-view',
        30
    );
}
add_action('admin_menu', 'pf_add_activity_log_page');

/**
 * Display the Activity Log page content
 */
function pf_activity_log_page_content() {
    // Check if the clear logs button was pressed
    if (isset($_POST['clear_logs']) && check_admin_referer('pf_clear_logs')) {
        pf_clear_log_entries();
        echo '<div class="notice notice-success"><p>Logs cleared successfully.</p></div>';
    }

    $logs = pf_get_log_entries();
    ?>
    <div class="wrap">
        <h1>Activity Log</h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('pf_clear_logs'); ?>
            <input type="submit" name="clear_logs" class="button button-secondary" value="Clear Logs" onclick="return confirm('Are you sure you want to clear all logs?');" />
        </form>

        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Type</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log) : ?>
                    <tr>
                        <td><?php echo esc_html($log['timestamp']); ?></td>
                        <td><?php echo esc_html($log['type']); ?></td>
                        <td><?php echo esc_html($log['message']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}