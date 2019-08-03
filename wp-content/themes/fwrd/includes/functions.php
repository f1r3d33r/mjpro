<?php

function iron_is_login_page() {
	return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

function iron_breadcrumbs($id = 'breadcrumbs', $class = 'breadcrumbs')
{
	global $post_type;

	echo '<nav id="' . esc_attr($id) . '" class="' . esc_attr($class) . '"><ul>';

	if ( is_paged() || is_singular() || is_author() || is_tax() || is_category() || is_tag() || is_date() ) {
		echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'fwrd') . '</a></li>';
	}

	if ( ( is_paged() || is_singular() || is_tax() || is_category() || is_tag() || is_date() ) && ( ! is_page() || ! is_attachment() ) )
	{
		global $iron_post_types;

		# Post Type
		if ( empty($post_type) )
			$post_type = get_post_type();

		$url = '';

		if ( in_array($post_type, $iron_post_types) )
			$archive_page = get_iron_option('page_for_' . $post_type . 's');
		else
			$archive_page = get_option('page_for_' . $post_type . 's');

		if ( $archive_page > 0 )
		{
			$url = post_permalink( $archive_page );
			if ( ! empty($url) )
				echo '<li><a href="' . esc_url($url) . '">' . get_the_title( $archive_page ) . '</a></li>';
		}
	}

	if ( is_archive() )
	{
		# Term
		if ( is_tax() )
		{
			$taxonomy = get_query_var('taxonomy');
			$term = get_term_by( 'slug', get_query_var('term'), $taxonomy );

		} elseif ( is_category() ) {
			$taxonomy = 'category';
			$term = get_category( get_query_var('cat') );

		} elseif ( is_tag() ) {
			$taxonomy = 'post_tag';
			$term = get_term_by( 'slug', get_query_var('tag'), $taxonomy );
		}

		if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
			echo '<li><a href="' . esc_url(get_term_link( $term->slug, $taxonomy )) . '">' . esc_attr($term->name) . '</a></li>';
		}

		# A Date/Time Page
		if ( is_day() ) {

			echo '<li>' . sprintf( esc_html__('Archive for %s', 'fwrd'), get_the_date() ) . '</li>';

		} elseif ( is_month() ) {

			echo '<li>' . sprintf( esc_html__('Archive for %s', 'fwrd'), get_the_date( esc_html_x('F Y', 'monthly archives date format', 'fwrd') ) ) . '</li>';

		} elseif ( is_year() ) {

			echo '<li>' . sprintf( esc_html__('Archive for %s', 'fwrd'), get_the_date( esc_html_x('Y', 'yearly archives date format', 'fwrd') ) ) . '</li>';

		} elseif ( is_author() ) {

			$author = get_userdata( get_query_var('author') );
			echo '<li>' . sprintf( esc_html__('Archive for %s', 'fwrd'), $author->display_name ) . '</li>';

		}

	} elseif ( is_search() ) {

		echo '<li>' . esc_html__('Results', 'fwrd') . '</li>';

	}

	// Index Pagination
	if ( is_paged() ) {

		echo '<li>' . sprintf( esc_html__('Page %s', 'fwrd'), get_query_var('paged') ) . '</li>';

	}




	# A Single Page, Single Post or Attachment
	if ( is_singular() ) {

		echo '<li>' . get_the_title() . '</li>';

		// Post Pagination
		$paged = get_query_var('page');

		if ( is_single() && $paged > 1 ) {

			echo '<li>' . sprintf( esc_html__('Page %s', 'fwrd'), $paged ) . '</li>';

		}

	}

	echo '</ul></nav>';
}


function iron_get_posts($post_type = 'post', $paged = 1, $per_page = null, $term = null, $tax = null, $ajax = false) {

	$args = array(
		'post_type' => $post_type,
		'paged' => $paged
	);

	$args['ajax'] = $ajax;

	if ( $per_page )
		$args['posts_per_page'] = $per_page;

	if ( term_exists($term) && taxonomy_exists($tax) )
	{
		switch ( $tax ) {
			case 'category':
				$args['cat'] = $term;
				break;

			case 'tag':
				$args['tag'] = $term;
				break;

			default:
				$args['tax_query'] = array(
					array(
						'taxonomy' => $tax,
						'field'    => 'id',
						'terms'    => $term,
						'operator' => 'IN'
					)

				);
				break;
		}
	}

	$query = new WP_Query( $args );

	return $query;
}

