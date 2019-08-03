<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo (is_admin_bar_showing())? 'wp-admin-bar':''?> ">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0">
	<?php 
	if ( ! function_exists( '_wp_render_title_tag' ) ) :
	    function theme_slug_render_title() {
	?>
	<title><?php wp_title('-', true, 'right'); ?></title>
	<?php
	    }
	    add_action( 'wp_head', 'theme_slug_render_title' );
	endif;?>
	<?php wp_head(); ?>	
</head>
<body <?php body_class("layout-wide"); ?> onload="jQuery('header').animate({'opacity': 1})">
	<?php 
	$facebook_appid = get_iron_option('facebook_appid');
	if(!empty($facebook_appid)) { ?>
		<script>
	      window.fbAsyncInit = function() {
	        FB.init({
	          appId      : '<?php echo esc_html($facebook_appid);?>',
	          xfbml      : true,
	          version    : 'v2.1'
	        });
	      };

	      (function(d, s, id){
	         var js, fjs = d.getElementsByTagName(s)[0];
	         if (d.getElementById(id)) {return;}
	         js = d.createElement(s); js.id = id;
	         js.src = "//connect.facebook.net/en_US/sdk.js";
	         fjs.parentNode.insertBefore(js, fjs);
	       }(document, 'script', 'facebook-jssdk'));
	    </script>
		<div id="fb-root"></div>
	<?php } ?>

	<div id="overlay"></div>
	
	<?php 
	$fixed_header = get_iron_option('enable_fixed_header');
	$menu_type = get_iron_option('menu_type');
	$menu_position = get_iron_option('classic_menu_position');
	$menu_is_over = get_field('classic_menu_over_content', get_the_ID());
	
	if(!empty($menu_is_over)) {
		if($menu_position == 'absolute absolute_before') {
			$menu_position = 'absolute';
		}else{
			$menu_position = 'fixed';
		}	
	}
	
	?>
	
	<?php if($menu_type == 'push-menu'): ?>
		<?php get_template_part('parts/push', 'menu'); ?>
	<?php endif; ?>


	<?php if($menu_type == 'classic-menu' && $menu_position != 'absolute' && $menu_position != 'absolute absolute_before'): ?>
	
		<?php get_template_part('parts/classic', 'menu'); ?>
	
	<?php endif; ?>
		
	<?php if(($menu_type == 'push-menu' && empty($fixed_header)) || ($menu_type == 'classic-menu' && ($menu_position == 'fixed' || $menu_position == 'fixed_before'))) : ?>	
	<div id="pusher" class="menu-type-<?php echo esc_attr($menu_type);?>">
	<?php endif; ?>
	
	<?php if($menu_type == 'push-menu'): ?>
	<header class="opacityzero">
		<div class="menu-toggle">
			<?php echo wp_remote_retrieve_body(wp_remote_request(get_template_directory_uri().'/images/svg/menu_icon.svg')); ?>
		</div>
		<?php get_template_part('parts/top-menu'); ?>

		<?php if( get_iron_option('header_logo') !== ''): ?>
		<a href="<?php echo esc_url( home_url('/'));?>" class="site-logo">
		  <img id="menu-trigger" class="logo-desktop regular" src="<?php echo esc_url( get_iron_option('header_logo') ); ?>" <?php echo (get_iron_option('retina_header_logo'))? 'data-at2x="' . esc_url( get_iron_option('retina_header_logo')) .'"':''?> alt="<?php echo esc_attr( get_bloginfo('name') ); ?>">
		</a>
		<?php endif; ?>
	</header>
	<?php endif; ?>

		
	<?php if(($menu_type == 'push-menu' && !empty($fixed_header)) || ($menu_type == 'classic-menu' && ($menu_position != 'fixed' || $menu_position == 'fixed_before'))) : ?>	
	<div id="pusher" class="menu-type-<?php echo esc_attr($menu_type);?>">
	<?php endif; ?>
	

		<?php if($menu_type == 'classic-menu' && ($menu_position == 'absolute' || $menu_position == 'absolute absolute_before') ): ?>
		
			<?php get_template_part('parts/classic', 'menu'); ?>
		
		<?php endif; ?>
	
		<div id="wrapper">
