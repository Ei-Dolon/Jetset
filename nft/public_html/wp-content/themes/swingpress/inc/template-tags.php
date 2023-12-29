<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

if ( ! function_exists( 'swingpress_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function swingpress_posted_on( $id = '' ) {
		if ( false === swingpress_archive_meta_option( 'hide_date' ) ) {
			return;
		}

		$id = ! empty( $id ) ? $id : get_the_id();

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U', $id ) !== get_the_modified_time( 'U', $id ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			get_the_date( DATE_W3C, $id ),
			get_the_date( '', $id ),
			get_the_modified_date( DATE_W3C, $id ),
			get_the_modified_date( '', $id )
		);

		$year = get_the_date( 'Y' );
		$month = get_the_date( 'm' );

		// Wrap the time string in a link, and preface it with 'Posted on'.
		printf(
			/* translators: %s: post date */
			__( '<span class="posted-on"><span class="screen-reader-text">Posted on</span> %s', 'swingpress' ),
			'<a href="' . esc_url( get_month_link( $year, $month ) ) . '" rel="bookmark">' . $time_string . '</a></span>'
		);
	}
endif;

if ( ! function_exists( 'swingpress_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the tags and comments.
	 */
	function swingpress_entry_footer() {
		$options = swingpress_get_theme_options();

		if ( ! $options['single_post_hide_category'] ) :
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'swingpress' ) );
			if ( $categories_list && swingpress_categorized_blog() ) {
				printf( '<span class="post-categories"><span class="cat-links">' . esc_html__( 'Categories: ', 'swingpress' ) . '%1$s' . '</span></span>', $categories_list ); // WPCS: XSS OK.
			}
		endif;

		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			if ( ! $options['single_post_hide_tags'] ) :
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list();
				if ( $tags_list ) {
					printf( '<span class="tags-links">%1$s</span>', $tags_list ); // WPCS: XSS OK.
				}
			endif;
		}

		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'swingpress' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

/**
 * articles meta
 *  @param [id] $id post id
 *  @param [html] $authro author template
 */
function swingpress_author_meta( $id = '' ) { 
	$id = ! empty( $id ) ? $id : get_the_id();
	$options = swingpress_get_theme_options();

	if ( ! swingpress_archive_meta_option( 'hide_author' ) ) {
		return;
	}

	if ( 'post' !== get_post_type( $id ) ) { 
		return;
	}
	$output = '';
	
	$author = sprintf(
		/* translators: %s: post author */
		__( 'By: %s', 'swingpress' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a></span>'
	);
    $output .= '<span class="byline">' . $author . '</span><!-- .byline -->'; 

    return $output;
}


/**
 * articles meta
 *  @param [id] $id post id
 *  @param [html] $authro author template
 */
function swingpress_article_header_meta( $id = '' ) { 
	$id = ! empty( $id ) ? $id : get_the_id();

	if ( 'post' !== get_post_type( $id ) ) { 
		return;
	}
	$output = '';
	
	if ( true === swingpress_archive_meta_option( 'hide_category' ) ) {
	    $categories_list = get_the_category_list( '', '', $id );
		if ( $categories_list && swingpress_categorized_blog() ) {
			$output .= '<span class="cat-links">' . $categories_list . '</span>'; // WPCS: XSS OK.
		}
	}

    return $output;
}


/**
 * Checks to see if meta option is hide enabled in archive/blog
 */
function swingpress_archive_meta_option( $option = '' ) {
	$options = swingpress_get_theme_options();
	if ( is_archive() || is_search() || is_home() ) :
		if ( true === $options[$option] )
			return false;
		else
			return true;
	else :
		return true;
	endif;
}

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function swingpress_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'swingpress_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'swingpress_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so swingpress_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so swingpress_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in swingpress_categorized_blog.
 */
function swingpress_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'swingpress_categories' );
}
add_action( 'edit_category', 'swingpress_category_transient_flusher' );
add_action( 'save_post',     'swingpress_category_transient_flusher' );
