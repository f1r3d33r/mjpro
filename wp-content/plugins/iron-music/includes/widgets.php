<?php




/**
 * Events Widget Class
 *
 * @since 1.6.0
 * @see   Iron_Widget_Posts
 * @todo  - Add advanced options
 *        - Merge Videos, and Posts
 */

class Iron_Music_Widget_Events extends Iron_Music_Widget
{

	/**
	 * Widget Defaults
	 */

	public static $widget_defaults;
	
	
	/**
	 * Register widget with WordPress.
	 */

	function __construct ()
	{
		$widget_ops = array(
			  'classname'   => 'Iron_Music_Widget_Events'
			, 'description' => esc_html_x('List upcoming or past events on your site.', 'Widget', IRON_MUSIC_TEXT_DOMAIN)
		);

		self::$widget_defaults = array(
			  'title'        => ''
			, 'post_type'    => 'event'
			, 'filter'		 => ''
			, 'number'       => get_ironMusic_option('events_per_page', '_iron_music_discography_options')
			, 'filter'		 => 'upcoming' 
			, 'artists_filter' => array()
			, 'enable_artists_filter' => false
			, 'action_title' => ''
			, 'action_obj_id'  => ''
			, 'action_ext_link'  => ''
		);

		parent::__construct('iron-features-events', IRON_MUSIC_PREFIX . esc_html__('Events', 'Widget', IRON_MUSIC_TEXT_DOMAIN), $widget_ops);

		add_action( 'save_post', array($this, 'flush_widget_cache') );
		add_action( 'deleted_post', array($this, 'flush_widget_cache') );
		add_action( 'switch_theme', array($this, 'flush_widget_cache') );
	}

