<?php
get_header();

// $jobs_locations = get_terms(array(
//     'taxonomy' => 'jobs_location',
//     'hide_empty' => false,
// ));

$departments = wp_get_post_terms(get_the_ID(), 'jobs_department');

if (!empty($departments) && !is_wp_error($departments)) {
    // Get the first department term (if multiple are assigned)
    $department_id = $departments[0]->term_id;

    // Query posts that share the same department term
    $related_args = array(
        'post_type' => 'jobs', // Replace with your custom post type
        'posts_per_page' => 5,      // Limit the number of related posts
        'post__not_in' => array(get_the_ID()), // Exclude the current post
        'tax_query' => array(
            array(
                'taxonomy' => 'jobs_department',
                'field' => 'term_id',
                'terms' => $department_id,
            ),
        ),
    );

    $related_query = new WP_Query($related_args);
}
?>
<div class="career-ui">
    <div class="pf-job-detail">
        <div class="pf-job-dtl-cnt">
            <div class="pf-dtl-header">
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
                                    <div class="save-job-post">
                                        <button class="save-button save-job-btn" data-job-id="<?= get_the_ID() ?>">
                                            <span class="heart-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M19.6651 5.84554C18.7391 4.98447 17.5173 4.50527 16.2479 4.50527C14.9785 4.50527 13.7567 4.98447 12.8307 5.84554L11.9914 6.64816L11.1321 5.83563C10.2067 4.97742 8.98696 4.5 7.71993 4.5C6.45291 4.5 5.23321 4.97742 4.30775 5.83563C2.40932 7.60932 2.72906 10.3442 4.29776 12.2665C6.73361 14.8088 9.30165 17.2233 11.9914 19.5C11.9914 19.5 17.9864 14.3573 19.6651 12.2764C21.3437 10.1955 21.5435 7.61923 19.6651 5.84554Z"
                                                        fill="none" stroke="none" stroke-width="2" stroke-miterlimit="10">
                                                    </path>
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M3.62871 5.10238C4.73987 4.07197 6.20273 3.5 7.72085 3.5C9.23897 3.5 10.7018 4.07197 11.813 5.10238L11.8201 5.10899L11.9884 5.26822L12.1506 5.11315C13.2624 4.0793 14.7278 3.50527 16.2488 3.50527C17.7697 3.50527 19.2351 4.07937 20.3469 5.11322L20.3525 5.11843C21.4968 6.19888 22.0309 7.55837 21.9996 8.96797C21.9688 10.3564 21.3935 11.7276 20.4443 12.9043C19.5459 14.0179 17.5633 15.8786 15.8706 17.4101C15.011 18.1877 14.2048 18.8995 13.6137 19.4166C13.3181 19.6752 13.0759 19.8854 12.9075 20.0312C12.8233 20.104 12.7575 20.1608 12.7127 20.1994L12.6438 20.2587C12.6437 20.2587 12.6434 20.259 11.9923 19.5L12.6438 20.2587L11.9966 20.8138L11.3462 20.2633C8.62996 17.9641 6.03655 15.5258 3.57661 12.9583L3.54907 12.9296L3.52391 12.8987C1.74542 10.7193 1.21547 7.35704 3.62598 5.10492L3.62871 5.10238ZM11.9877 18.1812C12.0823 18.0987 12.1857 18.0085 12.2968 17.9113C12.8823 17.3991 13.6797 16.6951 14.5288 15.927C16.2532 14.3669 18.1074 12.6158 18.8876 11.6486C19.617 10.7444 19.981 9.78694 20.0001 8.9236C20.0188 8.08247 19.7144 7.26793 18.9822 6.57526C18.2424 5.88861 17.2653 5.50527 16.2488 5.50527C15.2332 5.50527 14.257 5.88791 13.5174 6.57337L11.9962 8.0281L10.4497 6.56575C9.71034 5.88172 8.73523 5.5 7.72085 5.5C6.70555 5.5 5.72961 5.8824 4.99003 6.56758C3.61331 7.85526 3.71372 9.94454 5.04936 11.6045C7.25537 13.9051 9.57079 16.0998 11.9877 18.1812Z"
                                                        fill="#757575"></path>
                                                </svg></span> Save This Role
                                        </button>
                                    </div>
                                </div>
                                <div class="department-job">
                                    <div class="dprt-job">
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
                                    <div class="loc-area">
                                        <span>
                                            <svg width="12" height="16" viewBox="0 0 12 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6 15.5C5.79116 15.4982 5.58499 15.4528 5.39471 15.3667C5.20443 15.2806 5.03423 15.1557 4.895 15C3.07 13 7.50879e-08 9.195 7.50879e-08 6.68C-0.0227161 5.06517 0.596394 3.50733 1.72137 2.34861C2.84634 1.18989 4.3852 0.525012 6 0.5C7.6148 0.525012 9.15366 1.18989 10.2786 2.34861C11.4036 3.50733 12.0227 5.06517 12 6.68C12 9.18 8.93 12.975 7.105 15.005C6.96533 15.1598 6.79492 15.2837 6.60467 15.3689C6.41441 15.4542 6.20847 15.4988 6 15.5ZM6 1.5C4.65084 1.52629 3.36709 2.0862 2.42992 3.05711C1.49276 4.02801 0.978577 5.33075 1 6.68C1 8.25 2.735 11.11 5.64 14.335C5.68664 14.3834 5.74256 14.4219 5.80442 14.4482C5.86627 14.4745 5.93279 14.488 6 14.488C6.06721 14.488 6.13373 14.4745 6.19559 14.4482C6.25744 14.4219 6.31336 14.3834 6.36 14.335C9.265 11.11 11 8.25 11 6.68C11.0214 5.33075 10.5072 4.02801 9.57008 3.05711C8.63292 2.0862 7.34916 1.52629 6 1.5Z"
                                                    fill="#333333" fill-opacity="0.5" />
                                                <path
                                                    d="M6 9.5C5.40666 9.5 4.82664 9.32405 4.33329 8.99441C3.83994 8.66477 3.45543 8.19623 3.22836 7.64805C3.0013 7.09987 2.94189 6.49667 3.05765 5.91473C3.1734 5.33279 3.45912 4.79824 3.87868 4.37868C4.29824 3.95912 4.83279 3.6734 5.41473 3.55765C5.99667 3.44189 6.59987 3.5013 7.14805 3.72836C7.69623 3.95543 8.16477 4.33994 8.49441 4.83329C8.82405 5.32664 9 5.90666 9 6.5C9 7.29565 8.68393 8.05871 8.12132 8.62132C7.55871 9.18393 6.79565 9.5 6 9.5ZM6 4.5C5.60444 4.5 5.21776 4.6173 4.88886 4.83706C4.55996 5.05683 4.30362 5.36918 4.15224 5.73463C4.00087 6.10009 3.96126 6.50222 4.03843 6.89018C4.1156 7.27814 4.30608 7.63451 4.58579 7.91422C4.86549 8.19392 5.22186 8.3844 5.60982 8.46157C5.99778 8.53874 6.39992 8.49914 6.76537 8.34776C7.13082 8.19639 7.44318 7.94004 7.66294 7.61114C7.8827 7.28224 8 6.89556 8 6.5C8 5.96957 7.78929 5.46086 7.41421 5.08579C7.03914 4.71072 6.53043 4.5 6 4.5Z"
                                                    fill="#333333" fill-opacity="0.5" />
                                            </svg>
                                            <?= $locations ?>
                                        </span>
                                    </div>
                                </div>
            </div>
            <div class="pf-dtl-body-cnt">
                <div class="job-full-content">
                    <div>
                        <?= get_the_content() ?>
                    </div>
                    <div class="social-media-cnt">
                        <a href="https://twitter.com/propertyfinder?lang=en" target=”_blank”>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M22.4591 6C21.6891 6.35 20.8591 6.58 19.9991 6.69C20.8791 6.16 21.5591 5.32 21.8791 4.31C21.0491 4.81 20.1291 5.16 19.1591 5.36C18.3691 4.5 17.2591 4 15.9991 4C13.6491 4 11.7291 5.92 11.7291 8.29C11.7291 8.63 11.7691 8.96 11.8391 9.27C8.27906 9.09 5.10906 7.38 2.99906 4.79C2.62906 5.42 2.41906 6.16 2.41906 6.94C2.41906 8.43 3.16906 9.75 4.32906 10.5C3.61906 10.5 2.95906 10.3 2.37906 10V10.03C2.37906 12.11 3.85906 13.85 5.81906 14.24C5.1899 14.4129 4.5291 14.4369 3.88906 14.31C4.16067 15.1625 4.6926 15.9084 5.41008 16.4429C6.12756 16.9775 6.99451 17.2737 7.88906 17.29C6.37273 18.4905 4.49307 19.1394 2.55906 19.13C2.21906 19.13 1.87906 19.11 1.53906 19.07C3.43906 20.29 5.69906 21 8.11906 21C15.9991 21 20.3291 14.46 20.3291 8.79C20.3291 8.6 20.3291 8.42 20.3191 8.23C21.1591 7.63 21.8791 6.87 22.4591 6Z"
                                    fill="black" />
                            </svg>
                        </a>

                        <a href="https://www.facebook.com/propertyfinderuae/" target=”_blank”>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M22 12C22 6.48 17.52 2 12 2C6.48 2 2 6.48 2 12C2 16.84 5.44 20.87 10 21.8V15H8V12H10V9.5C10 7.57 11.57 6 13.5 6H16V9H14C13.45 9 13 9.45 13 10V12H16V15H13V21.95C18.05 21.45 22 17.19 22 12Z"
                                    fill="black" />
                            </svg>
                        </a>

                        <a href="https://www.instagram.com/propertyfinder/?hl=en" target="_blank">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M7.8 2H16.2C19.4 2 22 4.6 22 7.8V16.2C22 17.7383 21.3889 19.2135 20.3012 20.3012C19.2135 21.3889 17.7383 22 16.2 22H7.8C4.6 22 2 19.4 2 16.2V7.8C2 6.26174 2.61107 4.78649 3.69878 3.69878C4.78649 2.61107 6.26174 2 7.8 2ZM7.6 4C6.64522 4 5.72955 4.37928 5.05442 5.05442C4.37928 5.72955 4 6.64522 4 7.6V16.4C4 18.39 5.61 20 7.6 20H16.4C17.3548 20 18.2705 19.6207 18.9456 18.9456C19.6207 18.2705 20 17.3548 20 16.4V7.6C20 5.61 18.39 4 16.4 4H7.6ZM17.25 5.5C17.5815 5.5 17.8995 5.6317 18.1339 5.86612C18.3683 6.10054 18.5 6.41848 18.5 6.75C18.5 7.08152 18.3683 7.39946 18.1339 7.63388C17.8995 7.8683 17.5815 8 17.25 8C16.9185 8 16.6005 7.8683 16.3661 7.63388C16.1317 7.39946 16 7.08152 16 6.75C16 6.41848 16.1317 6.10054 16.3661 5.86612C16.6005 5.6317 16.9185 5.5 17.25 5.5ZM12 7C13.3261 7 14.5979 7.52678 15.5355 8.46447C16.4732 9.40215 17 10.6739 17 12C17 13.3261 16.4732 14.5979 15.5355 15.5355C14.5979 16.4732 13.3261 17 12 17C10.6739 17 9.40215 16.4732 8.46447 15.5355C7.52678 14.5979 7 13.3261 7 12C7 10.6739 7.52678 9.40215 8.46447 8.46447C9.40215 7.52678 10.6739 7 12 7ZM12 9C11.2044 9 10.4413 9.31607 9.87868 9.87868C9.31607 10.4413 9 11.2044 9 12C9 12.7956 9.31607 13.5587 9.87868 14.1213C10.4413 14.6839 11.2044 15 12 15C12.7956 15 13.5587 14.6839 14.1213 14.1213C14.6839 13.5587 15 12.7956 15 12C15 11.2044 14.6839 10.4413 14.1213 9.87868C13.5587 9.31607 12.7956 9 12 9Z"
                                    fill="black" />
                            </svg>

                        </a>

                        <a href="https://www.linkedin.com/company/propertyfinder-ae/" target="_blank">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M19 3C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19ZM18.5 18.5V13.2C18.5 12.3354 18.1565 11.5062 17.5452 10.8948C16.9338 10.2835 16.1046 9.94 15.24 9.94C14.39 9.94 13.4 10.46 12.92 11.24V10.13H10.13V18.5H12.92V13.57C12.92 12.8 13.54 12.17 14.31 12.17C14.6813 12.17 15.0374 12.3175 15.2999 12.5801C15.5625 12.8426 15.71 13.1987 15.71 13.57V18.5H18.5ZM6.88 8.56C7.32556 8.56 7.75288 8.383 8.06794 8.06794C8.383 7.75288 8.56 7.32556 8.56 6.88C8.56 5.95 7.81 5.19 6.88 5.19C6.43178 5.19 6.00193 5.36805 5.68499 5.68499C5.36805 6.00193 5.19 6.43178 5.19 6.88C5.19 7.81 5.95 8.56 6.88 8.56ZM8.27 18.5V10.13H5.5V18.5H8.27Z"
                                    fill="black" />
                            </svg>

                        </a>

                        <a href="https://www.glassdoor.com/Overview/Working-at-propertyfinder-EI_IE1176641.11,25.htm"
                            target="_blank">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_1_6742)">
                                    <path
                                        d="M14.1113 0.000590318C14.0937 -0.00113373 14.076 0.000929143 14.0592 0.00664042C14.0425 0.0123517 14.0272 0.0215792 14.0143 0.0337032C14.0014 0.0458272 13.9913 0.0605672 13.9845 0.0769332C13.9778 0.0932993 13.9747 0.110913 13.9753 0.12859V3.57859C13.9753 3.64659 14.0293 3.69859 14.0963 3.70659C16.7163 3.87859 18.7063 4.65659 18.7063 7.38759H13.0873C13.0696 7.38746 13.052 7.39086 13.0355 7.3976C13.0191 7.40433 13.0042 7.41427 12.9916 7.42683C12.979 7.4394 12.9691 7.45433 12.9624 7.47077C12.9556 7.48721 12.9522 7.50482 12.9523 7.52259V16.4866C12.9523 16.5616 13.0123 16.6216 13.0873 16.6216H23.0903C23.1653 16.6216 23.2253 16.5616 23.2253 16.4866V7.34259C23.2253 5.10559 22.4253 3.28659 20.8093 2.01459C19.3213 0.84759 17.0893 0.14359 14.1113 0.00159032M0.911347 7.38859C0.893666 7.38859 0.876159 7.39209 0.859836 7.39889C0.843513 7.40568 0.828696 7.41564 0.81624 7.42819C0.803784 7.44074 0.793934 7.45563 0.787259 7.472C0.780584 7.48838 0.777216 7.50591 0.777347 7.52359V16.4796C0.777347 16.5546 0.837347 16.6146 0.912347 16.6146H6.53135C6.53135 19.3456 4.54135 20.1246 1.92135 20.2956C1.88932 20.2983 1.85944 20.3128 1.83746 20.3362C1.81548 20.3597 1.80297 20.3905 1.80235 20.4226V23.8736C1.80235 23.9486 1.86235 24.0086 1.93735 24.0006C4.91535 23.8586 7.14535 23.1546 8.63435 21.9876C10.2503 20.7156 11.0503 18.8976 11.0503 16.6596V7.52259C11.0505 7.50482 11.0471 7.48721 11.0403 7.47077C11.0336 7.45433 11.0237 7.4394 11.0111 7.42683C10.9985 7.41427 10.9836 7.40433 10.9672 7.3976C10.9507 7.39086 10.9331 7.38746 10.9153 7.38759L0.911347 7.38859Z"
                                        fill="black" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_1_6742">
                                        <rect width="24" height="24" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>

                        </a>

                    </div>
                    <div class="apply-btn">
                        <a href="<?= get_post_meta(get_the_ID(), '_job_url', true) ?>" target="_blank">Apply Now</a>
                    </div>
                </div>
                <div class="job-reaalted-post-list">
                    <h3>Related Roles</h3>
                    <hr>
                    <div class="related-job-list">
                        <?php
                        if ($related_query->have_posts()) {
                            echo '<ul>';
                            while ($related_query->have_posts()) {
                                $related_query->the_post();
                                $jobs_locations = get_the_terms(get_the_ID(), 'jobs_location');
                                ?>
                                <li><a href="<?php the_permalink(); ?>">
                                        <h5><?php the_title(); ?></h5>
                                    </a>
                                    <span><?= $locations ?></span>
                                </li>
                                <hr>
                                <?php
                            }
                            echo '</ul>';
                        } else {
                            echo '<p>No related posts found.</p>';
                        }
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="job-dtl-video">
            <div class="video-dtl-sec">
                <h3>Video</h3>
                <?php echo do_shortcode('[elementor-template id="1119"]'); ?>
            </div>

        </div>
        <div class="quick-links-section">
            <h3>Quick Clicks</h3>
            <div class="quick-links-col">
                <?php
                $job_departments = get_the_terms(get_the_ID(), 'jobs_department');
                if (!empty($job_departments) && !is_wp_error($job_departments)) {
                    $department = $job_departments[0]; // Get the first department
                    $department_name = $department->name;

                    // Check if ACF function exists and field has a value
                    if (function_exists('get_field')) {
                        $quick_clicks_link = get_field('quick_clicks', $department);
                    } else {
                        $quick_clicks_link = null;
                        error_log('ACF plugin is not active or get_field function is not available');
                    }
                } else {
                    $department_name = 'No department found';
                    $quick_clicks_link = null;
                    error_log('No valid department found for post ID: ' . get_the_ID());
                }

                // Only display the block if quick_clicks_link has a value
                if ($quick_clicks_link && is_array($quick_clicks_link) && isset($quick_clicks_link['url'])):
                    $link_url = $quick_clicks_link['url'];
                    $link_target = $quick_clicks_link['target'] ? $quick_clicks_link['target'] : '_self';
                    ?>
                    <div class="ql-block">
                        <p>More about
                            <strong>"Working in <?php echo esc_html($department_name); ?>"</strong>
                        </p>
                        <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                            <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="icon-svg">
                                <circle cx="28" cy="28" r="27.6" stroke="#333333" stroke-width="0.8" class="icon-circle">
                                </circle>
                                <g clip-path="url(#clip0_1_6784)" class="icon-arrow">
                                    <path
                                        d="M32.7928 22.7788H33.4513V32.9856H32.1342V25.0271L23.7101 33.4512L22.7789 32.5201L31.2026 24.096H23.2445V22.7788H32.7928Z"
                                        fill="#333333"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_1_6784">
                                        <rect width="14.2299" height="14.2299" fill="white" transform="translate(21 21)">
                                        </rect>
                                    </clipPath>
                                </defs>
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="ql-block">
                    <p>Why <strong>Property Finder</strong></p>
                    <a href="#" target="_blank">
                        <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="icon-svg">
                            <circle cx="28" cy="28" r="27.6" stroke="#333333" stroke-width="0.8" class="icon-circle">
                            </circle>
                            <g clip-path="url(#clip0_1_6784)" class="icon-arrow">
                                <path
                                    d="M32.7928 22.7788H33.4513V32.9856H32.1342V25.0271L23.7101 33.4512L22.7789 32.5201L31.2026 24.096H23.2445V22.7788H32.7928Z"
                                    fill="#333333"></path>
                            </g>
                            <defs>
                                <clipPath id="clip0_1_6784">
                                    <rect width="14.2299" height="14.2299" fill="white" transform="translate(21 21)">
                                    </rect>
                                </clipPath>
                            </defs>
                        </svg>

                    </a>
                </div>
                <div class="ql-block">
                    <p>How We <strong>Hire</strong></p>
                    <a href="#" target="_blank">
                        <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="icon-svg">
                            <circle cx="28" cy="28" r="27.6" stroke="#333333" stroke-width="0.8" class="icon-circle">
                            </circle>
                            <g clip-path="url(#clip0_1_6784)" class="icon-arrow">
                                <path
                                    d="M32.7928 22.7788H33.4513V32.9856H32.1342V25.0271L23.7101 33.4512L22.7789 32.5201L31.2026 24.096H23.2445V22.7788H32.7928Z"
                                    fill="#333333"></path>
                            </g>
                            <defs>
                                <clipPath id="clip0_1_6784">
                                    <rect width="14.2299" height="14.2299" fill="white" transform="translate(21 21)">
                                    </rect>
                                </clipPath>
                            </defs>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

get_footer();
