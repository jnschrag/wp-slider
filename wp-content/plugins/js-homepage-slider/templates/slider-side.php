<div id='homepage-slider-container'>
	<div class='feature-background' style='
		background-image:url("<?php echo $feat_image; ?>");
		background-position-x:<?php echo $feat_bgpos_x; ?>;
		background-position-y:<?php echo $feat_bgpos_y; ?>;
	'>
		<div class='overlay'>
			<div class='featuredItem-container'>
				<div class='featuredItem'>
					<span class='description'><?php echo $feat_description; ?></span><br />
					<?php echo $feat_title; ?><br />
					<a href='<?php echo $feat_link; ?>' class='seeMore'><?php echo __($feat_cta, 'js_slider'); ?></a>
				</div>
			</div>
			<?php wp_nav_menu( array('theme_location' => $location,'walker' => $walker,'activeID' => $feat_id, 'hideFeature' => $hideFeature) ); ?>
		</div>
	</div>
</div>