function iron_show_categories($taxonomy) {

	include(locate_template('parts/categories.php'));
}


/**
 * Trashes custom settings related to Theme Options
 *
 * When the post and page is permanently deleted, everything that is tied to it is deleted also.
 * This includes theme settings.
 *
 * @see wp_delete_post()
 *
 * @param int $post_id Post ID.
 */
function iron_delete_post ( $post_id ) {
	global $wpdb;

	if ( $post = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE ID = %d", $post_id)) )
	{
		if ( 'page' == $post->post_type) {
			// if the page is defined in option page_on_front or post_for_posts,
			// adjust the corresponding options
			if ( get_iron_option('page_for_albums') == $post_id ) {
				reset_iron_option('page_for_albums');
			}
			if ( get_iron_option('page_for_events') == $post_id ) {
				reset_iron_option('page_for_events');
			}
			if ( get_iron_option('page_for_videos') == $post_id ) {
				reset_iron_option('page_for_videos');
			}
			if ( get_iron_option('page_for_photos') == $post_id ) {
				reset_iron_option('page_for_photos');
			}
		}
	}
}

add_action('delete_post', 'iron_delete_post');


/**
 * Save custom settings related to Theme Options
 *
 * When the post and page is updated, everything that is tied to it is saved also.
 * This includes theme settings.
 *
 * @see wp_update_post()
 *
 * @param int $post_id Post ID.
 */
function iron_save_post ( $post_id ) {
	global $wpdb;

	if ( $post = $wpdb->get_row($wpdb->prepare("SELECT p.*, pm.meta_value AS page_template FROM $wpdb->posts AS p INNER JOIN $wpdb->postmeta AS pm ON p.ID = pm.post_id WHERE p.ID = %d AND pm.meta_key = '_wp_page_template'", $post_id)) )
	{
		if ( 'page' == $post->post_type)
		{
			switch ( $post->page_template ) {
				case 'front-page.php':
				case 'page-front.php':
				case 'page-home.php':
					update_option('show_on_front', 'page');
					update_option('page_on_front', absint($post_id));
					break;

				case 'home.php':
				case 'index.php':
				case 'page-blog.php':
				case 'archive-post.php':
					update_option('show_on_front', 'page');
					update_option('page_for_posts', absint($post_id));
					break;

				case 'archive-album.php':
					set_iron_option('page_for_albums', absint($post_id));
					break;

				case 'archive-event.php':
					set_iron_option('page_for_events', absint($post_id));
					break;

				case 'archive-video.php':
					set_iron_option('page_for_videos', absint($post_id));
					break;

				case 'archive-photo.php':
					set_iron_option('page_for_photos', absint($post_id));
					break;

				default:

					if($post->post_name == 'home') {
						update_option('show_on_front', 'page');
						update_option('page_on_front', absint($post_id));
					}else{
					
						if ( get_iron_option('page_for_albums') == $post_id ) {
							reset_iron_option('page_for_albums');
						}
						if ( get_iron_option('page_for_events') == $post_id ) {
							reset_iron_option('page_for_events');
						}
						if ( get_iron_option('page_for_videos') == $post_id ) {
							reset_iron_option('page_for_videos');
						}
						if ( get_iron_option('page_for_photos') == $post_id ) {
							reset_iron_option('page_for_photos');
						}
	
						if ( get_option('page_on_front') === 0 && get_option('page_for_posts') === 0 ) {
							update_option('show_on_front', 'posts');
						}
					}
					break;
			}
		}
	}
}

add_action( 'save_post', 'iron_save_post' );



/**
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */

function iron_wp_title ( $title, $sep, $seplocation ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	if ( is_post_type_archive() )
	{
		$post_type_obj = get_queried_object();
		
		$title = $post_type_obj->labels->name;
		
		$prefix = '';
		if ( !empty($title) )
			$prefix = " $sep ";

		$t_sep = '%WP_TITILE_SEP%'; // Temporary separator, for accurate flipping, if necessary

		// Determines position of the separator and direction of the breadcrumb
		if ( 'right' == $seplocation ) { // sep on right, so reverse the order
			$title_array = explode( $t_sep, $title );
			$title_array = array_reverse( $title_array );
			$title = implode( " $sep ", $title_array ) . $prefix;
		} else {
			$title_array = explode( $t_sep, $title );
			$title = $prefix . implode( " $sep ", $title_array );
		}
	}


	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( esc_html__('Page %s', 'fwrd'), max($paged, $page) );

	return $title;
}

add_filter('wp_title', 'iron_wp_title', 5, 3);

/**
 * Display or retrieve the archive title with optional content.
 *
 * @see the_title()
 *
 * @param string $before Optional. Content to prepend to the title.
 * @param string $after Optional. Content to append to the title.
 * @param bool $echo Optional, default to true.Whether to display or return.
 * @return null|string Null on no title. String if $echo parameter is false.
 */

function iron_the_archive_title ( $post_type = '', $before = '', $after = '', $echo = true ) {
	$title = iron_get_the_archive_title( $post_type );

	if ( strlen($title) == 0 )
		return;

	$title = $before . $title . $after;

	if ( $echo )
		echo esc_html($title);
	else
		return esc_html($title);
}

function iron_get_the_archive_title ( $post_type = '' ) {

	if ( is_tax() )
	{
		$taxonomy = get_query_var('taxonomy');
		$term = get_term_by( 'slug', get_query_var('term'), $taxonomy );

		$title = $term->name;

	} else {

		if ( empty($post_type) )
			$post_type = get_post_type();

		$page_for_archive = get_iron_option('page_for_' . $post_type . 's');
		$post_type_object = get_post_type_object( $post_type );

		if ( ! empty($page_for_archive) )
			$title = get_the_title( $page_for_archive );
		else if ( ! empty($post_type_object->label) )
			$title = $post_type_object->label;
		else
			$title = esc_html__('Archives', 'fwrd');
	}

	return apply_filters( 'the_title', $title );
}


/**
 * Append archive template to stack of taxonomy templates.
 *
 * If no taxonomy templates can be located, WordPress
 * falls back to archive.php, though it should try
 * archive-{$post_type}.php before.
 *
 * @see get_taxonomy_template(), get_archive_template()
 */
function iron_archive_template_include ( $templates ) {
	$term = get_queried_object();
	$post_types = array_filter( (array) get_query_var( 'post_type' ) );

	if ( empty($post_types) ) {
		$taxonomy = get_taxonomy( $term->taxonomy );

		$post_types = $taxonomy->object_type;

		$templates = array();

		if ( count( $post_types ) == 1 ) {
			$post_type = reset( $post_types );
			$templates[] = "archive-{$post_type}.php";
		}
	}

	return locate_template( $templates );
}

add_filter('taxonomy_template', 'iron_archive_template_include');


function iron_full_pagination () {
	global $wp_query, $wp_rewrite;
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

	$pagination = array(
		'base' => add_query_arg('paged','%#%'),
		'format' => '',
		'total' => $wp_query->max_num_pages,
		'current' => $current,
		'show_all' => false,
		'type' => 'list',
		'next_text' => '&raquo;',
		'prev_text' => '&laquo;'
		
		);



	if( !empty($wp_query->query_vars['s']) )
		$pagination['add_args'] = array( 's' => get_query_var( 's' ) );

	if(isset($_GET['artist-id']) && !empty($_GET['artist-id'])) {
		$pagination['add_args'] = array('artist-id' => $_GET['artist-id']);
	}

	echo paginate_links( $pagination );
}



/**
 * Get the page assigned to be the content type archive.
 *
 * @since 1.5.0
 * @todo Swap `get_pages()` with `WP_Query` directly to
 *       use `meta_query` to query an array of template names.
 */

function iron_get_page_for_post_type ( $post_type = '', $page_template = '' ) {

	if ( $post_type )
	{
		$page_id = get_iron_option('page_for_' . $post_type . 's');

		if ( empty($page_id) && ! empty($page_template) ) {
			$page = get_pages(array(
				'meta_key' => '_wp_page_template',
				'meta_value' => $page_template,
				'number' => 1
			));

			if ( ! empty($page) )
				$page_id = $page[0]->ID;
		}

		if ( $page_id )
			return $page_id;
	}

	return 0;
}



function iron_redirect_album_url () {

	if ( 'album' == get_post_type() && is_single() ) {
		global $post;

		$url = get_post_meta($post->ID, 'alb_link_external', true);
		$url = esc_url($url);

		if ( ! empty($url) ) {
			wp_redirect($url, 302);
			exit;
		}
	}
}

add_action('template_redirect', 'iron_redirect_album_url');



/**
 * Display the time at which the event was written.
 *
 * @since 1.5.0
 * @see the_time()
 *
 * @param string $d Either 'G', 'U', or php date format.
 */

function iron_the_event_date ( $d = '' ) {
	$post = get_post();

	$show_time = get_field('event_show_time', $post->ID);

	echo '<time class="datetime" datetime="' . apply_filters( 'the_event_timestamp', ( $show_time ? get_the_time('c') : get_the_time('Y-m-d\TZ') ), $d ) . '">' . apply_filters( 'the_event_date', iron_get_the_event_date( $d ), $d ) . '</time>';
}



/**
 * Retrieve the time at which the event was written.
 *
 * @since 1.5.0
 * @see get_the_time()
 *
 * @param string $d Optional Either 'G', 'U', or php date format defaults to the value specified in the time_format option.
 * @param int|object $post Optional post ID or object. Default is global $post object.
 * @return string
 */

function iron_get_the_event_date ( $d = '', $post = null ) {
	$post = get_post($post);

	$field = get_field_object('field_523b46ebe35ef', $post->ID, array( 'load_value' => false ));
	$value = get_post_meta($post->ID, 'event_show_time', true);

	if ( '' == $value )
		$show_time = $field['default_value'];
	else if ( 0 == $value )
		$show_time = false;
	else if ( 1 == $value )
		$show_time = true;

	if ( '' == $d )
		$the_time = '<span class="date">' . date_i18n( get_option('date_format'), get_post_time()) . '</span>' . ( $show_time ? ' <span class="time">' . date_i18n(get_option('time_format'), get_post_time()) . '</span>' : '' );
	else
		$the_time = get_post_time( $d, false, $post, true );

	return apply_filters('get_the_event_date', $the_time, $d, $post);
}



/**
 * Setup Dynamic Sidebar
 */

function iron_setup_dynamic_sidebar ( $page_id )
{

	$has_sidebar = false;
	$sidebar_area = false;
	$sidebar_position = get_field('sidebar-position', $page_id);

	if ( 'disabled' === $sidebar_position )
		$sidebar_position = false;

	if ( $sidebar_position )
	{
		$sidebar_area = get_field('sidebar-area_id', $page_id, false);
		$has_sidebar = is_active_sidebar( $sidebar_area );

	}
	
	return array(
		  $has_sidebar
		, $sidebar_position
		, $sidebar_area
	);
}


/**
 * Add Theme Options to WordPress Toolbar
 */

function iron_admin_bar ( $wp_toolbar )
{
	global $redux_args;

	$wp_toolbar->add_node( array(
		  'parent' => 'appearance'
		, 'id'     => 'iron-options'
		, 'title'  => $redux_args['menu_title']
		, 'href'   => admin_url('/admin.php?page=' . $redux_args['page_slug'])
	) );
}

add_action('admin_bar_menu', 'iron_admin_bar', 999);

/**
 * Outputs a notice that a subject is deprecated.
 *
 * There is a hook `iron_deprecated` that will be called that can be used
 * to get the backtrace up to what file and function called the deprecated
 * function.
 *
 * This function is to be used in every field that is deprecated.
 *
 * @since 1.6.0
 *
 * @param string $subject The function that was called
 * @param string $version The version of WordPress that deprecated the function
 * @param string $replacement Optional. The function that should have been called
 */

function iron_deprecated_notice( $subject, $version, $replacement = null ) {

	do_action( 'iron_deprecated', $subject, $replacement, $version );

	if ( ! is_null( $replacement ) )
		return wp_kses(sprintf( __('%1$s is <strong>deprecated</strong> since version %2$s! Use %3$s instead.', 'fwrd'), $subject, $version, $replacement ),iron_get_allowed_html());
	else
		return wp_kses(sprintf( __('%1$s is <strong>deprecated</strong> since version %2$s with no alternative available.', 'fwrd'), $subject, $version ),iron_get_allowed_html());
}





/**
 *  Modify excerpts length
 *
 */

function iron_custom_excerpt_length( $length ) {
	return 80;
}
add_filter( 'excerpt_length', 'iron_custom_excerpt_length', 999 );


// Hex 2 RGB values
function iron_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   return implode(",", $rgb); // returns the rgb values separated by commas
}

	
//setting a random id
if(!function_exists('iron_random_id')) { 
	function iron_random_id($id_length) {
	$random_id_length = $id_length; 
	$rnd_id = crypt(uniqid(rand(),1)); 
	$rnd_id = strip_tags(stripslashes($rnd_id)); 
	$rnd_id = str_replace(".","",$rnd_id); 
	$rnd_id = strrev(str_replace("/","",$rnd_id)); 
	$rnd_id = str_replace(range(0,9),"",$rnd_id); 
	$rnd_id = substr($rnd_id,0,$random_id_length); 
	$rnd_id = strtolower($rnd_id);  

	return $rnd_id;
	}
}