	function widget ( $args, $instance )
	{
		global $post;
		
		$cache = wp_cache_get('Iron_Music_Widget_Events', 'widget');

		if ( ! is_array($cache) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();
		$args['before_title'] = "<span class='heading-t3'></span>".$args['before_title'];
		$args['before_title'] = str_replace('h2','h3',$args['before_title']);
		$args['after_title'] = str_replace('h2','h3',$args['after_title']);
		/*$args['after_title'] = $args['after_title']."<span class='heading-b3'></span>";*/
		extract($args);

		$instance = wp_parse_args( (array) $instance, self::$widget_defaults );

		$title      = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$post_type  = apply_filters( 'widget_post_type', $instance['post_type'], $instance, $this->id_base );
		$number     = $instance['number'];
		$filter 	= $instance['filter'];
		
		$meta_query = array();
		$artists_filter = $instance['artists_filter'];
		$enable_artists_filter = $instance['enable_artists_filter'];
		if(!empty($artists_filter)) {
			if(!is_array($artists_filter)) {
				$artists_filter = explode(",", $artists_filter);
				$meta_query = array(
					array(
						'key'     => 'artist_at_event',
						'value'   => implode('|',$artists_filter),
						'compare' => 'rlike',
					),
				);

			}
		}

		// $show_date  = $instance['show_date'];
		// $thumbnails = $instance['thumbnails'];

		$r = new WP_Query( apply_filters( 'IronFeatures_Widget_Events_args', array(
			  'post_type'           => $post_type
			, 'filter'      		=> $filter
			, 'artists_filter'		=> $artists_filter
			, 'posts_per_page'      => $number
			, 'no_found_rows'       => true
			, 'post_status'         => 'publish'
			, 'ignore_sticky_posts' => true
			, 'meta_query' => $meta_query
		) ) );


			$action_title = apply_filters( 'iron_widget_action_title', $instance['action_title'], $instance, $this->id_base );
			$action_obj_id = apply_filters( 'iron_widget_action_obj_id', $instance['action_obj_id'], $instance, $this->id_base );
			$action_ext_link = apply_filters( 'iron_widget_action_ext_link', $instance['action_ext_link'], $instance, $this->id_base );
	
			/***/
	
			$action = $this->action_link( $action_obj_id, $action_ext_link, $action_title);



			echo $before_widget;

			if ( ! empty( $title ) )
				echo sprintf( $before_title, $action ) . $title . $after_title;
				if(!empty($title)){$this->get_title_divider();}
   

				if(!empty($enable_artists_filter) && sizeof($artists_filter) > 1) {
					iron_get_events_filter($artists_filter);
				}
				?>
				
				<ul id="post-list" class="concerts-list">

<?php
				
				$permalink_enabled = (bool) get_option('permalink_structure');
				while ( $r->have_posts() ) : $r->the_post();
					$post->filter = $filter;
					iron_music_get_template_part('event');

				endwhile;
				
				if(!$r->have_posts()): 
				?>
				
					<li class="nothing-found">
					<?php 
					if($filter == 'upcoming')
						echo esc_html__("No upcoming events scheduled yet. Stay tuned!", IRON_MUSIC_TEXT_DOMAIN);
					else
						echo esc_html__("No events scheduled yet. Stay tuned!", IRON_MUSIC_TEXT_DOMAIN);
					?>
					</li>

				<?php endif; ?>
				

				<li><?php echo $action; ?></li>
				</ul>

<?php

			echo $after_widget;
			//echo $action;

			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();


		wp_reset_query();
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('IronFeatures_Widget_Events', $cache, 'widget');
	}

	function update ( $new_instance, $old_instance )
	{
		$instance = wp_parse_args( (array) $old_instance, self::$widget_defaults );

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['filter']  = $new_instance['filter'];
		$instance['artists_filter']  = $new_instance['artists_filter'];
		$instance['action_title']  = $new_instance['action_title'];
		$instance['action_obj_id']  = $new_instance['action_obj_id'];
		$instance['action_ext_link']  = $new_instance['action_ext_link'];

		$this->flush_widget_cache();

		return $instance;
	}

	function flush_widget_cache ()
	{
		wp_cache_delete('IronFeatures_Widget_Events', 'widget');
	}

	function form ( $instance )
	{
		$instance = wp_parse_args( (array) $instance, self::$widget_defaults );

		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$filter    = $instance['filter'];
		$artists_filter = $instance['artists_filter'];
		$action_title = $instance['action_title'];
		$action_obj_id = $instance['action_obj_id'];
		$action_ext_link = $instance['action_ext_link'];
?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', IRON_MUSIC_TEXT_DOMAIN); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" placeholder="<?php esc_html_e('Upcoming Events', IRON_MUSIC_TEXT_DOMAIN); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>"><?php esc_html_e('Number of events to show:', IRON_MUSIC_TEXT_DOMAIN); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'filter' )); ?>"><?php esc_html_e('Filter By:', IRON_MUSIC_TEXT_DOMAIN); ?></label>
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('filter')); ?>" name="<?php echo esc_attr($this->get_field_name('filter')); ?>">
				<option <?php echo ($filter == 'upcoming' ? 'selected' : ''); ?> value="upcoming"><?php _ex('Upcoming Events', 'Widget', IRON_MUSIC_TEXT_DOMAIN); ?></option>
				<option <?php echo ($filter == 'past' ? 'selected' : ''); ?> value="past"><?php _ex('Past Events', 'Widget', IRON_MUSIC_TEXT_DOMAIN); ?></option>
			</select>						
		<p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'artists_filter' )); ?>"><?php esc_html_e('Filter By Artists:', IRON_MUSIC_TEXT_DOMAIN); ?></label>
			<select multiple class="widefat" id="<?php echo esc_attr($this->get_field_id('artists_filter')); ?>" name="<?php echo esc_attr($this->get_field_name('artists_filter')); ?>[]">
				<?php echo $this->get_object_options($artists_filter, 'artist'); ?>
			</select>						
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('action_title')); ?>"><?php _ex('Call To Action Title:', 'Widget', IRON_MUSIC_TEXT_DOMAIN); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('action_title')); ?>" name="<?php echo esc_attr($this->get_field_name('action_title')); ?>" value="<?php echo esc_attr($action_title); ?>" placeholder="<?php esc_html_e('View More', IRON_MUSIC_TEXT_DOMAIN); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('action_obj_id')); ?>"><?php _ex('Call To Call To Action Page:', 'Widget', IRON_MUSIC_TEXT_DOMAIN); ?></label>
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('action_obj_id')); ?>" name="<?php echo esc_attr($this->get_field_name('action_obj_id')); ?>">
				<?php echo $this->get_object_options($action_obj_id); ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('action_ext_link')); ?>"><?php _ex('Call To Action External Link:', 'Widget', IRON_MUSIC_TEXT_DOMAIN); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('action_ext_link')); ?>" name="<?php echo esc_attr($this->get_field_name('action_ext_link')); ?>" value="<?php echo esc_attr($action_ext_link); ?>" />
		</p>

		
