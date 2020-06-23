<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package SimpleBlog
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no,user-scalable=no"/>

    <title><?php bloginfo('name'); ?></title>

    <?php wp_head(); ?>

    <?php $themeBase = get_template_directory_uri(); ?>

    <style>
        <?php $colors = simpleblog_get_color_settings(); ?>
        html {
            --text-color: <?php echo $colors['text_color']; ?>;
            --text-color-rgb: <?php echo $colors['text_color_rgb']; ?>;
            --link-color: <?php echo $colors['link_color']; ?>;
            --link-color-hover: <?php echo $colors['link_color_hover']; ?>;
            --page-background: <?php echo $colors['page_bg_color']; ?>;
            --page-background-rgb: <?php echo $colors['page_bg_color_rgb']; ?>;

            --theme-primary-color: <?php echo $colors['primary_color']; ?>;
            --theme-primary-color-hover: <?php echo $colors['primary_color_hover']; ?>;
            --theme-primary-counter-color: <?php echo $colors['primary_counter_color']; ?>;
            --theme-primary-counter-color-rgb: <?php echo $colors['primary_counter_color_rgb']; ?>;
            --theme-secondary-color: <?php echo $colors['secondary_color']; ?>;
            --theme-secondary-color-hover: <?php echo $colors['secondary_color_hover']; ?>;
            --theme-secondary-counter-color: <?php echo $colors['secondary_counter_color']; ?>;
            --theme-secondary-counter-color-rgb: <?php echo $colors['secondary_counter_color_rgb']; ?>;

            --theme-dark-gray-color: rgb(159, 159, 162);
            --theme-dark-gray-color-hover: rgb(128, 128, 131);
            --theme-dark-gray-counter-color: white;
        }
    </style>

    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/theme.css" />

    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/reset.css" />
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/layout.css" />
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/general.css" />

    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/article.css" />
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/button.css" />
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/calendar.css" />
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/cover.css" />
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/footer.css" />
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/hamburger.css" />
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/list.css" />
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/mainpost.css" />
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/nav.css" />
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/offcanvas.css" />
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/post.css" />
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/postcard.css" />
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/social.css" />
    <link rel="stylesheet" href="<?php echo $themeBase; ?>/css/topbar.css" />

    <!-- <link rel="stylesheet" href="vendor/fontawesome-free-5.13.0-web/css/all.css" /> -->
    <script src="https://kit.fontawesome.com/ee86041869.js" crossorigin="anonymous"></script>
</head>

<body <?php body_class(); ?>>

    <?php
    wp_body_open();
    ?>

    <div class="offcanvas">
        <nav class="nav nav-drilldown">
            <ul>
                <li class="leaf"><a href="/">Home</a></li>
                <li class="active node">
                    <a href="#">About</a>
                    <ul>
                        <li class="leaf"><a href="#">Chapter 2.1</a></li>
                        <li class="active leaf"><a href="#">Chapter 2.2</a></li>
                        <li class="node">
                            <a href="#">Chapter 2.3</a>
                            <ul>
                                <li class="leaf"><a href="#">Chapter 2.3.1</a></li>
                                <li class="leaf"><a href="#">Chapter 2.3.2</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- <li class="subheader">More navigation</li> -->
                <li class="node">
                    <a href="#">Solutions</a>
                    <ul>
                        <li class="leaf"><a href="#">Chapter 3.1</a></li>
                        <li class="node">
                            <a href="#">Chapter 3.2</a>
                            <ul>
                                <li class="leaf"><a href="#">Chapter 3.2.1</a></li>
                                <li class="leaf"><a href="#">Chapter 3.2.2</a></li>
                            </ul>
                        </li>
                        <li class="leaf"><a href="#">Chapter 3.3</a></li>
                    </ul>
                </li>
                <li class="leaf"><a href="#">Contact</a></li>
            </ul>
        </nav>
    </div>

    <a class="hamburger" data-offcanvas-trigger><span></span><span></span><span></span></a>

    <div class="primary topbar<?php if (is_singular()) echo ' inverted'; ?>">
        <div class="large container">
            <div class="brandbox"></div>
            <a class="brand" href="/">Simple Blog</a>
            <nav class="nav nav-dropdown">
                <ul>
                    <li class="leaf"><a href="#">Home</a></li>
                    <li class="active node">
                        <a href="#">About</a>
                        <ul>
                            <li class="leaf"><a href="#">Chapter 2.1</a></li>
                            <li class="active leaf"><a href="#">Chapter 2.2</a></li>
                            <li class="node">
                                <a href="#">Chapter 2.3</a>
                                <ul>
                                    <li class="leaf"><a href="#">Chapter 2.3.1</a></li>
                                    <li class="leaf"><a href="#">Chapter 2.3.2</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="subheader">More navigation</li> -->
                    <li class="node">
                        <a href="#">Solutions</a>
                        <ul>
                            <li class="leaf"><a href="#">Chapter 3.1</a></li>
                            <li class="node">
                                <a href="#">Chapter 3.2</a>
                                <ul>
                                    <li class="leaf"><a href="#">Chapter 3.2.1</a></li>
                                    <li class="leaf"><a href="#">Chapter 3.2.2</a></li>
                                </ul>
                            </li>
                            <li class="leaf"><a href="#">Chapter 3.3</a></li>
                        </ul>
                    </li>
                    <li class="leaf"><a href="#">Contact</a></li>
                    <li class="item">
                        <a class="primary social-link" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="primary social-link" href="#"><i class="fab fa-instagram"></i></a>
                    </li>
                    <!-- <li class="leaf"><a href="#"><i class="far fa-search"></i></a></li> -->
                </ul>
            </nav>
        </div>
    </div>
