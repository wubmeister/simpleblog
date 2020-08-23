<?php if (has_post_thumbnail()): ?>
<header class="large primary cover with-image">
    <?php
    $imageCaption = get_the_post_thumbnail_caption();
    ?>
    <div class="image opacity-70" style="background-image:url(<?php echo get_the_post_thumbnail_url(null, 'large'); ?>)"></div>
<?php else: ?>
<header class="large primary cover">
<?php endif; ?>
    <div class="large container">
        <div class="caption">
            <h1><?php the_title(); ?></h1>
            <?php simpleblog_the_post_meta( get_the_ID(), 'single-bottom' ); ?>
            <?php if ($imageCaption) { ?>
                <div class="post-meta">
                    <div class="meta-wrapper">
                        <span class="meta-icon">
							<span class="screen-reader-text">Photo author</span>
							<i class="fas fa-camera-retro" aria-hidden="true"></i>
						</span>
                        <span class="meta-text"><?php echo $imageCaption; ?></span>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</header>

<article class="article">
    <div class="small container">
        <div class="article-body">

            <?php the_content(); ?>

        </div>

            <?php if ( comments_open() || get_comments_number() ) :
                    // comments_template();
                    simpleblog_comments_template();

			endif; ?>
    </div>
</article>
