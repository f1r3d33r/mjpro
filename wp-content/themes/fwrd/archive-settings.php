<?php
global $post_type, $item_template, $post, $wp_query, $enable_excerpts, $events_filter, $isocol, $link_mode, $show_post_date, $show_post_author, $show_post_tags, $show_post_categories;


if ( $item_template === null )
	$item_template = $post_type;

$paged = ( get_query_var('paged') ? get_query_var('paged') : 1 );

if ( empty( $post_type ) )
	$post_type = get_post_type();



$post_type_object = get_post_type_object( $post_type );

if ( empty($page_for_archive) ) {
	$page_for_archive = ( 'post' == $post_type ? get_option('page_for_posts') : get_iron_option('page_for_' . $post_type . 's') );
	$page_for_archive = get_post( $page_for_archive );
}

$attr = array(
	'data-type="' . esc_attr( $post_type ) . '"',
	'data-page="' . esc_attr( $paged ) . '"'
);

$archive_content = "";
$hide_page_title = false;
$enable_excerpts = false;
$link_mode;
$events_filter = false;

if ( is_day() ){
	$archive_title = sprintf( esc_html__('Daily Archives: %s', 'fwrd'), get_the_date() );
} elseif ( is_month() ) {
	$archive_title = sprintf( esc_html__('Monthly Archives: %s', 'fwrd'), get_the_date( esc_html_x('F Y', 'monthly archives date format', 'fwrd') ) );
} elseif ( is_year() ) {
	$archive_title = sprintf( esc_html__('Yearly Archives: %s', 'fwrd'), get_the_date( esc_html_x('Y', 'yearly archives date format', 'fwrd') ) );
} elseif(is_search() || !empty($_GET["s"])) {
	$archive_title = sprintf( esc_html__('Search Results', 'fwrd'));
} elseif ( is_home() ) {
	
	$item_template = 'post_classic';
	$archive_title = false;
	$archive_content = false;
	if($wp_query->queried_object){
		$enable_excerpts = get_field('enable_excerpts', $wp_query->queried_object->ID);
	}
} elseif ( is_archive() ) {
	$archive_title = false;
	$archive_content = false;

} elseif ( ! empty($post_type_object->label) ) {

	if ( isset($wp_query->queried_object) && isset($wp_query->queried_object->ID) ) {
	
		$archive_title = get_the_title( $wp_query->queried_object->ID );
		$archive_content = apply_filters('the_content', $wp_query->queried_object->post_content);
		$hide_page_title = get_field('hide_page_title', $wp_query->queried_object->ID);
		
		if($post_type == 'post') {
			$enable_excerpts = get_field('enable_excerpts', $wp_query->queried_object->ID);
		}else if($post_type == 'event') {
			$events_filter = get_field('events_filter', $wp_query->queried_object->ID);
		}else if($post_type == 'video') {
			$link_mode = get_field('video_link_type', $wp_query->queried_object->ID); 
		}
		
	}else{
	
		$archive_title = get_the_title( $post->ID );
		$archive_content = apply_filters('the_content', $post->post_content);
		$hide_page_title = get_field('hide_page_title', $post->ID);
		
		if($post_type == 'post') {
			$enable_excerpts = get_field('enable_excerpts', $post->ID);
		}else if($post_type == 'event') {
			$events_filter = get_field('events_filter', $post->ID);
		}else if($post_type == 'video') {
			$link_mode = get_field('video_link_type', $post->ID); 
		}
	}	

};


// Prevent extra database query by enabling permalink structure
if ( $post_type !== get_post_type())
{
	if( empty($_GET["s"]) ) {
		$page_for_archive = $post;
	
		query_posts( array(
			'post_type' => $post_type,
			'paged' => $paged
		) );
	
	}
}


if ( is_tax() )
{
	$taxonomy = get_query_var('taxonomy');
	$term = get_term_by( 'slug', get_query_var('term'), $taxonomy );
	$archive_content = "";

} elseif ( is_category() ) {
	$taxonomy = 'category';
	$term = get_category( get_query_var('cat') );
	$archive_content = "";
	$enable_excerpts = true;
	
} elseif ( is_tag() ) {
	$taxonomy = 'post_tag';
	$term = get_term_by( 'slug', get_query_var('tag'), $taxonomy );
	$archive_content = "";
}

