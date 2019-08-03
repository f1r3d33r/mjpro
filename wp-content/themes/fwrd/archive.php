<?php

$is_ajax = !empty($_POST["ajax"]) ? true : false;

if($is_ajax) {
	include_once(locate_template('archive-ajax.php'));
}else{
	get_header();
	include_once(locate_template('archive-settings.php'));
		
?>
	
	<!-- container -->
	<div class="container">
		<div class="boxed">
	
			<?php if(empty($hide_page_title)){ ?>
				<div class="page-title <?php echo (iron_is_page_title_uppercase() == true) ? 'uppercase': ''; ?>">
					<span class="heading-t"></span>
					<h1><?php echo esc_html($archive_title); ?></h1>
					<?php iron_page_title_divider(); ?>
				</div>
			<?php } ?>
			<?php 
				echo $archive_content;
				if ( isset( $has_sidebar ) && $has_sidebar ) { ?>
					<div id="twocolumns" class="content__wrapper<?php if ( 'left' === $sidebar_position ) echo ' content--rev'; ?>">
					<div id="content" class="content__main">
			<?php
				}
				$iron_option = ( function_exists( 'get_ironMusic_option' ) ? get_ironMusic_option( 'events_filter', '_iron_music_event_options' ) : false );
				$artists_filter = get_field('artists_filter');
		
				if($post_type == 'event' && $iron_option && !empty( $artists_filter ) ) {
					foreach($artists_filter as $artist){
						$artists[] = $artist->ID;
					}
					iron_get_events_filter($artists);
				}
			
				// post-list
				echo '<'.esc_attr($tag).' id="post-list" class="'.esc_attr($class).'">';
				if ( $paginate_method != 'paginate_more' ){
					if ( have_posts() ){
						while ( have_posts() ){
							the_post();
							get_iron_part( $item_template );
						} 
					}else{
						echo '<div class="search-result"><h3>'.esc_html__('Nothing Found!', 'fwrd').'</h3>';
						echo '<p>'.esc_html__('Search keyword', 'fwrd').': '.get_search_query().'</p>';
						echo '<p>'.esc_html__('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'fwrd').'</p></div>';		
					}
				}
				echo '</'.esc_attr($tag).'>';
				if ( $paginate_method == 'paginate_more' ){
					$next_link = '<a href="#" onclick="this.href = location.href" data-rel="post-list" '.implode(' ', $attr).' class="button-more">'.esc_html__('More', 'fwrd').'</a>';
					echo $next_link;
				
				}elseif( $paginate_method == 'paginate_links' ){ ?>

					<div class="pages full clear">
						<?php iron_full_pagination(); ?>
					</div>
	
				<?php }else{ ?>
					<div class="pages clear">
						<div class="alignleft button-next-prev"><?php previous_posts_link('&laquo; '.$prev, ''); ?></div>
						<div class="alignright button-next-prev"><?php next_posts_link($next.' &raquo;',''); ?></div>
					</div>
	
				<?php }
	
	
				if ( isset( $has_sidebar ) && $has_sidebar ){
					echo '</div><aside id="sidebar" class="content__side widget-area widget-area--'.esc_attr( $sidebar_area ).'">';
					do_action('before_ironband_sidebar_dynamic_sidebar', 'archive.php');
					dynamic_sidebar( $sidebar_area );
					do_action('after_ironband_sidebar_dynamic_sidebar', 'archive.php');
					echo '</aside></div>';
				}
			echo '</div></div>';
	get_footer();
	}