function iron_get_fonts() {

	$fonts = array();
	
	// Safe Fonts
	$fonts["safefont"]["Arial, Helvetica, sans-serif"] = "Arial, Helvetica, sans-serif";
	$fonts["safefont"]["Arial Black, Gadget, sans-serif"] = "Arial Black, Gadget, sans-serif";
	$fonts["safefont"]["Bookman Old Style, serif"] = "Bookman Old Style, serif";
	$fonts["safefont"]["Comic Sans MS, cursive"] = "Comic Sans MS, cursive";
	$fonts["safefont"]["Courier, monospace"] = "Courier, monospace";
	$fonts["safefont"]["Courier New, Courier, monospace"] = "Courier New, Courier, monospace";
	$fonts["safefont"]["Garamond, serif"] = "Garamond, serif";
	$fonts["safefont"]["Georgia, serif"] = "Georgia, serif";
	$fonts["safefont"]["Impact, Charcoal, sans-serif"] = "Impact, Charcoal, sans-serif";
	$fonts["safefont"]["Lucida Console, Monaco, monospace"] = "Lucida Console, Monaco, monospace";
	$fonts["safefont"]["Lucida Grande, Lucida Sans Unicode, sans-serif"] = "Lucida Grande, Lucida Sans Unicode, sans-serif";
	$fonts["safefont"]["MS Sans Serif, Geneva, sans-serif"] = "MS Sans Serif, Geneva, sans-serif";
	$fonts["safefont"]["MS Serif, New York, sans-serif"] = "MS Serif, New York, sans-serif";
	$fonts["safefont"]["Palatino Linotype, Book Antiqua, Palatino, serif"] = "Palatino Linotype, Book Antiqua, Palatino, serif";
	$fonts["safefont"]["Tahoma, Geneva, sans-serif"] = "Tahoma, Geneva, sans-serif";
	$fonts["safefont"]["Times New Roman, Times, serif"] = "Times New Roman, Times, serif";
	$fonts["safefont"]["Trebuchet MS, Helvetica, sans-serif"] = "Trebuchet MS, Helvetica, sans-serif";
	$fonts["safefont"]["Verdana, Geneva, sans-serif"] = "Verdana, Geneva, sans-serif";
	
	
	// Google Fonts	
	$fonts["google"]["Source+Sans+Pro:300,400,600,700,900"] = "Source Sans Pro:300,400,600,700,900";
	$fonts["google"]["Droid+Sans:400,700"] = "Droid Sans:400,700";
	$fonts["google"]["Lato:300,400,700,900"] = "Lato:300,400,700,900";
	$fonts["google"]["Arvo:400,700"] = "Arvo:400,700";
	$fonts["google"]["Cabin:400,500,600,700"] = "Cabin:400,500,600,700";
	$fonts["google"]["Lora:400,700"] = "Lora:400,700";
	$fonts["google"]["PT+Sans:400,700"] = "PT Sans:400,700";
	$fonts["google"]["Lobster"] = "Lobster";
	$fonts["google"]["Josefin+Slab:300,400,600,700"] = "Josefin Slab:300,400,600,700";
	$fonts["google"]["Droid+Serif:400,700"] = "Droid Serif:400,700";
	$fonts["google"]["Bree+Serif"] = "Bree Serif";
	$fonts["google"]["Lobster+Two:400,700"] = "Lobster Two:400,700";
	$fonts["google"]["Open+Sans:400,300,600,700,800"] = "Open Sans:400,300,600,700,800";
	$fonts["google"]["Oswald:400,700,300"] = "Oswald:400,700,300";
	$fonts["google"]["Patua+One"] = "Patua One";
	$fonts["google"]["Poly"] = "Poly";
	$fonts["google"]["Varela+Round"] = "Varela Round";
	
	return $fonts;
	
}	