<?php
	}
} // class IronFeatures_Widget_Events




/**
 * Discography Widget Class
 *
 * @since 1.6.0
 * @todo  - Add options
 */

 
class Iron_Music_Widget_Discography extends Iron_Music_Widget
{
	//Widget Defaults
	public static $widget_defaults;
	
	//Register widget with WordPress
	function __construct ()
	{
		$widget_ops = array(
			  'classname'   => 'Iron_Music_Widget_discography'
			, 'description' => esc_html_x('A grid view of your selected albums.', 'Widget', IRON_MUSIC_TEXT_DOMAIN)
		);

		self::$widget_defaults = array(
			  'title'        => ''
			, 'albums'     	 => array()
			, 'artists_filter' => array()
			, 'action_title' => ''
			, 'action_obj_id'  => ''
			, 'action_ext_link'  => ''
		);

		parent::__construct('iron-features-discography', IRON_MUSIC_PREFIX . esc_html_x('Discography', 'Widget', IRON_MUSIC_TEXT_DOMAIN), $widget_ops);

	}

	//Front-end display of widget
	public function widget ( $args, $instance )
	{		
		global $post, $widget;
		
		$args['before_title'] = "<span class='heading-t3'></span>".$args['before_title'];
		$args['before_title'] = str_replace('h2','h3',$args['before_title']);
		$args['after_title'] = str_replace('h2','h3',$args['after_title']);
		//$args['after_title'] = $args['after_title']."<span class='heading-b3'></span>";
		extract($args);
	
		$instance = wp_parse_args( (array) $instance, self::$widget_defaults );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$albums = $instance['albums'];
		if(!is_array($albums)) {
			$albums = explode(",", $albums);
		}
		
		$meta_query = array();
		$artists_filter = $instance['artists_filter'];
		if(!empty($artists_filter)) {
			if(!is_array($artists_filter)) {
				$artists_filter = explode(",", $artists_filter);
				$meta_query =  array(
					array(
						'key'     => 'artist_of_album',
						'value'   => implode('|',$artists_filter),
						'compare' => 'rlike',
					),
				);
			}
		}
		
		$query_args = array(
			  'post_type'           => 'album'
			, 'artists_filter' 	=> $artists_filter 
			, 'posts_per_page'      => -1
			, 'no_found_rows'       => true
			, 'post_status'         => 'publish'
			, 'ignore_sticky_posts' => true
			, 'post__in' => $albums	
			, 'meta_query' => $meta_query
		);
	
		$r = new WP_Query( apply_filters( 'iron_widget_posts_args', $query_args));

			
		if ( $r->have_posts() ) :


			$action_title = apply_filters( 'iron_widget_action_title', $instance['action_title'], $instance, $this->id_base );
			$action_obj_id = apply_filters( 'iron_widget_action_obj_id', $instance['action_obj_id'], $instance, $this->id_base );
			$action_ext_link = apply_filters( 'iron_widget_action_ext_link', $instance['action_ext_link'], $instance, $this->id_base );
	
			$action = $this->action_link( $action_obj_id, $action_ext_link, $action_title);

			echo $before_widget;

			if ( ! empty( $title ) )
				echo sprintf( $before_title, $action ) . $title . $after_title;
				if(!empty($title)){$this->get_title_divider();}

?>
				<div id="albums-list" class="two_column_album">

<?php
				$widget = true;
				$permalink_enabled = (bool) get_option('permalink_structure');
				while ( $r->have_posts() ) : $r->the_post();
					iron_music_get_template_part('album');
				endwhile;
?>
				<?php echo $action; ?>
				</div>

<?php
			
			echo $after_widget;
			//echo $action;

			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();

		endif;
		wp_reset_query();
	}

