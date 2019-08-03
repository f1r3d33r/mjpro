<?php global $enable_excerpts, $show_post_date, $show_date, $show_post_author, $show_post_tags, $show_post_categories;?>

<article id="post-<?php the_ID(); ?>" <?php post_class('media-block'); ?>>
		<div class="holder">
			<?php if(has_post_thumbnail()): ?>
				<a href="<?php the_permalink(); ?>" class="image"><?php the_post_thumbnail( 'medium' ); ?></a>
			<?php else :?>
				<div class="image empty"></div>
			<?php endif; ?>
			<div class="text-box<?php if(!has_post_thumbnail()){ echo " empty"; }?>">
			
				<a href="<?php the_permalink(); ?>"><h2><?php the_title(); ?></h2></a>
				
				<div class="classic-meta">
				<?php if($show_post_date || $show_date): ?>
					<a href="<?php the_permalink() ?>">	
						<time class="datetime" datetime="<?php the_time('c'); ?>"><?php the_time( get_option('date_format') ); ?> </time>
					</a>
				<?php endif; ?>
				<?php if ($show_post_author): ?>
					<?php echo esc_html__('by', 'fwrd'); ?> <a class="meta-author-link" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author(); ?></a>
				<?php endif ?>
				<?php 
					$categories_list = get_the_category_list( ', ',get_the_ID() );
					if(!empty($categories_list) && $show_post_categories) 
					echo '<div class="post-categories"><i class="fa fa-folder-open-o"></i>'.$categories_list.'</div>';

					$tag_list = get_the_tag_list('',', ');
					if(!empty($tag_list) && $show_post_tags )
					echo '<div class="post-tags"><i class="fa fa-tag"></i>'.$tag_list.'</div>';

				?>
				 </div>
				
				<?php if( !$enable_excerpts ):?>
					
					<?php the_content(esc_html__('Read More', 'fwrd')); ?>
					
				<?php endif; ?>
				<div class="stickypost">
					<i class="fa fa-star"></i>
				</div>
			</div>
		</div>
</article>
