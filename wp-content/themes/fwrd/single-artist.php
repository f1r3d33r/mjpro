<?php
get_header();
?>

<?php
$archive_page = get_iron_option('page_for_artists');
$archive_page = ( empty($archive_page) ? false : post_permalink($archive_page) );

$template = get_field('single-artist-template');

/**
 * Setup Dynamic Sidebar
 */
list( $has_sidebar, $sidebar_position, $sidebar_area ) = iron_setup_dynamic_sidebar( $post->ID );
?>

		<!-- container -->
		<div class="container">
		<div class="boxed">

		<?php
		$single_title = get_iron_option('single_artist_page_title');
		if(!empty($single_title)): 
		?>
		
		<?php
			if(iron_is_page_title_uppercase() == true){
				echo '<div class="page-title uppercase">';
			} else {
				echo '<div class="page-title">';
			};
		?>
			<span class="heading-t"></span>
				<h1><?php echo esc_html($single_title); ?></h1>
			<?php
				iron_page_title_divider();
			?>
		</div>
		
		<?php else: ?>
			
			<div class="heading-space"></div>
			
		<?php endif; ?>

<?php
		if ( $has_sidebar ) :
?>
			<div id="twocolumns" class="content__wrapper<?php if ( 'left' === $sidebar_position ) echo ' content--rev'; ?>">
				<div id="content" class="content__main">
<?php
		endif;

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
?>
		<!-- single-post artist-post -->
		<div id="post-<?php the_ID(); ?>" <?php post_class('single-post artist-post'); ?>>
			
			<?php if($template == 'default'): ?>
			
			<?php
				// ---------------- temporary output -------------------------
				
				$bio = get_the_content(); 
				echo '<h4>Bio</h4> '.$bio;
				echo '<br><br>';
				
				$values = get_post_meta(get_the_ID());
				$keys = array_keys($values);
				foreach($keys as $key)
				{
					if(substr($key, 0, 1) != '_') {

						$value = get_field($key);
						
						if(!empty($value)) {
							echo '<h4>'.$key . '</h4>';
						
							if(is_array($value)) {
								foreach($value as $p) {
									if(!empty($p->post_title)) {
										echo $p->post_title.'<br>';
									}else{
										print_r($p);
									}	
								}
							}else{
								echo $value;
							}
				    	}
				    	echo '<br><br>';
				    }	
				}
				
				// ---------------- end temporary output -------------------------
			?>
			
			<?php else: ?>
			
			<div class="entry">
				<?php the_content(); ?>
			</div>
			
			<?php endif; ?>
			
		</div>
<?php
	endwhile;
endif;

if ( $has_sidebar ) :
?>
				</div>

				<aside id="sidebar" class="content__side widget-area widget-area--<?php echo esc_attr( $sidebar_area ); ?>">
<?php
	do_action('before_ironband_sidebar_dynamic_sidebar', 'single-artist.php');

	dynamic_sidebar( $sidebar_area );

	do_action('after_ironband_sidebar_dynamic_sidebar', 'single-artist.php');
?>
				</aside>
			</div>
<?php
endif;
?>
			</div>
		</div>
	
<?php get_footer(); ?>