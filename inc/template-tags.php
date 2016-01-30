<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Mine
 */

if ( ! function_exists( 'mine_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function mine_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( '%s', 'post date', 'mine' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'mine' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'mine_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function mine_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'mine' ) );
		if ( $categories_list && mine_categorized_blog() ) {
			printf( '<span class="cat-links"><span class="glyphicon glyphicon-folder-open"></span>'. esc_html__('%1$s', 'mine') . '</span>', $categories_list ); // WPCS: XSS OK. esc_html__( 'Posted in %1$s', 'mine' )
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'mine' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links"><span class="glyphicon glyphicon-tags"></span>' . esc_html__('%1$s', 'mine') . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( '<span class="fa fa-comment"></span>', '<span class="fa fa-comment"></span>' . esc_html__( '1', 'mine' ) , '<span class="fa fa-comments"></span>' . esc_html__( '%', 'mine' ) );
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( '%s', 'mine' ),
			the_title( '<span class="screen-reader-text">"', '"</span><span class="glyphicon glyphicon-edit"></span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function mine_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'mine_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'mine_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so mine_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so mine_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in mine_categorized_blog.
 */
function mine_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'mine_categories' );
}
add_action( 'edit_category', 'mine_category_transient_flusher' );
add_action( 'save_post',     'mine_category_transient_flusher' );


/*****************
* comment
******************/

if ( ! function_exists( 'bpl_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function bpl_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'media' ); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'mine' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'mine' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body media">


			<div class="media-body">
				<div class="media-body-wrap ">


					<div class="panel-heading">

							<a class="pull-left" href="#">
								<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
							</a>


							<h5 class="media-heading"><?php printf( __( '%s', 'mine' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?></h5>
							<div class="comment-meta">
								<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
									<time datetime="<?php comment_time( 'c' ); ?>">
										<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'mine' ), get_comment_date(), get_comment_time() ); ?>
									</time>
								</a>
								<?php edit_comment_link( '<span style="margin-left: 5px;" class="glyphicon glyphicon-edit"></span>'. __( ' Edit', 'mine' ), '<span class="edit-link">', '</span>' ); ?>
							</div>

					</div>

					<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'mine' ); ?></p>
					<?php endif; ?>

					<div class="comment-content panel-body"><!--panel-body-->
						<?php comment_text(); ?>
					</div><!-- .comment-content -->

					<?php comment_reply_link(
						array_merge(
							$args, array(
								'add_below' => 'div-comment',
								'depth' 	=> $depth,
								'max_depth' => $args['max_depth'],
								'before' 	=> '<footer class="reply comment-reply panel-footer">',
								'after' 	=> '</footer><!-- .reply -->'
							)
						)
					); ?>

				</div>
			</div><!-- .media-body -->

		</article><!-- .comment-body -->

	<?php
	endif;
}
endif; // ends check for bpl_comment()
