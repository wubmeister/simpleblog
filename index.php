<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package SimpleBlog
 */

get_header(); ?>

        <?php if (is_home() && is_front_page()): ?>

            <header class="primary  cover align-left" style="background-image: url(<?php echo get_template_directory_uri(); ?>/image.php?width=1500&height=500&color=f4e04d);">
                <div class="large container">
                    <div class="caption">
                        <h1><?php echo get_bloginfo('name'); ?></h1>
                        <p class="subtitle"><?php echo get_bloginfo('description'); ?></p>
                    </div>
                </div>
            </header>

        <?php endif; ?>

        <?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>

			<?php endif; ?>

            <?php
            /* Start the Loop */
            $index = 0;
            ?>
            <?php if (!is_singular()): ?>
            <section class="mainposts">
                <div class="large container">
            <?php endif; ?>
                <?php while ( have_posts() ) : the_post(); ?>

                    <?php if ($index == 2): ?>
                </div>
            </section>
            <section class="postcards">
                <div class="large container">
                    <?php endif; ?>


                    <?php

                        /*
                        * Include the Post-Format-specific template for the content.
                        * If you want to override this in a child theme, then include a file
                        * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                        */
                        if (is_singular()) get_template_part( 'template-parts/content', get_post_format() );
                        else if ($index == 0) get_template_part( 'template-parts/mainpost', get_post_format() );
                        else if ($index == 1) get_template_part( 'template-parts/mainpost-rev', get_post_format() );
                        else if ($index >= 2 && $index <= 4) get_template_part( 'template-parts/postcard', get_post_format() );
                        $index++;
                    ?>

                <?php endwhile; ?>
            <?php if (!is_singular()): ?>
                </div>
            </section>
            <?php endif; ?>

			<?php the_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

<?php get_footer(); ?>