function iron_get_fontfamily( $element_name, $id, $font_family, $font_type, $child_el = null ) {
    $output = '';
    if ( $font_type == 'google' ) {
        if ( !function_exists( "my_strstr" ) ) {
            function my_strstr( $haystack, $needle, $before_needle = false ) {
                if ( !$before_needle ) return strstr( $haystack, $needle );
                else return substr( $haystack, 0, strpos( $haystack, $needle ) );
            }
        }
        wp_enqueue_style( $font_family, '//fonts.googleapis.com/css?family=' .$font_family , false, false, 'all' );
        $format_name = strpos( $font_family, ':' );
        if ( $format_name !== false ) {
            $google_font =  my_strstr( str_replace( '+', ' ', $font_family ), ':', true );
        } else {
            $google_font = str_replace( '+', ' ', $font_family );
        }
        $output .= '<style type="text/css">'.$element_name.$id.' '.$child_el.'{font-family: "'.$google_font.'"}</style>';

    } else if ( $font_type == 'safefont' ) {
            $output .= '<style type="text/css">'.$element_name.$id.' '.$child_el.'{font-family: '.$font_family.' !important}</style>';
        }

    return $output;
}


function iron_page_title_divider() {
	$divider_image = get_iron_option('page_title_divider_image');
	$divider_color = get_iron_option('page_title_divider_color');
	$divider_margin_top = get_iron_option('page_title_divider_margin_top');
	$divider_margin_bottom = get_iron_option('page_title_divider_margin_bottom');
	if(empty($divider_image)){
		echo '<span class="heading-b3" style="margin-top:'.esc_attr($divider_margin_top).'px; margin-bottom:'.esc_attr($divider_margin_bottom).'px; background-color:'.esc_attr($divider_color).'"></span>';
	} else {
		echo '<img class="custom-header-img" style="margin-top:'.esc_attr($divider_margin_top).'px; margin-bottom:'.esc_attr($divider_margin_bottom).'px;" src="'.esc_url($divider_image).'" />';
	}
}

