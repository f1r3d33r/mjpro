<?php get_header(); ?>

<?php 

$title = get_iron_option('404_page_title'); 
$content = get_iron_option('404_page_content'); 
$pageID404 = get_iron_option('404_page_selection');
	
?>
	<!-- container -->
	<div class="container">
	
		<div class="content__wrapper <?php echo ( $pageID404 ) ? '': 'boxed' ?>">
			<!-- single-post -->
			<article class="single-post">
			<div class="entry">
					<div class="<?php echo ( iron_is_page_title_uppercase() ) ? 'uppercase' : '' ?>">
						<span class="heading-t"></span>
						<h1><?php echo ( $pageID404 ) ? get_the_title( $pageID404 ) : esc_html__('Page not found', 'fwrd'); ?></h1>
						<?php iron_page_title_divider(); ?>
					</div>
				<?php echo ( $pageID404 ) ? apply_filters( 'the_content', get_post_field( 'post_content', $pageID404)) : wp_kses('<p style="text-align: center;">' . __('Are you lost? The content you were looking for is not here.','fwrd') . '</p><p style="text-align: center;"><a href="' . get_home_url( null, '/' ) . '">'. __('Return to home page', 'fwrd') . '</a></p>', iron_get_allowed_html()); ?>
				</div>
			</article>
		</div>
	</div>

<?php get_footer(); ?>