<?php /* Template Name: Job Search */
get_header();

$jobs_locations = get_terms(array(
    'taxonomy' => 'jobs_location',
    'hide_empty' => false,
));

$jobs_departments = get_terms(array(
    'taxonomy' => 'jobs_department',
    'hide_empty' => false,
));

$job_args = array(
    'post_type' => 'jobs',
    'posts_per_page' => -1,
    'post_status' => 'publish'
);

$jobs_posts_query = new WP_Query($job_args);
?>
<div id="page-loader">
    <div class="loader-content">
        <img src="/wp-content/uploads/2024/10/1_kE6TDV_fn41AMbNJQTjC9A.gif" alt="Loading...">
    </div>
</div>
<div class="career-ui">
    <div class="search-job-filter">
        <div class="sj-title">
            <h1>Search Job</h1>
        </div>
        <div class="sj-input-similar">
            <form class="sj-input-form">
                <input type="text" placeholder="Web Designer" id="job_departments" />
                <input type="hidden" id="department_slug">
                <select id="jobs_locations">
                    <option>Select Location</option>
                    <?php
                    if (!empty($jobs_locations) && !is_wp_error($jobs_locations)) {
                        foreach ($jobs_locations as $jobs_location) {
                            ?>
                            <option value="<?= $jobs_location->slug ?>"><?= $jobs_location->name ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                <button type="button" class="search-btn">Search</button>
            </form>
            <div class="sj-similar-jobs" style="display: none;">
                <span class="similar-title">Similar: </span>
                <div class="sj-similar-chips">
                    <?php
                    if (!empty($jobs_departments) && !is_wp_error($jobs_departments)) {
                        foreach ($jobs_departments as $jobs_department) {
                            ?>
                            <span class="sj-chip"
                                data-department="<?= $jobs_department->slug ?>"><?= $jobs_department->name ?></span>
                            <?php
                        }
                    } else {
                        echo 'No terms found.';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="sj-search-result" style="display: none;">
            <div class="sj-results">
                Showing <span id="post-counts">5</span> Results for <span id="post-filter-department">Web
                    Designer</span>
            </div>
            <div class="sj-sorting">
                <select class="sj-sorting-select">
                    <option value="Most Recent">Most Recent</option>
                    <option value="Most Related">Most Related</option>
                </select>
            </div>
        </div>
    </div>
    <div class="job-filter-main">
        <div class="job-cards-filter">
            <div class="joblist-fc">
                <div class="location-filter">
                    <h2>Location</h2>
                    <div class="location-list">
                        <!-- <div class="location-checkbox">
                            <div class="checkbox-wrapper-46">
                                <input type="checkbox" id="cbx-all" class="inp-cbx" />
                                <label for="cbx-all" class="cbx"><span>
                                        <svg viewBox="0 0 12 10" height="10px" width="12px">
                                            <polyline
                                                points="1.5 6 4.5 9 10.5 1"></polyline>
                                        </svg></span><span>All</span>
                                </label>
                            </div>
                            <span class="Location-count"><?php echo wp_count_posts('jobs')->publish; ?></span>
                        </div> -->
                        <?php
                        if (!empty($jobs_locations) && !is_wp_error($jobs_locations)) {
                            foreach ($jobs_locations as $jobs_location) {
                                ?>
                                <div class="location-checkbox">
                                    <div class="checkbox-wrapper-46">
                                        <input type="checkbox" id="cbx-<?= $jobs_location->slug ?>" class="inp-cbx" />
                                        <label for="cbx-<?= $jobs_location->slug ?>" class="cbx"><span>
                                                <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                    <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                </svg></span><span><?= $jobs_location->name ?></span>
                                        </label>
                                    </div>
                                    <span class="Location-count"><?= $jobs_location->count ?></span>
                                </div>
                                <?php
                            }
                        } else {
                            echo 'No terms found.';
                        }
                        ?>
                    </div>
                    <div class="clear-location">
                        <span class="clear-select-btn">Clear Location Selection</span>
                    </div>
                </div>
            </div>
            <div class="job-list-cards">
                <?php
                if ($jobs_posts_query->have_posts()) {
                    // Loop through the posts
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
                            <div class="job-card-body">
                                <?= wp_trim_words(get_the_content(), 60, '...') ?>
                            </div>
                            <div class="job-card-footer">
                                <a href="<?= get_permalink() ?>" class="ftr-btn">
                                    Read More
                                    <svg width="12" height="11" viewBox="0 0 12 11" fill="none"
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

                    // Reset post data after the loop
                    wp_reset_postdata();
                } else {
                    // No posts found
                    echo 'No Jobs found.';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php

get_footer();
