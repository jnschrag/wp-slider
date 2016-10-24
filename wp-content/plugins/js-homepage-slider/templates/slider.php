<div id='homepage-slider-container'>
	<div class='feature-background' style='background-image:url("<?php echo $feat_image; ?>");'>
		<div class='overlay'>
			<div class='featuredItem'>
				<span class='description'><?php echo $feat_description; ?></span><br />
				<?php echo $feat_title; ?><br />
				<a href='<?php echo $feat_link; ?>' class='seeMore'><?php echo __('See More', 'textdomain'); ?></a>
			</div>
			<?php wp_nav_menu( array('theme_location' => $location,'walker' => $walker,'activeID' => $feat_id) ); ?>
		</div>
	</div>
</div>