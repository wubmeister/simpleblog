<?php if (has_post_thumbnail()): ?>
<header class="large primary cover with-image">
    <div class="image opacity-70" style="background-image:url(<?php echo get_the_post_thumbnail_url(); ?>)"></div>
<?php else: ?>
<header class="large primary cover">
<?php endif; ?>
    <div class="large container">
        <div class="caption">
            <h1><?php the_title(); ?></h1>
            <?php simpleblog_the_post_meta( get_the_ID(), 'single-bottom' ); ?>

        </div>
    </div>
</header>

<article class="article">
    <div class="small container">
        <div class="body">

            <?php the_content(); ?>

        </div>
    </div>
</article>