if ( ! empty($term) && ! is_wp_error( $term ) )
{
	$archive_title = $term->name;

	$attr[] = 'data-taxonomy="' . $taxonomy . '"';
	$attr[] = 'data-term="' . $term->term_id . '"';
}

$paginate_method = get_iron_option('paginate_method');
$attr[] = 'data-paginate="' . esc_attr($paginate_method) . '"';
$attr[] = 'data-template="' . esc_attr($item_template) . '"';
$attr[] = 'data-excerpts="' . esc_attr($enable_excerpts) . '"';
$attr[] = 'data-postdate="' . esc_attr($show_post_date) . '"';
$attr[] = 'data-eventsfilter="' . esc_attr($events_filter) . '"';
$attr[] = 'data-isocol="' . esc_attr($isocol) . '"';

if ( $paginate_method == 'paginate_scroll' )
	$paginate_method = "paginate_more";



/**
 * Setup Dynamic Sidebar
 */
if ($page_for_archive) {
	list( $has_sidebar, $sidebar_position, $sidebar_area ) = iron_setup_dynamic_sidebar( $page_for_archive->ID );
}



/**
 * Setup Post Type Structure
 */

		
switch ( $post_type ) {
	case 'post':
		$tag = 'div';
		$show_post_date = (bool)get_iron_option('show_post_date');
		$show_post_author = (bool)get_iron_option('show_post_author');
		$show_post_tags = (bool)get_iron_option('show_post_tags');
		$show_post_categories = (bool)get_iron_option('show_post_categories');

		
		if(empty($item_template))
			$item_template = 'post_grid';

		if($item_template == 'post_grid')
			$class = 'articles-section';
		else if($item_template == 'post_isotope')
			$class = 'isotope-wrap';
		else if($item_template == 'post_classic')
			$class = 'articles-classic';
		else
			$class = 'listing-section news';
		
		$attr[] = 'data-callback="initGridDisplayNews,initIsotope"';
		$caption = esc_html__('Older News','fwrd');
		$next = esc_html__('Older News','fwrd');
		$prev = esc_html__('Recent News','fwrd');
		break;

	case 'event':
		if(empty($archive_title)) { $archive_title = $post_type_object->labels->name; }
		$tag = 'ul';
		$class = 'concerts-list';
		$attr[] = 'data-active="' . ( empty($_GET['id']) ? '' : $_GET['id'] ) . '"';
		$attr[] = 'data-callback="initEventCenter,initCountdownCenter,initDisableTimers"';
		$caption = esc_html__('More Events','fwrd');
		$next = esc_html__('Next Events','fwrd');
		$prev = esc_html__('Previous Events','fwrd');

		break;

	case 'album':
		if(empty($archive_title)) { $archive_title = $post_type_object->labels->name; }
		$tag = 'div';
		$class = 'two_column_album';
		$attr[] = 'data-callback="initGridDisplayAlbum,initHeadsetCenter"';
		$caption = esc_html__('More Albums','fwrd');
		$next = esc_html__('Next Albums','fwrd');
		$prev = esc_html__('Previous Albums','fwrd');

		break;

	case 'video':
		$tag = 'div';
		$class = 'listing-section videos';
		$attr[] = 'data-callback="initGridDisplayVideo,initVideoLinks"';
		$caption = esc_html__('More Videos','fwrd');
		$next = esc_html__('Next Videos','fwrd');
		$prev = esc_html__('Previous Videos','fwrd');

		break;
		
	case 'portfolio':
		$tag = 'div';
		$class = 'isotope-wrap';
		$attr[] = 'data-callback="initGridDisplayNews,initIsotope"';
		$caption = esc_html__('More Projects','fwrd');
		$next = esc_html__('Next Projects','fwrd');
		$prev = esc_html__('Previous Projects','fwrd');

		break;

	default:
		$class = 'post-listing';
		$attr[] = 'data-callback="initGridDisplayNews"';
		$caption = esc_html__('Older Posts','fwrd');
		$next = esc_html__('Next Posts','fwrd');
		$prev = esc_html__('Previous Posts','fwrd');
		break;
}
	
$wp_query->query_vars["is_archive"] = true;
		

if ( empty($archive_title) )
	$hide_page_title = true;

		

$attr[] = 'data-caption="' . $caption . '"';
