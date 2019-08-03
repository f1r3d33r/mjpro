<?php


add_action( 'init', 'post_type_init' );
add_action( 'init', 'setup_future_hook' );
add_filter( 'post_updated_messages', 'events_updated_messages' );
add_filter( 'manage_edit-event_sortable_columns', 'iron_music_manage_event_sortable_columns' );
add_action( 'pre_get_posts', 'iron_music_pre_get_post_types' );
add_filter( 'posts_where', 'iron_events_where' );
add_filter( 'posts_orderby', 'iron_events_orderby' );
add_action( 'manage_event_posts_custom_column', 'iron_manage_event_custom_column', 10, 2 );
add_filter( 'manage_event_posts_columns', 'iron_music_manage_event_columns' );
add_action( 'posts_selection', 'iron_music_posts_selection' );

$iron_features_post_types = array('event', 'album', 'artist', 'video');
$iron_features_query = (object) array();

function post_type_init() {

    $default_args = array(
          'public'              => true
        , 'show_ui'             => true
        , 'show_in_menu'        => true
        , 'has_archive'         => true
        , 'query_var'           => true
        , 'exclude_from_search' => false
    );

    /* Events Post Type (events)
   ========================================================================== */
	$events_args = array(
		'labels'            => array(
			'name'                => esc_html__( 'Events', 'iron-music' ),
			'singular_name'       => esc_html__( 'Events', 'iron-music' ),
			'all_items'           => esc_html__( 'Events', 'iron-music' ),
			'new_item'            => esc_html__( 'New events', 'iron-music' ),
			'add_new'             => esc_html__( 'Add New', 'iron-music' ),
			'add_new_item'        => esc_html__( 'Add New events', 'iron-music' ),
			'edit_item'           => esc_html__( 'Edit events', 'iron-music' ),
			'view_item'           => esc_html__( 'View events', 'iron-music' ),
			'search_items'        => esc_html__( 'Search events', 'iron-music' ),
			'not_found'           => esc_html__( 'No events found', 'iron-music' ),
			'not_found_in_trash'  => esc_html__( 'No events found in trash', 'iron-music' ),
			'parent_item_colon'   => esc_html__( 'Parent events', 'iron-music' ),
			'menu_name'           => esc_html__( 'Events', 'iron-music' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'has_archive'       => true,
		'rewrite'           => true,
		'query_var'         => true,
		'menu_icon'         => 'dashicons-calendar-alt',
		'menu_position'		=> 25
	);

	$events_args['supports'] = array(
		'title',
		'editor',
		'excerpt',
		'thumbnail',
		'comments',
		'custom-fields',
		'revisions'
	);

	$slug = get_ironMusic_option( 'events_slug_name', '_iron_music_general_options' );
	$events_args['rewrite'] = array( 'slug'=>$slug );

	register_post_type( 'event',  $events_args );




    /* Album Post Type (album)
   ========================================================================== */

    $album_args = $default_args;

    $album_args['labels'] = array(
          'name'               => esc_html__('Discographies', 'iron-music')
        , 'singular_name'      => esc_html__('Album', 'iron-music')
        , 'name_admin_bar'     => esc_html_x('Album', 'add new on admin bar', 'iron-music')
        , 'menu_name'          => esc_html__('Discographies', 'iron-music')
        , 'all_items'          => esc_html__('All Albums', 'iron-music')
        , 'add_new'            => esc_html__('Add New', 'album', 'iron-music')
        , 'add_new_item'       => esc_html__('Add New album', 'iron-music')
        , 'edit_item'          => esc_html__('Edit album', 'iron-music')
        , 'new_item'           => esc_html__('New album', 'iron-music')
        , 'view_item'          => esc_html__('View album', 'iron-music')
        , 'search_items'       => esc_html__('Search Discography', 'iron-music')
        , 'not_found'          => esc_html__('No albums found.', 'iron-music')
        , 'not_found_in_trash' => esc_html__('No albums found in the Trash.', 'iron-music')
        , 'parent'             => esc_html__('Parent album:', 'iron-music')
    );

    $album_args['supports'] = array(
          'title'
        , 'editor'
        , 'excerpt'
        , 'thumbnail'
        , 'custom-fields'
        , 'revisions'
    );
    $slugMusic = get_ironMusic_option( 'discography_slug_name', '_iron_music_general_options' );
    $album_args['rewrite'] = array( 'slug'=>$slugMusic );

    $album_args['menu_icon'] = 'dashicons-format-audio';

    register_post_type( 'album' , $album_args);


     /* Artists Post Type (artist)
   ========================================================================== */

    $portfolio_args = $default_args;

    $portfolio_args['labels'] = array(

          'name'               => esc_html__('Artists', 'iron-music')
        , 'singular_name'      => esc_html__('Artist', 'iron-music')
        , 'name_admin_bar'     => esc_html_x('Artist', 'add new on admin bar', 'iron-music')
        , 'menu_name'          => esc_html__('Artists', 'iron-music')
        , 'all_items'          => esc_html__('All Artists', 'iron-music')
        , 'add_new'            => esc_html__('Add Artist', 'video', 'iron-music')
        , 'add_new_item'       => esc_html__('Add New Artist', 'iron-music')
        , 'edit_item'          => esc_html__('Edit Artist', 'iron-music')
        , 'new_item'           => esc_html__('New Project', 'iron-music')
        , 'view_item'          => esc_html__('View Artist', 'iron-music')
        , 'search_items'       => esc_html__('Search Artist', 'iron-music')
        , 'not_found'          => esc_html__('No artists found.', 'iron-music')
        , 'not_found_in_trash' => esc_html__('No artists found in the Trash.', 'iron-music')
        , 'parent'             => esc_html__('Parent Artist:', 'iron-music')
    );

    $portfolio_args['supports'] = array(
          'title'
        , 'editor'
        , 'excerpt'
        , 'thumbnail'
        , 'comments'
        , 'custom-fields'
        , 'revisions'
    );


    $portfolio_args['menu_icon'] = 'dashicons-groups';

    $slug = get_ironMusic_option( 'artist_slug_name', '_iron_music_general_options' );
	$portfolio_args['rewrite'] = array( 'slug'=>$slug );

    register_post_type('artist', $portfolio_args);


    /* Video Post Type (video)
   ========================================================================== */

    $video_args = $default_args;

    $video_args['labels'] = array(
          'name'               => esc_html__('Videos', 'iron-music')
        , 'singular_name'      => esc_html__('Video', 'iron-music')
        , 'name_admin_bar'     => esc_html_x('Video', 'add new on admin bar', 'iron-music')
        , 'menu_name'          => esc_html__('Videos', 'iron-music')
        , 'all_items'          => esc_html__('All Videos', 'iron-music')
        , 'add_new'            => esc_html__('Add New', 'video', 'iron-music')
        , 'add_new_item'       => esc_html__('Add New Video', 'iron-music')
        , 'edit_item'          => esc_html__('Edit Video', 'iron-music')
        , 'new_item'           => esc_html__('New Video', 'iron-music')
        , 'view_item'          => esc_html__('View Video', 'iron-music')
        , 'search_items'       => esc_html__('Search Video', 'iron-music')
        , 'not_found'          => esc_html__('No videos found.', 'iron-music')
        , 'not_found_in_trash' => esc_html__('No videos found in the Trash.', 'iron-music')
        , 'parent'             => esc_html__('Parent Video:', 'iron-music')
    );

    $video_args['supports'] = array(
          'title'
        , 'editor'
        , 'excerpt'
        , 'thumbnail'
        , 'comments'
        , 'custom-fields'
        , 'revisions'
    );


    $video_args['menu_icon'] = 'dashicons-format-video';

    $slug = get_ironMusic_option( 'video_slug_name', '_iron_music_general_options' );
	$video_args['rewrite'] = array( 'slug'=>$slug );
    register_post_type('video', $video_args);


}










function events_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['events'] = array(
		0 => '', // Unused. Messages start at index 1.

		1 => wp_kses(sprintf( __('Events updated. <a target="_blank" href="%s">View events</a>', 'iron-music'), esc_url( $permalink ) ),get_allowed_html()),
		2 => esc_html__('Custom field updated.', 'iron-music'),
		3 => esc_html__('Custom field deleted.', 'iron-music'),
		4 => esc_html__('Events updated.', 'iron-music'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( esc_html__('Events restored to revision from %s', 'iron-music'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => wp_kses(sprintf( __('Events published. <a href="%s">View events</a>', 'iron-music'), esc_url( $permalink ) ),get_allowed_html()),
		7 => esc_html__('Events saved.', 'iron-music'),
		8 => wp_kses(sprintf( __('Events submitted. <a target="_blank" href="%s">Preview events</a>', 'iron-music'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),get_allowed_html()),
		9 => wp_kses(sprintf( __('Events scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview events</a>', 'iron-music'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( esc_html__( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),get_allowed_html()),
		10 => wp_kses(sprintf( __('Events draft updated. <a target="_blank" href="%s">Preview events</a>', 'iron-music'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),get_allowed_html()),
	);

	return $messages;
}






/*-----------------------------------------------------------------------------------*/
/* Post Type Sorting & Filtering
/*-----------------------------------------------------------------------------------*/

function iron_music_pre_get_post_types ( $query )
{
    global $iron_features_post_types, $iron_features_query, $post;

    $post_type = $query->get('post_type');
    $posts_per_page = $query->get('posts_per_page');

    $iron_features_query->post_type = $post_type;

    if ( in_array($post_type, $iron_features_post_types) )
    {
        if ( empty($posts_per_page) || $posts_per_page == 0 ) {
            switch ($post_type) {
                case 'event':
                    $posts_per_page = get_ironMusic_option('events_per_page', '_iron_music_event_options');
                    break;
                case 'album':
                    $posts_per_page = get_ironMusic_option('albums_per_page', '_iron_music_discography_options');
                    break;

                default:
                    $posts_per_page = -1;
                    break;
            }
            $query->set( 'posts_per_page',  $posts_per_page);
        }
    }



    if ( (!is_admin() || ( defined('DOING_AJAX') && DOING_AJAX ) ) && ( $post_type == 'event' || $post_type == 'album' ) ) {

        $artists_filter = array();

        if(!empty($post->ID)) {
            $artists_filter = get_post_meta( $post->ID , 'artists_filter', true );
        }
        if(empty($artists_filter)) {
            $artists_filter = $query->get('artists_filter');
        }

        $key = false;
        if($post_type == 'event') {

            $key = 'artist_events';

        }else if($post_type == 'album') {

            $key = 'artist_discography';

        }


        if(!empty($key) && !empty($artists_filter)) {

            $artist_posts = array();
            foreach($artists_filter as $artist) {

                $posts = get_field($key, $artist);
                if ( is_array( $posts ) ) {
                    $artist_posts = array_merge($artist_posts, $posts);
                }
            }

            $query->set('post__in', $artist_posts);

        }

    }

    if ( 'album' == $post_type && !is_admin() ) {

        $query->set( 'posts_per_page', -1 );

    }

    if ( 'event' == $post_type )
    {
        $order = $query->get('order');
        $orderby = $query->get('orderby');



        if ( is_admin() && ! $query->get('ajax') ) {

            // Furthest to Oldest
            if ( empty( $order ) )
                $query->set('order', 'ASC');

            if ( empty( $orderby ) )
                $query->set('orderby', 'date');

        } else {

            if(empty($query->query_vars['filter'])) {
                $filter = get_field('events_filter', get_the_id());
                if(empty($filter)) {
                    $filter  = ( empty( $_POST['eventsfilter'] ) ? 'upcoming' : sanitize_key($_POST['eventsfilter']) );
                }

                $query->query_vars['filter'] = $filter;
            }

            $filter = $query->query_vars['filter'];
            $iron_features_query->query_vars['filter'] = $filter;

            // reset Post Status
            $query->set('post_status', array(''));

        }


    }

}



function iron_events_where ( $where = '' )
{
    if(is_single())
        return $where;

    global $wpdb, $iron_features_query, $wp_query;

    $post_type = $wp_query->get('post_type');
    if(empty($post_type))
        $post_type = $iron_features_query->post_type;


    if ( (!is_admin() || ( defined('DOING_AJAX') && DOING_AJAX ) ) && ( $post_type == 'event' ) && (@$_POST['action'] != 'acf/fields/relationship/query_posts')) {


        $filter = $iron_features_query->query_vars['filter'];

        if($filter == 'past') {

            $where .= " AND ($wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_status != 'future') AND DATE ($wpdb->posts.post_date) < '" . date_i18n('Y-m-d 00:00:00') . "'";

        }else{

            $where .= " AND ($wpdb->posts.post_status = 'publish' OR $wpdb->posts.post_status = 'future') AND DATE ($wpdb->posts.post_date) >= '" . date_i18n('Y-m-d 00:00:00') . "'";

        }


    }

    return $where;
}




function iron_events_orderby($orderby) {
    global $iron_features_query, $wpdb, $wp_query;

    if(is_single())
        return $orderby;

    $post_type = $wp_query->get('post_type');
    if(empty($post_type))
        $post_type = $iron_features_query->post_type;

    if ( (!is_admin() || ( defined('DOING_AJAX') && DOING_AJAX ) ) && ( $post_type == 'event' ) && (@$_POST['action'] != 'acf/fields/relationship/query_posts')) {

        $filter = sanitize_text_field($iron_features_query->query_vars['filter']);

        if($filter == 'past') {

            $orderby = $wpdb->prefix."posts.post_date DESC";

        }else{

            $orderby = $wpdb->prefix."posts.post_date ASC";

        }

    }

    return $orderby;
}



function iron_music_posts_selection ()
{
    $iron_features_query = (object) array();
}



function setup_future_hook() {
// Replace native future_post function with replacement
    remove_action('future_event','_future_post_hook');
    add_action('future_event','iron_music_publish_future_post_now');
}

function iron_music_publish_future_post_now($id) {
// Set new post's post_status to "publish" rather than "future."
    if(!empty($_POST["post_type"]) && $_POST["post_type"] == "event")
        wp_publish_post($id);
}




/*-----------------------------------------------------------------------------------*/
/* Event Management
/*-----------------------------------------------------------------------------------*/

function iron_music_manage_event_columns ($columns)
{
    unset( $columns['date'] );

    $iron_cols = array(
          'event_date'    => esc_html__('Date', 'iron-music')
        , 'event_city'    => esc_html__('City', 'iron-music')
        , 'event_venue'   => esc_html__('Venue', 'iron-music')
    );

    /*if ( function_exists('array_insert') )
        $columns = array_insert($columns, $iron_cols, 'date', 'after');
    else*/
        $columns = array_merge($columns, $iron_cols);


    $columns['title'] = esc_html__('Event', 'iron-music');  // Renamed first column

    return $columns;
}




// Events: Display Custom Columns

function iron_manage_event_custom_column ($column, $post_id)
{
    switch ($column)
    {
        case 'event_date':
            global $mode;

            $post = get_post( $post_id );
            setup_postdata( $post );

            if ( '0000-00-00 00:00:00' == $post->post_date ) {
                $t_time = $h_time = esc_html__('Unpublished', 'iron-music');
            } else {
                $t_time = get_the_time( esc_html__('Y/m/d g:i:s A', 'iron-music') );

                $h_time = date_i18n( get_option('date_format') . ' ' . get_option('time_format'), get_post_time('U', false, $post_id) );
            }

            echo '<abbr title="' . $t_time . '">' . apply_filters( 'post_date_column_time', $h_time, $post, 'event_date', $mode ) . '</abbr>';
        break;

        case 'event_city':
            $post_meta = get_post_meta( $post_id, 'event_city' );
            if ( ! empty( $post_meta ) )
                echo get_post_meta( $post_id, 'event_city', true );
            else
                echo esc_html__('N/A', 'iron-music');


            break;

        case 'event_venue':
            $post_meta = get_post_meta( $post_id, 'event_venue' );
            if ( ! empty( $post_meta ) )
                echo get_post_meta( $post_id, 'event_venue', true);
            else
                echo esc_html__('N/A', 'iron-music');
            break;

    }
}




// Events: Register Custom Columns as Sortable

function iron_music_manage_event_sortable_columns ($columns)
{
    $columns['event_date']  = 'date';
    // $columns['event_city']  = 'event_city';
    // $columns['event_venue'] = 'event_venue';

    return $columns;
}



/*-----------------------------------------------------------------------------------*/
/* Discography Management
/*-----------------------------------------------------------------------------------*/

// Album: Icon


function iron_music_manage_album_columns ($columns)
{
    $iron_cols = array(
          'alb_release_date' => esc_html__('Release Date', 'iron-music')
        , 'alb_tracklist'    => esc_html__('# Tracks', 'iron-music')
        , 'alb_store_list'   => esc_html__('# Stores', 'iron-music')
    );

    if ( function_exists('array_insert') )
        $columns = array_insert($columns, $iron_cols, 'date', 'before');
    else
        $columns = array_merge($columns, $iron_cols);


    $iron_cols = array(
        'icon' => ''
    );

    if ( function_exists('array_insert') )
        $columns = array_insert($columns, $iron_cols, 'title', 'before');
    else
        $columns = array_merge($columns, $iron_cols);

    $columns['date'] = esc_html__('Published', 'iron-music');   // Renamed date column

    return $columns;
}



add_filter('manage_album_posts_columns', 'iron_music_manage_album_columns');



// Discography: Display Custom Columns

function iron_music_manage_album_custom_column ($column, $post_id)
{
    switch ($column)
    {
        case 'alb_release_date':
            if ( get_field('alb_release_date', $post_id) )
                the_field('alb_release_date', $post_id);
            else
                echo esc_html__('N/A', 'iron-music');
            break;

        case 'alb_tracklist':
            if ( $list = get_field('alb_tracklist', $post_id) )
                echo count($list);
            else
                echo esc_html__('N/A', 'iron-music');
            break;

        case 'alb_store_list':
            if ( $list = get_field('alb_store_list', $post_id) )
                echo count($list);
            else
                echo esc_html__('N/A', 'iron-music');
            break;
        case 'icon':
            $att_title = _draft_or_post_title();
?>
                <a href="<?php echo esc_url(get_edit_post_link( $post_id, true )); ?>" title="<?php echo esc_attr( sprintf( esc_html__('Edit &#8220;%s&#8221;', 'iron-music'), $att_title ) ); ?>"><?php

                    if ( $thumb = get_the_post_thumbnail( $post_id, array(80, 60) ) )
                        echo $thumb;
                    else
                        echo '<img width="46" height="60" src="' . wp_mime_type_icon('image/jpeg') . '" alt="">';
                ?></a>
<?php
            break;
    }
}

add_action('manage_album_posts_custom_column', 'iron_music_manage_album_custom_column', 10, 2);


/*-----------------------------------------------------------------------------------*/
/* Video Management
/*-----------------------------------------------------------------------------------*/

function iron_music_manage_video_columns ($columns)
{
    $iron_cols = array(
        'icon' => ''
    );

    if ( function_exists('array_insert') )
        $columns = array_insert($columns, $iron_cols, 'title', 'before');
    else
        $columns = array_merge($columns, $iron_cols);

    $columns['date'] = __('Published', 'fwrd'); // Renamed date column

    return $columns;
}

add_filter('manage_video_posts_columns', 'iron_music_manage_video_columns');


// Videos: Display Custom Columns

function iron_music_manage_video_custom_column ($column, $post_id)
{
    switch ($column)
    {
        case 'icon':
            $att_title = _draft_or_post_title();
?>
                <a href="<?php echo esc_url(get_edit_post_link( $post_id, true )); ?>" title="<?php echo esc_attr( sprintf( __('Edit &#8220;%s&#8221;', 'fwrd'), $att_title ) ); ?>"><?php
                    if ( $thumb = get_the_post_thumbnail( $post_id, array(80, 60) ) )
                        echo $thumb;
                    else
                        echo '<img width="46" height="60" src="' . wp_mime_type_icon('image/jpeg') . '" alt="">';
                ?></a>
<?php
            break;
    }
}

add_action('manage_video_posts_custom_column', 'iron_music_manage_video_custom_column', 10, 2);








/**
 * Custom Taxonomies
 *
 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
 */

$iron_taxonomies = array();

function iron_music_register_taxonomies ()
{
    global $iron_taxonomies;

    $iron_taxonomies = array( 'video-category' );

    $args = array(
          'public'            => true
        , 'show_ui'           => true
        , 'show_in_nav_menus' => true
        , 'show_in_admin_bar' => false
        , 'show_admin_column' => true
        , 'show_tagcloud'     => false
        , 'query_var'         => false
        , 'rewrite'           => true
        , 'hierarchical'      => true
        , 'sort'              => false
    );


/* Video Categories (video-categories)
   ========================================================================== */

    $labels = array(
          'name'          => esc_html_x('Video Categories',     'Taxonomy : name',          'fwrd')
        , 'all_items'     => esc_html_x('All Categories',       'Taxonomy : all_items',     'fwrd')
        , 'singular_name' => esc_html_x('Category',             'Taxonomy : singular_name', 'fwrd')
        , 'add_new_item'  => esc_html_x('Add New Category',     'Taxonomy : add_new_item',  'fwrd')
        , 'not_found'     => esc_html_x('No categories found.', 'Taxonomy : not_found',     'fwrd')
    );

    $args['labels'] = $labels;


    register_taxonomy('video-category', 'video', $args);



/* Portfolio Categories (portfolio-categories)
   ========================================================================== */

    $labels = array(
          'name'          => esc_html_x('Portfolio Categories', 'Taxonomy : name',          'fwrd')
        , 'all_items'     => esc_html_x('All Categories',       'Taxonomy : all_items',     'fwrd')
        , 'singular_name' => esc_html_x('Category',             'Taxonomy : singular_name', 'fwrd')
        , 'add_new_item'  => esc_html_x('Add New Category',     'Taxonomy : add_new_item',  'fwrd')
        , 'not_found'     => esc_html_x('No categories found.', 'Taxonomy : not_found',     'fwrd')
    );

    $args['labels'] = $labels;


    register_taxonomy('portfolio-category', 'portfolio', $args);


/* Discography Categories (discography-categories)
   ========================================================================== */

    $labels = array(
          'name'          => esc_html_x('Discography Categories', 'Taxonomy : name',          'fwrd')
        , 'all_items'     => esc_html_x('All Categories',       'Taxonomy : all_items',     'fwrd')
        , 'singular_name' => esc_html_x('Category',             'Taxonomy : singular_name', 'fwrd')
        , 'add_new_item'  => esc_html_x('Add New Category',     'Taxonomy : add_new_item',  'fwrd')
        , 'not_found'     => esc_html_x('No categories found.', 'Taxonomy : not_found',     'fwrd')
    );

    $args['labels'] = $labels;


    register_taxonomy('album-category', 'album', $args);



/* Photo Albums Categories (photo-album-categories)
   ========================================================================== */

    $labels = array(
          'name'          => esc_html_x('Photo Albums Categories', 'Taxonomy : name',          'fwrd')
        , 'all_items'     => esc_html_x('All Categories',       'Taxonomy : all_items',     'fwrd')
        , 'singular_name' => esc_html_x('Category',             'Taxonomy : singular_name', 'fwrd')
        , 'add_new_item'  => esc_html_x('Add New Category',     'Taxonomy : add_new_item',  'fwrd')
        , 'not_found'     => esc_html_x('No categories found.', 'Taxonomy : not_found',     'fwrd')
    );

    $args['labels'] = $labels;


    register_taxonomy('photo-album-category', 'photo-album', $args);

}

add_action('init', 'iron_music_register_taxonomies');

function iron_music_post_class_terms ( $classes = array() )
{
    global $post, $iron_taxonomies;


/*

    // Tags
    if ( is_object_in_taxonomy( $post->post_type, 'post_tag' ) ) {
        foreach ( (array) get_the_tags($post->ID) as $tag ) {
            if ( empty($tag->slug ) )
                continue;
            $classes[] = 'tag-' . sanitize_html_class($tag->slug, $tag->term_id);
        }
    }

*/
    global $post;

    foreach ( $iron_taxonomies as $tax )
    {
        if ( is_object_in_taxonomy( $post->post_type, $tax ) )
        {
            $terms = get_the_terms($post->ID, $tax);

            foreach ( (array) $terms as $term ) {
                if ( empty($term->slug ) )
                    continue;
                $classes[] = sanitize_html_class($tax, 'tax') . '-' . sanitize_html_class($term->slug, $term->term_id);
            }

            # Alternate
            // $terms = wp_list_pluck($terms, 'slug');
            // $classes = array_merge($classes, $terms);
        }
    }

    return $classes;
}

add_filter('post_class', 'iron_music_post_class_terms');