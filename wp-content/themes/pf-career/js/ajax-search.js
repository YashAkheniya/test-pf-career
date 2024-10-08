jQuery(document).ready(function($) {
    var $liveSearchInput = $('#live-search-input');
    var $liveSearchResults = $('#live-search-results-new1');
    var homeUrl = ajax_object.url;
    var prevSearchQuery = '';
    var searchCache = {};
    var searchTimeout;

    $('#search-icon').click(function(e) {
        e.preventDefault();
        search();
    });

    $liveSearchInput.keypress(function(event) {
        if (event.which === 13) {
            event.preventDefault();
            $('#search-form').submit(); 
        }
    });

    function search() {
        var searchQuery = $liveSearchInput.val().trim();
        performSearch(searchQuery);
    }

    $liveSearchInput.on('input', function() {
        var searchQuery = $(this).val().trim();
        if (searchQuery !== '') {
            $liveSearchResults.addClass('search-results-visible');
            $liveSearchResults.html('<div class="loading">Searching...</div>');
            
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                performSearch(searchQuery);
            }, 300); // Debounce for 300ms
        } else {
            $liveSearchResults.removeClass('search-results-visible');
            $liveSearchResults.html('');
        }
    });

    $(document).on('click', function(event) {
        if (!$(event.target).closest($liveSearchInput, $liveSearchResults).length) {
            $liveSearchResults.removeClass('search-results-visible');
            $liveSearchResults.html('');
            prevSearchQuery = '';
        }
    });

    $liveSearchInput.focus(function() {
        if (prevSearchQuery !== '') {
            $liveSearchResults.addClass('search-results-visible');
            performSearch(prevSearchQuery);
        }
    });

    $liveSearchInput.blur(function() {
        if ($(this).val().trim() === '' && $liveSearchResults.html().trim() === '') {
            $liveSearchResults.removeClass('search-results-visible');
        }
    });

    function performSearch(searchQuery) {
        if (searchCache[searchQuery]) {
            displayResults(searchCache[searchQuery], searchQuery);
            return;
        }

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                'action': 'custom_live_search',
                'search_query': searchQuery
            },
            success: function(response) {
                searchCache[searchQuery] = response;
                displayResults(response, searchQuery);
            },
            error: function(xhr, status, error) {
                console.error('AJAX request error:', error);
            }
        });
    }

    function displayResults(response, searchQuery) {
        $liveSearchResults.html(response);

        var numResults = $liveSearchResults.find('a').length;

        $liveSearchResults.find('a:gt(4)').hide();
        $liveSearchResults.find('br:gt(4)').hide();

        if (numResults === 0) {
            $liveSearchResults.html('<div class="no-results">No result found</div>');
        } else if (numResults > 4) {
            $('#view-all').remove();
            var searchURL = homeUrl + '/?s=' + encodeURIComponent(searchQuery);
            $('#search-icon').attr('href', searchURL);

            // $liveSearchResults.append('<a id="view-all" href="' + homeUrl + '/?s=' + encodeURIComponent(searchQuery) + '">View All</a>');
        } else {
            $('#view-all').remove();
        }

        $liveSearchResults.find('.loading').remove();
    }

    $('#job_departments').on('input', function () {
        job_departments_text = $(this).val();
        if (job_departments_text != "") {
            $(".sj-chip").show();
            $(".sj-chip").each(function () {
                if ($(this).text().toLowerCase().includes(job_departments_text.toLowerCase())) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            })
            $(".sj-similar-jobs").show();
        } else { 
            $(".sj-similar-jobs").hide();
        }
    });

    $(".sj-chip").click(function () { 
        $('#job_departments').val($(this).text());
        $('#department_slug').val($(this).attr('data-department'));
        $(".sj-similar-jobs").hide();
    })

    $("#job_departments").change(function () { 
        if ($(this).val() == "") { 
            $("#department_slug").val("");
        }
    })

    $(".search-btn").click(function () { 
        if ($('#jobs_locations').val() != "Select Location") {
            $('.inp-cbx').each(function () {
                $(this).prop('checked', false);
            });
            var jobs_locations = $('#jobs_locations').val();
            $("#cbx-" + jobs_locations).prop('checked', true);
        }
        
        filter_job_serach();
    })

    $(".clear-select-btn").click(function () { 
        $('.inp-cbx').each(function () {
            $(this).prop('checked', false);
        });
        filter_job_serach();
    })

    $('.inp-cbx').click(function () { 
        filter_job_serach();
    })

    $("#jobs_locations").change(function () { 
        $('.inp-cbx').each(function () {
            $(this).prop('checked', false);
        });
        $("#cbx-" + $(this).val()).prop('checked', true);
    })

    function filter_job_serach() { 
        $("#page-loader").css("display", "flex");
        // var job_departments = $('#department_slug').val();
        var job_departments =  $('#job_departments').val();
        var jobs_locations = "";
        var checkedCheckboxes = $('.location-list input[type="checkbox"]:checked');

        checkedCheckboxes.each(function () {
            var checkboxId = $(this).attr('id');
            jobs_locations += checkboxId.replace("cbx-", "") +",";
        });
        if (job_departments != "") {
            get_current_count();
        }
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                'action': 'job_listing_filter',
                'job_departments': job_departments,
                'jobs_locations': jobs_locations,
            },
            success: function (response) {
                $(".job-list-cards").html(response);
                $("#post-counts").text($(".job-card").length);
                $("#post-filter-department").text($('#job_departments').val());
                $(".sj-search-result").show();
                jQuery("#page-loader").css("display", "none");
                // $('html, body').animate({
                //     scrollTop: $(".job-list-cards").offset().top - 100
                // }, 500);
            },
            error: function (xhr, status, error) {
                console.error('AJAX request error:', error);
            }
        });
    }

    function get_current_count() { 
        var job_departments = $('#job_departments').val();
        var jobs_locations = "";
        var checkedCheckboxes = $('.location-list input[type="checkbox"]:checked');

        checkedCheckboxes.each(function () {
            var checkboxId = $(this).attr('id');
            jobs_locations += checkboxId.replace("cbx-", "") + ",";
        });

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                'action': 'job_listing_location_count',
                'job_departments': job_departments,
            },
            success: function (response) {
                $(".location-checkbox").find(".Location-count").text(0);
                $.each(response.data, function (location, count) {
                    // console.log(location + ": " + count + " jobs available.");
                    $("#cbx-" + location).closest(".location-checkbox").find(".Location-count").text(count);
                });
            },
            error: function (xhr, status, error) {
                console.error('AJAX request error:', error);
            }
        });
    }

    function getUrlParameter(param) {
        var urlParams = new URLSearchParams(window.location.search);
        return urlParams.has(param) ? urlParams.get(param) : false; 
    }

    var custom_serarch = getUrlParameter('custom-serarch');
    if (custom_serarch && $("#job_departments").length > 0) {
        $("#job_departments").val(custom_serarch);
        $(".search-btn").click();
    }

    $(document).on("click", ".save-button",function () { 
        const jobId = $(this).attr('data-job-id');
        let savedJobs = JSON.parse(localStorage.getItem('savedJobs')) || [];

        if (savedJobs.includes(jobId)) {
            savedJobs = savedJobs.filter(id => id !== jobId);
            $(this).removeClass('saved');
        } else {
            savedJobs.push(jobId);
            $(this).addClass('saved');
        }
        localStorage.setItem('savedJobs', JSON.stringify(savedJobs));
    })

    $(".delete-button").click(function () { 
        const jobId = $(this).attr('data-job-id');
        let savedJobs = JSON.parse(localStorage.getItem('savedJobs')) || [];
        if (savedJobs.length === 1) {
            $(".no-job-records-found").show();
        }
        if (savedJobs.includes(jobId)) {
            savedJobs = savedJobs.filter(id => id !== jobId);
        } 
        localStorage.setItem('savedJobs', JSON.stringify(savedJobs));

        $(this).closest(".job-card").remove();
    })

    if ($(".save-jobs").length > 0){ 
        let savedJobs = JSON.parse(localStorage.getItem('savedJobs')) || [];
        if (savedJobs.length === 0) {
            $('.job-card').hide();
            // return; 
            $(".no-job-records-found").show();
        }

        $('.job-card').each(function () {
            let jobId = $(this).attr('data-job-id');
            if (savedJobs.includes(jobId)) {
                $(this).show();
            } else {
                $(this).remove();
            }
        });

        $(".job-list-cards").show();
    }

});
document.addEventListener('DOMContentLoaded', () => {
    const savedJobs = JSON.parse(localStorage.getItem('savedJobs')) || [];

    document.querySelectorAll('.save-button').forEach(button => {
        const jobId = button.getAttribute('data-job-id');
        if (savedJobs.includes(jobId)) {
            button.classList.add('saved');
        }
    });
});