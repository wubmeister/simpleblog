<?php

require_once "inc/settings.php";

add_theme_support( 'post-thumbnails' );
add_theme_support( 'custom-header' );

function simpleblog_enqueue_comment_reply_script() {
    if ( get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment_reply' );
    }
}
add_action( 'comment_form_before', 'simpleblog_enqueue_comment_reply_script' );

function simpleblog_customize_register( $wp_customize )
{
	$sections = [
		"brand" => [
			"label" => __("Brand"),
			"settings" => [
				"brand_text" => [
					"type" => "text",
					"label" => __("Brand text"),
				],
				"brand_image" => [
					"type" => "media",
					"label" => __("Logo"),
				],
				"brand_image_footer" => [
					"type" => "media",
					"label" => __("Logo for the footer"),
				],
			],
		],
		"socialmedia" => [
			"label" => __("Social media"),
			"settings" => [
				"twitter" => [
					"type" => "text",
					"label" => __("Twitter"),
				],
				"facebook" => [
					"type" => "text",
					"label" => __("Facebook"),
				],
				"instagram" => [
					"type" => "text",
					"label" => __("Instagram"),
				],
				"youtube" => [
					"type" => "text",
					"label" => __("YouTube"),
				],
			],
		],
		"content" => [
			"label" => __("Content"),
			"settings" => [
				"cover_text" => [
					"type" => "text",
					"label" => __("Cover text"),
				],
				"cover_typewriter" => [
					"type" => "textarea",
					"label" => __("Cover typewriter"),
				],
			],
		],
		"colors" => [
			"label" => __("Colors"),
			"settings" => [
				"page_bg_color" => [
					"type" => "color",
					"label" => __("Page background color"),
				],
				"primary_color" => [
					"type" => "color",
					"label" => __("Primary color"),
				],
				"secondary_color" => [
					"type" => "color",
					"label" => __("Secondary color"),
				],
				"text_color" => [
					"type" => "color",
					"label" => __("Text color"),
					"default_value" => "#000000"
				],
				"link_color" => [
					"type" => "color",
					"label" => __("Link color"),
				],
			],
		],
	];

	foreach ($sections as $section_name => $section) {
		if ($section_name != 'colors') {
			$wp_customize->add_section($section_name, [
				'title' => $section['label'],
				'capability' => 'edit_theme_options',
			]);
		}

		foreach ($section['settings'] as $setting_name => $setting) {

			$sn = "simpleblog_{$setting_name}";
			$args = [];
			if ($setting['default_value']) $args['default'] = $setting['default_value'];
			if ($setting['sanitize_callback']) $args['sanitize_callback'] = $setting['sanitize_callback'];
			$wp_customize->add_setting($sn, $args);

			if ($setting['type'] == 'media') {
				$args['mime_type'] = 'image';
				$wp_customize->add_control(
					new WP_Customize_Media_Control(
						$wp_customize,
						$sn,
						[
							'section' => $section_name,
							'label' => $setting['label'],
							'mime_type' => isset($setting['mime_type']) ? $setting['mime_type'] : 'image'
						]
					)
				);
			} else {
				$args = [
					'label' => $setting['label'],
					'section' => $section_name,
					'type' => $setting['type'],
					'settings' => $sn
				];
				if ($setting['options']) $args['options'] = $setting['options'];
				$wp_customize->add_control($sn, $args);
			}
		}
	}
}

add_action( 'customize_register', 'simpleblog_customize_register' );

function simpleblog_register_widgets() {
    register_sidebar([
		'name' => __('Footer 1'),
		'id' => 'footer-1',
		'before_widget' => '',
		'after_widget' => '',
	]);
    register_sidebar([
		'name' => __('Footer 2'),
		'id' => 'footer-2',
		'before_widget' => '',
		'after_widget' => '',
	]);
    register_sidebar([
		'name' => __('Footer 3'),
		'id' => 'footer-3',
		'before_widget' => '',
		'after_widget' => '',
	]);
    register_sidebar([
		'name' => __('Footer 4'),
		'id' => 'footer-4',
		'before_widget' => '',
		'after_widget' => '',
	]);
}

add_action( 'widgets_init', 'simpleblog_register_widgets' );

