<?php
/**
 * Logging Functions
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add a log entry
 *
 * @param string $message The log message
 * @param string $type The type of log entry (e.g., 'cron', 'job', 'error')
 */
function pf_add_log_entry($message, $type = 'general') {
    $max_logs = 1000; // Maximum number of logs to keep

    $logs = get_option('pf_activity_logs', array());
    
    $new_entry = array(
        'timestamp' => current_time('mysql'),
        'message' => $message,
        'type' => $type
    );

    array_unshift($logs, $new_entry);

    if (count($logs) > $max_logs) {
        array_pop($logs);
    }

    update_option('pf_activity_logs', $logs);
}

/**
 * Get all log entries
 *
 * @return array Log entries
 */
function pf_get_log_entries() {
    return get_option('pf_activity_logs', array());
}

/**
 * Clear all log entries
 */
function pf_clear_log_entries() {
    update_option('pf_activity_logs', array());
}