function iron_is_page_title_uppercase() {
		$page_title_uppercase = (bool)get_iron_option('page_title_uppercase');
		if(!empty($page_title_uppercase)){
			return true;
		}
		return false;
	}


function icl_post_languages(){
	
	if(!function_exists('icl_get_languages')) 
		return false;
		
	  $languages = icl_get_languages('skip_missing=1');
	  if(1 < count($languages)){
		foreach($languages as $l){
		  if(!$l['active']) $langs[] = '<li><a href="'.esc_url($l['url']).'">'.$l['native_name'].'</a></li>';
		}
		echo join(', ', $langs);
	  }
}


function iron_timezone_offset_string()
{
	$offset = get_option('gmt_offset');
	return $offset;
	
}



function get_iron_part($type) {
	if($type == 'event')
		iron_music_get_template_part('event');
	elseif($type == 'album')
		iron_music_get_template_part('album');
	else
		get_template_part('items/' . $type);
}



if ( ! function_exists('iron_get_allowed_html') )
{

	function iron_get_allowed_html() {
		return array(
		    'a' => array(
		        'href' => array(),
		        'title' => array()
		    ),
		    'br' => array(),
		    'em' => array(),
		    'strong' => array(),
		    'p' => array(
		    	'style' => array()
		    ),
		    'font' => array(),
		    'b' => array(),
		    'span' => array(),

		);
	}

}

function iron_footer(){
	$footer_back_to_top_enabled = get_iron_option('footer_back_to_top_enabled');
	$footer_back_to_top_text = get_iron_option('footer_back_to_top_text');
	
	if($footer_back_to_top_enabled){
	echo '<a href="#" id="back-to-top-mobile" class="footer-wrapper-backtotop-mobile">
		<i class="fa fa-chevron-up"></i>
	</a>
	<a href="#" id="back-to-top" class="footer-wrapper-backtotop">
		<i class="fa fa-chevron-up"></i>
	</a>';
	}
}

add_action( 'wp_footer', 'iron_footer', 20 );