<?php
$theme_data = wp_get_theme();
$item_uri = $theme_data->get('ThemeURI');
$name = $theme_data->get('Name');
$description = $theme_data->get('Description');
$author = $theme_data->get('Author');
$author_uri = $theme_data->get('AuthorURI');
$version = $theme_data->get('Version');
$tags = $theme_data->get('Tags');	
?>
<!-- CSS -->
<link rel="stylesheet" href="<?php echo esc_url(IRON_PARENT_URL.'/admin/assets/css/blueprint-css/screen.css'); ?>" type="text/css" media="screen, projection">
<!--[if lt IE 8]><link rel="stylesheet" href="<?php echo IRON_PARENT_URL; ?>/admin/assets/blueprint-css/ie.css" type="text/css" media="screen, projection"><![endif]-->
<link rel="stylesheet" href="<?php echo esc_url(IRON_PARENT_URL.'/admin/assets/css/blueprint-css/plugins/fancy-type/screen.css'); ?>" type="text/css" media="screen, projection">
<link rel="stylesheet" href="<?php echo esc_url(IRON_PARENT_URL.'/admin/assets/css/style.css'); ?>" type="text/css" media="screen, projection">
<link href="//fonts.googleapis.com/css?family=Monda:400,700" rel="stylesheet" type="text/css">

<script>
jQuery(document).ready(function(e) {
	jQuery('.toggle').on('click', function(e) {
		e.preventDefault();
		$toggle = jQuery(this);
		$target = jQuery($toggle.attr('href'));
		
		$target.toggle('fade', function() {
			
			if(jQuery(this).is(':hidden')) {
				$toggle.text('+');
			}else{
				$toggle.text('-');
			}
		});
	});	
});	
</script>

<div class="iron-docs">
	<div class="container">
		<br>
		<div class="top center">
			<div class="box">
				<img src="<?php echo IRON_PARENT_URL ?>/admin/assets/img/logo.png">
			</div>
		</div>
		<h1><?php echo esc_html($name); ?></h1>

		<div class="borderTop">
			<div class="span-8 colborder info prepend-1">
				<p class="prepend-top">
					<strong>
						Name: <?php echo esc_html($name); ?><br>
						Version: <?php echo esc_html($version); ?><br>
						Created: December 2015<br>
						By: <a href="<?php echo esc_url($author_uri); ?>"><?php echo esc_html($author); ?></a><br>
						<a href="mailto:support@irontemplates.com">support@irontemplates.com</a>
					</strong>
				</p>
			</div>

			<div class="span-10 last">
				<p class="prepend-top append-0">Thank you for purchasing our theme. If you need support or have any questions, you can <a target="_blank" href="http://support.irontemplates.com">contact us here</a>.</p>
			</div>
		</div>

		<hr>
		<h2>Documentation & Support</h2>
		
		<p><a target="_blank" href="http://support.irontemplates.com">Visit our online documentation</a></p>

		<hr>
		<h2>Changelog</h2>
		<div>
			<a href="<?php echo IRON_PARENT_URL ?>/changelog.txt" target="_blank">View the changelog.</a>
		</div>

	</div>
</div>