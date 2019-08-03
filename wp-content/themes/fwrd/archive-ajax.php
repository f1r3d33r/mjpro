<?php

	include_once('archive-settings.php');	

	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
			get_iron_part( $item_template );
		endwhile;
	else:
		echo '<div class="search-result"><h3>'.esc_html__('Nothing Found!', 'fwrd').'</h3>';
		echo '<p>'.esc_html__('Search keyword', 'fwrd').': '.get_search_query().'</p>';
		echo '<p>'.esc_html__('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'fwrd').'</p></div>';	
	endif;

	$next_link = get_next_posts_link(esc_html__('More', 'fwrd'),''); 
	$next_link = str_replace('<a ', '<a data-rel="post-list" '.implode(' ', $attr).' class="button-more" ', $next_link);
	
	echo $next_link;
?>


	
