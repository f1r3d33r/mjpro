<?php

/*-----------------------------------------------------------------------------------*/
/* Post Type Registering
/*-----------------------------------------------------------------------------------*/

$iron_post_types = array();
$iron_query = (object) array();
$use_dashicons = floatval($wp_version) >= 3.8;

function iron_register_post_types() {
	global $iron_post_types, $use_dashicons;

	$iron_post_types = array('video');

	$default_args = array(
		  'public'              => true
		, 'show_ui'             => true
		, 'show_in_menu'        => true
		, 'has_archive'         => true
		, 'query_var'           => true
		, 'exclude_from_search' => false
	);



/* Portfolio Post Type (portfolio)
   ========================================================================== */

	$portfolio_args = $default_args;

	$portfolio_args['labels'] = array(
		  'name'               => esc_html__('Portfolio', 'fwrd')
		, 'singular_name'      => esc_html__('Portfolio', 'fwrd')
		, 'name_admin_bar'     => esc_html_x('Portfolio', 'add new on admin bar', 'fwrd')
		, 'menu_name'          => esc_html__('Portfolio', 'fwrd')
		, 'all_items'          => esc_html__('All Projects', 'fwrd')
		, 'add_new'            => esc_html_x('Add Project', 'video', 'fwrd')
		, 'add_new_item'       => esc_html__('Add New Project', 'fwrd')
		, 'edit_item'          => esc_html__('Edit Project', 'fwrd')
		, 'new_item'           => esc_html__('New Project', 'fwrd')
		, 'view_item'          => esc_html__('View Project', 'fwrd')
		, 'search_items'       => esc_html__('Search Video', 'fwrd')
		, 'not_found'          => esc_html__('No projects found.', 'fwrd')
		, 'not_found_in_trash' => esc_html__('No projects found in the Trash.', 'fwrd')
		, 'parent'             => esc_html__('Parent Project:', 'fwrd')
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
	
	if($use_dashicons)
		$portfolio_args['menu_icon'] = 'dashicons-format-aside';
	
	$portfolio_args['rewrite'] = array('slug'=>esc_html__('_portfolio', 'fwrd'));
	


	
	
/* ========================================================================== */


	if ( get_transient('fwrd' . '_flush_rules') ) {
		flush_rewrite_rules( false );
		delete_transient('fwrd' . '_flush_rules');
	}
}

add_action('init', 'iron_register_post_types', 1);




/*-----------------------------------------------------------------------------------*/
/* Post Type Sorting & Filtering
/*-----------------------------------------------------------------------------------*/

function iron_pre_get_post_types ( $query )
{
	global $iron_post_types, $iron_query, $post;

	$post_type = $query->get('post_type');
	$posts_per_page = $query->get('posts_per_page');

	$iron_query->post_type = $post_type;

	if ( in_array($post_type, $iron_post_types) )
	{
		if ( empty($posts_per_page) || $posts_per_page == 0 ) {
			$posts_per_page = get_iron_option($post_type . 's_per_page');
			$query->set( 'posts_per_page',  $posts_per_page);
		}
	}



	if ( (!is_admin() || ( defined('DOING_AJAX') && DOING_AJAX ) ) && ( $post_type == 'video') ) {
		
		$artists_filter = array();
		
		if(!empty($post->ID)) {
			$artists_filter = get_field('artists_filter', $post->ID);
		}	
		if(empty($artists_filter)) {
			$artists_filter = $query->get('artists_filter');
		}
			
		$key = false;	
		if($post_type == 'video') {
			
			$key = 'artist_videos';
			
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

}

add_action('pre_get_posts', 'iron_pre_get_post_types');


function iron_posts_selection ()
{
	$iron_query = (object) array();
}

add_action('posts_selection', 'iron_posts_selection');



/*-----------------------------------------------------------------------------------*/
/* Page Management
/*-----------------------------------------------------------------------------------*/

// Register Custom Columns & Unregister Default Columns
if ( ! function_exists('iron_manage_pages_columns') )
{
	function iron_manage_pages_columns ( $columns )
	{
		$iron_cols = array(
			'template' => esc_html__('Page Template', 'fwrd')
		);

		if ( function_exists('array_insert') )
			$columns = array_insert($columns, $iron_cols, 'title', 'after');
		else
			$columns = array_merge($columns, $iron_cols);

		return $columns;
	}
}

add_filter('manage_pages_columns', 'iron_manage_pages_columns');



// Display Custom Columns
if ( ! function_exists('iron_manage_pages_custom_column') )
{
	function iron_manage_pages_custom_column ( $column, $post_id )
	{
		switch ($column)
		{
			case 'template':
				$output = ''; // esc_html__('Default', 'fwrd')
				$tpl = get_post_meta( $post_id, '_wp_page_template', true);
				$templates = get_page_templates();
				ksort($templates);
				foreach ( array_keys($templates) as $template )
				{
					if ( $tpl == $templates[$template] ) {
						$output = $template;
						break;
					}
				}
				echo esc_html($output);
			break;

		}
	}
}

add_action('manage_pages_custom_column', 'iron_manage_pages_custom_column', 10, 2);


/*-----------------------------------------------------------------------------------*/
/* Video Management
/*-----------------------------------------------------------------------------------*/

function iron_manage_video_columns ($columns)
{
	$iron_cols = array(
		'icon' => ''
	);

	if ( function_exists('array_insert') )
		$columns = array_insert($columns, $iron_cols, 'title', 'before');
	else
		$columns = array_merge($columns, $iron_cols);

	$columns['date'] = esc_html__('Published', 'fwrd');	// Renamed date column

	return $columns;
}

add_filter('manage_video_posts_columns', 'iron_manage_video_columns');


// Videos: Display Custom Columns

function iron_manage_video_custom_column ($column, $post_id)
{
	switch ($column)
	{
		case 'icon':
			$att_title = _draft_or_post_title();
?>
				<a href="<?php echo esc_url(get_edit_post_link( $post_id, true )); ?>" title="<?php echo esc_attr( sprintf( esc_html__('Edit &#8220;%s&#8221;', 'fwrd'), $att_title ) ); ?>"><?php
					if ( $thumb = get_the_post_thumbnail( $post_id, array(80, 60) ) )
						echo $thumb;
					else
						echo '<img width="46" height="60" src="' . wp_mime_type_icon('image/jpeg') . '" alt="">';
				?></a>
<?php
			break;
	}
}

add_action('manage_video_posts_custom_column', 'iron_manage_video_custom_column', 10, 2);