function simpleblog_get_post_meta( $post_id = null, $location = 'single-top' ) {

	// Require post ID.
	if ( ! $post_id ) {
		return;
	}

	// $disallowed_post_types = apply_filters( 'simpleblog_disallowed_post_types_for_meta_output', array( 'page' ) );
	// Check whether the post type is allowed to output post meta.
	// if ( in_array( get_post_type( $post_id ), $disallowed_post_types, true ) ) {
	// 	return;
	// }

	$post_meta_wrapper_classes = '';
	$post_meta_classes         = '';

	// Get the post meta settings for the location specified.
	if ( 'single-top' === $location ) {
		/**
		* Filters post meta info visibility
		*
		* Use this filter to hide post meta information like Author, Post date, Comments, Is sticky status
		*
		* @since Twenty Twenty 1.0
		*
		* @param array $args {
		*  @type string 'author'
		*  @type string 'post-date'
		*  @type string 'comments'
		*  @type string 'sticky'
		* }
		*/
		$post_meta = apply_filters(
			'simpleblog_post_meta_location_single_top',
			array(
				'author',
				'post-date',
				'comments',
				'sticky',
			)
		);

		$post_meta_wrapper_classes = ' post-meta-single post-meta-single-top';

	} elseif ( 'single-bottom' === $location ) {

		/**
		* Filters post tags visibility
		*
		* Use this filter to hide post tags
		*
		* @since Twenty Twenty 1.0
		*
		* @param array $args {
		*   @type string 'tags'
		* }
		*/
		$post_meta = /*apply_filters(
			'simpleblog_post_meta_location_single_bottom',*/
			array(
				'author',
				'post-date',
				'comments',
				'sticky',
				'tags',
			//)
		);

		$post_meta_wrapper_classes = ' post-meta-single post-meta-single-bottom';

	}

	// If the post meta setting has the value 'empty', it's explicitly empty and the default post meta shouldn't be output.
	if ( $post_meta && ! in_array( 'empty', $post_meta, true ) ) {

		// Make sure we don't output an empty container.
		$has_meta = false;

		global $post;
		$the_post = get_post( $post_id );
		setup_postdata( $the_post );

		ob_start();

		?>

		<div class="post-meta-wrapper<?php echo esc_attr( $post_meta_wrapper_classes ); ?>">

			<ul class="post-meta<?php echo esc_attr( $post_meta_classes ); ?>">

				<?php

				/**
				 * Fires before post meta html display.
				 *
				 * Allow output of additional post meta info to be added by child themes and plugins.
				 *
				 * @since Twenty Twenty 1.0
				 * @since Twenty Twenty 1.1 Added the `$post_meta` and `$location` parameters.
				 *
				 * @param int    $post_id   Post ID.
				 * @param array  $post_meta An array of post meta information.
				 * @param string $location  The location where the meta is shown.
				 *                          Accepts 'single-top' or 'single-bottom'.
				 */
				// do_action( 'simpleblog_start_of_post_meta_list', $post_id, $post_meta, $location );

				// Author.
				if ( post_type_supports( get_post_type( $post_id ), 'author' ) && in_array( 'author', $post_meta, true ) ) {

					$has_meta = true;
					?>
					<li class="post-author meta-wrapper">
						<span class="meta-icon">
							<span class="screen-reader-text"><?php _e( 'Post author', 'simpleblog' ); ?></span>
							<i class="far fa-user"></i>
						</span>
						<span class="meta-text">
							<?php
							printf(
								/* translators: %s: Author name. */
								__( 'By %s', 'simpleblog' ),
								'<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author_meta( 'display_name' ) ) . '</a>'
							);
							?>
						</span>
					</li>
					<?php

				}

				// Post date.
				if ( in_array( 'post-date', $post_meta, true ) ) {

					$has_meta = true;
					?>
					<li class="post-date meta-wrapper">
						<span class="meta-icon">
							<span class="screen-reader-text"><?php _e( 'Post date', 'simpleblog' ); ?></span>
							<i class="far fa-clock"></i>
						</span>
						<span class="meta-text">
							<a href="<?php the_permalink(); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a>
						</span>
					</li>
					<?php

				}

				// Categories.
				if ( in_array( 'categories', $post_meta, true ) && has_category() ) {

					$has_meta = true;
					?>
					<li class="post-categories meta-wrapper">
						<span class="meta-icon">
							<span class="screen-reader-text"><?php _e( 'Categories', 'simpleblog' ); ?></span>
							<i class="far fa-folder"></i>
						</span>
						<span class="meta-text">
							<?php _ex( 'In', 'A string that is output before one or more categories', 'simpleblog' ); ?> <?php the_category( ', ' ); ?>
						</span>
					</li>
					<?php

				}

				// Tags.
				if ( in_array( 'tags', $post_meta, true ) && has_tag() ) {

					$has_meta = true;
					?>
					<li class="post-tags meta-wrapper">
						<span class="meta-icon">
							<span class="screen-reader-text"><?php _e( 'Tags', 'simpleblog' ); ?></span>
							<i class="far fa-tag"></i>
						</span>
						<span class="meta-text">
							<?php the_tags( '', ', ', '' ); ?>
						</span>
					</li>
					<?php

				}

				// Comments link.
				if ( in_array( 'comments', $post_meta, true ) && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {

					$has_meta = true;
					?>
					<li class="post-comment-link meta-wrapper">
						<span class="meta-icon">
							<i class="far fa-comment"></i>
						</span>
						<span class="meta-text">
							<?php comments_popup_link(); ?>
						</span>
					</li>
					<?php

				}

				// Sticky.
				if ( in_array( 'sticky', $post_meta, true ) && is_sticky() ) {

					$has_meta = true;
					?>
					<li class="post-sticky meta-wrapper">
						<span class="meta-icon">
                            <i class="far fa-bookmark"></i>
						</span>
						<span class="meta-text">
							<?php _e( 'Sticky post', 'simpleblog' ); ?>
						</span>
					</li>
					<?php

				}

				/**
				 * Fires after post meta html display.
				 *
				 * Allow output of additional post meta info to be added by child themes and plugins.
				 *
				 * @since Twenty Twenty 1.0
				 * @since Twenty Twenty 1.1 Added the `$post_meta` and `$location` parameters.
				 *
				 * @param int    $post_id   Post ID.
				 * @param array  $post_meta An array of post meta information.
				 * @param string $location  The location where the meta is shown.
				 *                          Accepts 'single-top' or 'single-bottom'.
				 */
				// do_action( 'simpleblog_end_of_post_meta_list', $post_id, $post_meta, $location );

				?>

			</ul><!-- .post-meta -->

		</div><!-- .post-meta-wrapper -->

		<?php

		wp_reset_postdata();

		$meta_output = ob_get_clean();

		// If there is meta to output, return it.
		if ( $has_meta && $meta_output ) {

			return $meta_output;

		}
	}

}

function simpleblog_the_post_meta( $post_id = null, $location = 'single-top' )
{
    echo simpleblog_get_post_meta($post_id, $location);
}

function simpleblog_format_date($date)
{
	if (is_string($date)) {
		$date = strtotime($date);
	}

	return strftime(__("%B %e, %Y"), $date);
}

function simpleblog_render_comments($comments)
{
	$themeBase = get_template_directory_uri();

	foreach ($comments as $comment) {
		echo "<div class=\"comment\" data-comment-id=\"{$comment->comment_ID}\">
				<div class=\"avatar\">
					<img src=\"{$themeBase}/images/default_avatar.svg\"/>
				</div>
				<div class=\"content\">
					<div class=\"author\">{$comment->comment_author}
					<div class=\"meta\">" . simpleblog_format_date($comment->comment_date) . "</div></div>
					<div class=\"body\">
						<p>" . str_replace("\n", '<br/>', $comment->comment_content) . "</p>
					</div>
					<div class=\"actions\"><a href=\"#\" class=\"primary link\" data-place-comment>Reply to {$comment->comment_author}</a></div>";

		$children = $comment->get_children();
		if ($children) {
			echo "<div class=\"replies\">";
			simpleblog_render_comments($children);
			echo "</div>";
		}

		echo "</div></div>";
	}
}

function simpleblog_comments_template()
{
	echo '<div class="comments"><div class="content">';

	echo '<h3>' . __("Comments") . '</h3>';

	$comments = get_comments([ 'hierarchical' => 'threaded' ]);
	simpleblog_render_comments($comments);

	if (comments_open()) {

		echo "<p>
				<a href=\"#\" class=\"small primary button\" data-place-comment>
					<i class=\"far fa-comment\"></i>
					Leave a comment
				</a>
			</p>

			<div id=\"comment-form\" class=\"primary\">
				<a href=\"#\" class=\"primary link\" data-cancel-comment>Cancel</a>";

				comment_form();

		echo "</div>";

	}

	echo "</div></div>";
}
