<?php
/**
 * Template part for displaying the jobs departments cards
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<div class="jobs-departments-cards">
    <?php foreach ($departments as $department) : ?>
        <div class="dept-cards-wrap">
            <div class="depart-card">
                <div class="ctm-depart-card-head">
                    <h2><?php echo esc_html($department->name); ?></h2>
                    
                    <?php 
                    $description = wp_trim_words($department->description, 20, '...');
                    echo '<p class="description">' . wp_kses_post($description) . '</p>';
                    
                    $job_count = $department->count;
                    ?>
                </div>
                <div class="dept-card-footer">
                    <span class="dept-job-count"><?php echo $job_count . ' job' . ($job_count != 1 ? 's' : ''); ?></span>
                    <a href="<?php echo get_term_link($department); ?>" class="read-more">Read More <svg width="12" height="11" viewBox="0 0 12 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7931 0H11.4517V10.2068H10.1346V2.24832L1.71046 10.6724L0.779297 9.74125L9.20294 1.31715H1.24488V0H10.7931Z" fill="white"/>
                    </svg></a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>