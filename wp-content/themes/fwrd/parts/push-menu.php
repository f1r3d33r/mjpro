	<div class="side-menu">
		<div class="menu-toggle-off">
			<?php echo wp_remote_retrieve_body(wp_remote_request(get_template_directory_uri().'/images/svg/close_menu_icon.svg')); ?>
		</div>
		
		<a class="site-title" rel="home" href="<?php echo esc_url(home_url('/')); ?>">
		<?php if(get_iron_option('menu_logo') != ''): ?>
			<img class="logo-desktop regular" src="<?php echo esc_url( get_iron_option('menu_logo') ); ?>" <?php echo (get_iron_option('retina_menu_logo'))? 'data-at2x="' . esc_url( get_iron_option('retina_menu_logo')) .'"':''?> alt="<?php echo esc_attr( get_bloginfo('name') ); ?>">
			<img class="logo-mobile regular" src="<?php echo esc_url( get_iron_option('menu_logo') ); ?>" <?php echo (get_iron_option('retina_menu_logo'))? 'data-at2x="' . esc_url( get_iron_option('retina_menu_logo')) .'"':''?> alt="<?php echo esc_attr( get_bloginfo('name') ); ?>">
		<?php endif; ?>
		</a>
		
		
			<!-- panel -->
			<div class="panel">
				<a class="opener" href="#"><i class="icon-reorder"></i> <?php esc_html_e("Menu", 'fwrd'); ?></a>

				<!-- nav-holder -->
				<div class="nav-holder">

					<!-- nav -->
					<nav id="nav">
	<?php if ( get_iron_option('header_menu_logo_icon') != '') : ?>
						<a class="logo-panel" href="<?php echo esc_url(home_url('/')); ?>">
							<img src="<?php echo esc_url( get_iron_option('header_menu_logo_icon') ); ?>" alt="<?php echo esc_attr( get_bloginfo('name') ); ?>">
						</a>
	<?php endif; ?>
						<?php echo wp_nav_menu( array( 'theme_location' => 'main-menu', 'menu_class' => 'nav-menu', 'echo' => false, 'fallback_cb' => '__return_false' )); ?>

					</nav>
					<div class="clear"></div>
					
					<div class="panel-networks">
						<?php get_template_part('parts/networks'); ?>
						<div class="clear"></div>
					</div>
					
				</div>
			</div>
		
	</div>