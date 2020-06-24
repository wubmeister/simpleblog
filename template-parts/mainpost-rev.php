<div class="secondarysoft mainpost">
    <?php if (has_post_thumbnail()): ?>
    <div class="image" style="background-image:url(<?php echo get_the_post_thumbnail_url(); ?>)"></div>
    <?php else: ?>
    <div class="image" style="background-image:url(<?php echo get_template_directory_uri(); ?>/image.php?width=500&height=320&color=<?php echo simpleblog_get_color('primary_color', true); ?>)"></div>
    <?php endif; ?>
    <div class="content">
        <?php
        if ( is_singular() ) {
			the_title( '<h2>', '</h2>' );
		} else {
			the_title( '<h2><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
        }
        ?>
        <?php the_excerpt(); ?>
        <?php simpleblog_the_post_meta( get_the_ID(), 'single-bottom' ); ?>
        <p><a href="<?php echo get_permalink(); ?>" class="link">Lees meer</a></p>
    </div>
</div>
