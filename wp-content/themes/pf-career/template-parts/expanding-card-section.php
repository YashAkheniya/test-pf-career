<?php
/**
 * Template part for displaying the expanding card section
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<section class="expand-card">
    <div class="cards">
        <?php for ($i = 1; $i <= 5; $i++) : ?>
            <div class="card" <?php echo ($i === 1) ? 'active' : ''; ?> <?php if(get_field("expanding_image{$i}")): ?> style="background: url('<?php echo get_field("expanding_image{$i}")['url']; ?>') no-repeat center top / cover;"<?php endif; ?>>
                <?php if(get_field("tab_title{$i}")): ?>
                    <div class="card__infos <?php echo empty(get_field("tab_description{$i}")) ? 'active-desc' : 'inactive-desc'; ?>">
                        <h3 class="card__name deactive"><?php echo get_field("tab_title{$i}"); ?></h3>
                        <h3 class="card__name_active"><?php echo get_field("active_title_{$i}"); ?></h3>
                        <?php if(get_field("tab_description{$i}")): ?>
                            <div class="card__desc"><?php echo get_field("tab_description{$i}"); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="sliders-color" style="content: ''; position: absolute; top: 0; left: 0; width:100%; height: 100%; z-index: 0; background: url('<?php echo get_field("inactive_tab_image{$i}")['url']; ?>') no-repeat center center / cover;"></div>
                <?php if ($i === 1) : ?>
                    <button class="arrowNextslide" id="showNextSlide"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/right_arrow_white.svg" alt=""></button>
                <?php endif; ?>
            </div>
        <?php endfor; ?>
    </div>

    <div class="progress-controls">
        <div class="progress-bar" id="progress-bar"></div>
        <div class="controls">
            <button class="arrow" id="prevBtn"> <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/left-arrow.svg" alt=""> </button>
            <button class="arrow" id="nextBtn"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/right-arrow.svg" alt=""></button>
        </div>
    </div>
</section>

<style>
            :root {
                --section-width: 70.5rem; /* 1128px */
                --active-card-width: 41.5rem; /* 664px */
                --inactive-card-width: 6.625rem; /* 106px */
                --card-gap: 0.625rem; /* 10px */
                --cards-text-color: white;
                --animation-speed-normal: .5s;
                --animation-speed-fast: .25s;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: sans-serif;
            }

            .expand-card {
                width: var(--section-width);
                height: 33.125rem; /* 530px */
                margin: 0 auto;
            }

            .expand-card .cards {
                display: flex;
                gap: var(--card-gap);
                width: 100%;
            }

            .expand-card .card {
                width: var(--inactive-card-width);
                height: 33.125rem; /* 530px */
                flex: 0 0 var(--inactive-card-width);
                overflow: hidden;
                position: relative;
                z-index: 1;
                transition: all var(--animation-speed-normal) ease-in-out;
                cursor: pointer;
            }

            .expand-card .card[active] {
                width: var(--active-card-width);
                flex: 0 0 var(--active-card-width);
            }

            .expand-card .card__image {
                object-fit: cover;
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                z-index: 1;
                filter: brightness(.675) saturate(75%);
                transition: filter var(--animation-speed-fast) ease-in-out;
            }

            .expand-card .card:hover .card__image {
                filter: brightness(.875) saturate(100%);
            }

            .expand-card .card__infos {
                position: absolute;
                bottom: 0;
                left: 0;
                z-index: 2;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                width: 100%;
            }

            .expand-card .card__name {
                margin: 0;
                color: var(--cards-text-color);
                transform: translateY(.65rem);
                transition: all var(--animation-speed-normal) ease-in-out;
                font-family: Roboto Flex;
                font-size: 2rem; /* 32px */
                font-weight: 200;
                line-height: 1.2;
                text-align: left;
                white-space: nowrap;
                transform: rotate(-90deg) translate(3rem, 0rem);
            }

            .expand-card .card[active] .card__name {
                transform: translateY(0);
            }

            .expand-card .card__desc,
            .expand-card .card__name_active {
                color: var(--cards-text-color);
                display: none;
                opacity: 0;
                transition: opacity 0.25s ease;
            }

            .expand-card .card[active] .card__desc,
            .expand-card .card[active] .card__name_active {
                display: block;
            }

            .expand-card .card[active] .card__desc.fade-in,
            .expand-card .card[active] .card__name_active.fade-in {
                opacity: 1;
            }

            .expand-card .card__name.deactive {
                transition: opacity 0.25s ease;
            }

            .expand-card .card[active] .card__name.deactive {
                opacity: 0;
            }

            .expand-card .card[active] .card__infos {
                display: flex;
            }

            .expand-card .card[active] .inactive-desc,
            .expand-card .card[active] .active-desc {
                padding-left: 30px;  
                padding-bottom: 30px;  
            }

            .expand-card .card[active] .card__infos .card__desc {
                font-family: Roboto Flex;
                font-size: 1rem; /* 16px */
                line-height: 1.5; /* 24px */
                font-weight: 400;
                color: #fff;
            }
            .expand-card .card__desc p{
               margin: 0 !important;
            }

            .expand-card .cards [active] h3.card__name br {
                display: block;
            }

            .expand-card .cards [active] h3 {
                font-family: Roboto Flex;
                font-size: 2.3125rem; /* 37px */
                font-weight: 300;
                line-height: 3rem; /* 48px */
                text-align: left;
            }

            .card[active] .sliders-color {
                display: none;
            }

            .expand-card .controls {
                display: flex;
                justify-content: center;
                gap: 2rem; /* 32px */
            }

            .expand-card .arrow {
                background-color: transparent;
                border: none;
                cursor: pointer;
                padding: 0;
            }

            .expand-card .arrow:hover,
            .expand-card .arrow:focus,
            .expand-card .arrow:active {
                background-color: transparent !important;
            }

            .expand-card .progress-bar {
                width: 100%;
                height: 0.25rem; /* 4px */
                background: #000000;
                display: flex;
            }

            .expand-card .progress-segment {
                flex-grow: 1;
                background: #808080;
                transition: background 0.6s linear;
            }

            .expand-card .progress-segment.active {
                background: #EA3934;
                border-radius: 0.625rem; /* 10px */
                height: 0.25rem; /* 4px */
            }

            .expand-card .progress-controls {
                display: flex;
                align-items: center;
                margin-top: 3.9375rem; /* 63px */
                gap: 11.3125rem; /* 181px */
            }

            .expand-card .progress-controls .arrow img {
                width: 2.5rem; /* 40px */
            }

            .expand-card #prevBtn img {
                transform: rotate(180deg);
            }

            #showNextSlide {
                opacity: 0;
            }

            .card:first-child[active] #showNextSlide {
                opacity: 1;
                position: absolute;
                bottom: 2.125rem; /* 34px */
                right: 3.375rem; /* 54px */
                border: none;
                padding: 0;
                z-index: 99;
            }

            .card:first-child[active] #showNextSlide:hover,
            .card:first-child[active] #showNextSlide:focus,
            .card:first-child[active] #showNextSlide:active,
            .card:first-child[active] #showNextSlide:visited {
                background: none !important;
            }

            .card:first-child[active]::after {
                content: "";
                position: absolute;
                top: 0;
                right: 0;
                width: 20%;
                height: 100%;
                background-image: linear-gradient(270deg, rgba(196, 196, 196, 0.94) 58%, rgba(0, 0, 0, 0) 100%);
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .card:first-child[active]:hover::after {
                opacity: 0.4;
            }

            .expand-card .cards .card:first-child[active] .sliders-color {
                background: unset !important;    
            }

            
            .progress-controls {
                display: none !important;
            }
            @media screen and (max-width: 71.25rem) { /* 1140px */
                .expand-card {
                    width: 95%;
                }
            }

            @media screen and (max-width: 48rem) { /* 768px */
                .expand-card .card {
                    width: 100%;
                    flex: 1;
                }

                .expand-card .card[active] {
                    width: 100%;
                    flex: 1;
                }

                .expand-card .card__name {
                    transform: none;
                    white-space: normal;
                }

                .expand-card .progress-controls {
                    flex-direction: column;
                    gap: 1rem;
                    display: none !important;
                }
            }
        </style>

        <script>
                const cards = document.querySelectorAll('.card');
                const progressBar = document.getElementById('progress-bar');
                let activeIndex = 0;

                const updateProgressBar = () => {
                    progressBar.innerHTML = '';
                    cards.forEach((_, index) => {
                        const segment = document.createElement('div');
                        segment.className = 'progress-segment';
                        if (index === activeIndex) {
                            segment.classList.add('active');
                        }
                        segment.addEventListener('click', () => {
                            if (activeIndex !== index) {
                                cards.forEach((c) => c.removeAttribute('active'));
                                cards[index].setAttribute('active', '');
                                activeIndex = index;
                                updateProgressBar();
                            }
                        });
                        progressBar.appendChild(segment);
                    });
                };

                const updateActiveCard = (index) => {
                    cards.forEach((card, idx) => {
                        if (idx === index) {
                            card.setAttribute('active', '');
                            setTimeout(() => {
                                const desc = card.querySelector('.card__desc');
                                const activeName = card.querySelector('.card__name_active');
                                if (desc) desc.classList.add('fade-in');
                                if (activeName) activeName.classList.add('fade-in');
                            }, 400);
                        } else {
                            card.removeAttribute('active');
                            const desc = card.querySelector('.card__desc');
                            const activeName = card.querySelector('.card__name_active');
                            if (desc) desc.classList.remove('fade-in');
                            if (activeName) activeName.classList.remove('fade-in');
                        }
                    });
                    updateProgressBar();
                };

                const nextCard = () => {
                    activeIndex = (activeIndex + 1) % cards.length;
                    updateActiveCard(activeIndex);
                };

                const prevCard = () => {
                    activeIndex = (activeIndex - 1 + cards.length) % cards.length;
                    updateActiveCard(activeIndex);
                };

                document.getElementById('nextBtn').addEventListener('click', nextCard);
                document.getElementById('prevBtn').addEventListener('click', prevCard);

                cards.forEach((card, index) => {
                    card.addEventListener('mouseenter', () => {
                        if (activeIndex !== index) {
                            cards.forEach((c) => {
                                c.removeAttribute('active');
                                const desc = c.querySelector('.card__desc');
                                const activeName = c.querySelector('.card__name_active');
                                if (desc) desc.classList.remove('fade-in');
                                if (activeName) activeName.classList.remove('fade-in');
                            });
                            card.setAttribute('active', '');
                            setTimeout(() => {
                                const desc = card.querySelector('.card__desc');
                                const activeName = card.querySelector('.card__name_active');
                                if (desc) desc.classList.add('fade-in');
                                if (activeName) activeName.classList.add('fade-in');
                            }, 400);

                            activeIndex = index;
                            updateProgressBar();
                        }
                    });

                    card.addEventListener('click', () => {
                        if (activeIndex !== index) {
                            cards.forEach((c) => c.removeAttribute('active'));
                            card.setAttribute('active', '');
                            activeIndex = index;
                            updateProgressBar();
                        }
                    });

                    const nextSlideButton = card.querySelector('.arrowNextslide');
                    if (nextSlideButton) {
                        nextSlideButton.addEventListener('click', (event) => {
                            event.stopPropagation();
                            nextCard();
                        });
                    }
                });

                const expandCardSection = document.querySelector('.expand-card');
                expandCardSection.addEventListener('mouseleave', () => {
                    activeIndex = 0;
                    updateActiveCard(activeIndex);
                });

                updateActiveCard(activeIndex);

        </script>