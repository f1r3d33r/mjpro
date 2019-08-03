<?php global $enable_excerpts, $show_post_date, $show_date, $isocol, $show_post_author, $show_post_categories, $show_post_tags; ?>

<div class="news-grid-wrap <?php echo esc_attr($isocol); ?>">
	<?php
		$issticky = "";
		if(is_sticky()){
			$issticky = 'sticky';
		};
	?>
	<a href="<?php echo get_permalink();?>" class="<?php echo esc_attr($issticky); ?>">
		<?php if(has_post_thumbnail()): ?>
			<?php the_post_thumbnail('medium'); ?>
		<?php endif; ?>
		<div class="news-grid-tab">
			<div class="tab-text">
				<?php if($show_post_date || $show_date): ?>
				<time class="datetime" datetime="<?php the_time('c'); ?>"><?php the_time( get_option('date_format') ); ?></time>
				<?php endif; ?>
				<div class="tab-title"><?php the_title(); ?></div>
				
				<div class="meta-simple">
				<?php if ($show_post_author): ?>
					<div class="meta-author-link" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo esc_html__('by', 'fwrd'); ?> <?php the_author(); ?></div>
				<?php endif ?>
				<?php 
					$categories_list = get_the_category( get_the_ID() );
					if(!empty($categories_list) && $show_post_categories){
						echo '<div class="post-categories"><i class="fa fa-folder-open-o"></i> ';
						foreach ($categories_list as $key => $value) {
							echo (  $key + 1 == count( $categories_list ) )? $value->name  : $value->name . ', ' ;
						}
						echo '</div> ';
					}
					
					$tag_list = get_the_tags( get_the_ID() );
					if( $tag_list && $show_post_tags ){
						echo '<div class="post-tags"><i class="fa fa-tag"></i> ';
						foreach ($tag_list as $key => $value) {
							echo (  $key + 1 == count( $tag_list ) )? $value->name  : $value->name . ', ' ;
						}
						echo '</div> ';
					}
				?>
				</div>
				
				<?php if($enable_excerpts): ?>
				<div class="excerpt">
					<?php the_excerpt(); ?>
				</div>
				<?php endif; ?>
				<div class="stickypost">
					<i class="fa fa-star"></i>
				</div>
			</div>
		</div>
	</a>
	<div class="clear"></div>
</div>