	//Back-end widget form.

	public function form ( $instance )
	{
		$instance = wp_parse_args( (array) $instance, self::$widget_defaults );

		$title = esc_attr( $instance['title'] );
		$albums = $instance['albums'];
		$artists_filter = $instance['artists_filter'];
		$action_title = $instance['action_title'];
		$action_obj_id = $instance['action_obj_id'];
		$action_ext_link = $instance['action_ext_link'];
		
		$all_albums = get_posts(array(
			  'post_type' => 'album'
			, 'posts_per_page' => -1
			, 'no_found_rows'  => true
		));


		if ( !empty($all_albums) ) :
?>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _ex('Title:', 'Widget', IRON_MUSIC_TEXT_DOMAIN); ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" placeholder="<?php esc_html_e('Popular Albums', IRON_MUSIC_TEXT_DOMAIN); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('albums')); ?>"><?php _ex('Album:', 'Widget', IRON_MUSIC_TEXT_DOMAIN); ?></label>
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id('albums')); ?>" name="<?php echo esc_attr($this->get_field_name('albums')); ?>[]" multiple="multiple">
				<?php foreach($all_albums as $a): ?>
				
					<option value="<?php echo esc_attr($a->ID); ?>"<?php echo (in_array($a->ID, $albums) ? ' selected="selected"' : ''); ?>><?php echo esc_html($a->post_title); ?></option>
				
				<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'artists_filter' )); ?>"><?php esc_html_e('Filter By Artists:', IRON_MUSIC_TEXT_DOMAIN); ?></label>
				<select multiple class="widefat" id="<?php echo esc_attr($this->get_field_id('artists_filter')); ?>" name="<?php echo esc_attr($this->get_field_name('artists_filter')); ?>[]">
					<?php echo $this->get_object_options($artists_filter, 'artist'); ?>
				</select>						
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('action_title')); ?>"><?php _ex('Call To Action Title:', 'Widget', IRON_MUSIC_TEXT_DOMAIN); ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('action_title')); ?>" name="<?php echo esc_attr($this->get_field_name('action_title')); ?>" value="<?php echo esc_attr($action_title); ?>" placeholder="<?php esc_html_e('View More', IRON_MUSIC_TEXT_DOMAIN); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('action_obj_id')); ?>"><?php _ex('Call To Call To Action Page:', 'Widget', IRON_MUSIC_TEXT_DOMAIN); ?></label>
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id('action_obj_id')); ?>" name="<?php echo esc_attr($this->get_field_name('action_obj_id')); ?>">
					<?php echo $this->get_object_options($action_obj_id); ?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('action_ext_link')); ?>"><?php _ex('Call To Action External Link:', 'Widget', IRON_MUSIC_TEXT_DOMAIN); ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('action_ext_link')); ?>" name="<?php echo esc_attr($this->get_field_name('action_ext_link')); ?>" value="<?php echo esc_attr($action_ext_link); ?>" />
			</p>
						
			

<?php

		else :
			
				echo wp_kses_post( '<p>'. sprintf( _x('No albums have been created yet. <a href="%s">Create some</a>.', 'Widget', IRON_MUSIC_TEXT_DOMAIN), admin_url('edit.php?post_type=album') ) .'</p>' );
			
		endif;


	}

	//Sanitize widget form values as they are saved.

	public function update ( $new_instance, $old_instance )
	{
		$instance = wp_parse_args( $old_instance, self::$widget_defaults );

		$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		$instance['albums'] = $new_instance['albums'];
		$instance['artists_filter'] = $new_instance['artists_filter'];
		$instance['action_title']  = $new_instance['action_title'];
		$instance['action_obj_id']  = $new_instance['action_obj_id'];
		$instance['action_ext_link']  = $new_instance['action_ext_link'];
		
		return $instance;
	}

} // class Iron_Widget_Discography



function ironFeatures_widgets_init(){
	register_widget( 'Iron_Music_Widget_Events' );
	register_widget( 'Iron_Music_Widget_Discography' );
}

add_action('widgets_init', 'ironFeatures_widgets_init');
