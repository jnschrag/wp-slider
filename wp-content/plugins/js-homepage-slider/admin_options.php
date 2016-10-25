<?php

function js_hps_settings_init(  ) { 

	register_setting( 'pluginPage', 'js_hps_settings' );

	add_settings_section(
		'js_hps_pluginPage_section', 
		__( 'General Settings', 'js_slider' ), 
		'', 
		'pluginPage'
	);

	add_settings_field( 
		'js_hps_fb_image', 
		__( 'Fallback Image', 'js_slider' ), 
		'js_hps_fb_image_render', 
		'pluginPage', 
		'js_hps_pluginPage_section' 
	);

	add_settings_field( 
		'js_hps_fb_color', 
		__( 'Fallback Background Color', 'js_slider' ), 
		'js_hps_fb_color_render', 
		'pluginPage', 
		'js_hps_pluginPage_section' 
	);

	add_settings_field( 
		'js_hps_default_cta', 
		__( 'Default Call to Action Text', 'js_slider' ), 
		'js_hps_default_cta_render', 
		'pluginPage', 
		'js_hps_pluginPage_section' 
	);

	add_settings_field( 
		'js_hps_include_featured', 
		__( 'Include featured item on side menu?', 'js_slider' ), 
		'js_hps_include_featured_render', 
		'pluginPage', 
		'js_hps_pluginPage_section' 
	);

	add_settings_field( 
		'js_hps_show_on_pages', 
		__( 'Display menu on:', 'js_slider' ), 
		'js_hps_show_on_pages_render', 
		'pluginPage', 
		'js_hps_pluginPage_section' 
	);

	add_settings_field( 
		'js_hps_layout_style', 
		__( 'Menu Layout Style', 'js_slider' ), 
		'js_hps_layout_style_render', 
		'pluginPage', 
		'js_hps_pluginPage_section' 
	);

	add_settings_field( 
		'js_hps_custom_css', 
		__( 'Custom CSS', 'js_slider' ), 
		'js_hps_custom_css_render', 
		'pluginPage', 
		'js_hps_pluginPage_section' 
	);


}

// Set Setting Defaults
$defaults = array(
    'js_hps_default_cta'   => 'See More',
    'js_hps_fb_color' => '#cccccc'

);

function js_hps_fb_image_render(  ) { 

	global $defaults;
	$options = wp_parse_args(get_option( 'js_hps_settings', $defaults ), $defaults);
	?>
	<input type='hidden' name='js_hps_settings[js_hps_fb_image]' value='<?php echo $options['js_hps_fb_image']; ?>' id='fb_image_input'>
    <div class='image_container'>
    	<?php
    		if($options['js_hps_fb_image']) {
                    echo "<img src='".$options['js_hps_fb_image']."' style='width:200px;height:auto;cursor:pointer;' class='choose-meta-image-button' title='Change Image' /><br />";
                    echo '<input type="button" id="remove-meta-image-button" class="button" value="Remove Image" />';
                }
        ?>
    </div>
    <input type="button" id="meta-image-button" class="button choose-meta-image-button" value="<?php _e( 'Choose or Upload an Image', 'text_domain' )?>" />
	<p class="description">If there is no featured image, this image will be used instead.</p>
	<?php

}


function js_hps_fb_color_render(  ) { 

	global $defaults;
	$options = wp_parse_args(get_option( 'js_hps_settings', $defaults ), $defaults);
	?>
	<input type='text' name='js_hps_settings[js_hps_fb_color]' value='<?php echo $options['js_hps_fb_color']; ?>' class='color-field'>
	<p class="description">If this is no featured image or a fallback image selected, this background color will be used instead.</p>
	<?php

}


function js_hps_default_cta_render(  ) { 
	global $defaults;
	$options = wp_parse_args(get_option( 'js_hps_settings', $defaults ), $defaults);

	?>
	<input type='text' name='js_hps_settings[js_hps_default_cta]' value='<?php echo $options['js_hps_default_cta']; ?>'>
	<?php

}


function js_hps_include_featured_render(  ) { 

	global $defaults;
	$options = wp_parse_args(get_option( 'js_hps_settings', $defaults ), $defaults);
	?>
	<label for='js_hps_settings[js_hps_include_featured]'>
		<input type='checkbox' name='js_hps_settings[js_hps_include_featured]' <?php checked( $options['js_hps_include_featured'], 1 ); ?> value='1'> 
		Yes
	</label>
	<p class='description'>Should the featured item also be included on the side menu?</p>
	<?php

}


function js_hps_show_on_pages_render(  ) { 

	global $defaults;
	$options = wp_parse_args(get_option( 'js_hps_settings', $defaults ), $defaults);
	?>
	<select name='js_hps_settings[js_hps_show_on_pages]'>
		<option value='all' <?php selected( $options['js_hps_show_on_pages'], 'all' ); ?>>All Pages</option>
		<option value='front' <?php selected( $options['js_hps_show_on_pages'], 'front' ); ?>>Front Page Only</option>
	</select>

<?php

}


function js_hps_layout_style_render(  ) { 

	global $defaults;
	$options = wp_parse_args(get_option( 'js_hps_settings', $defaults ), $defaults);
	?>
	<select name='js_hps_settings[js_hps_layout_style]'>
		<option value='side' <?php selected( $options['js_hps_layout_style'], 'side' ); ?>>Featured Item with Side Menu</option>
		<option value='single' <?php selected( $options['js_hps_layout_style'], 'single' ); ?>>Single Featured Item</option>
	</select>

<?php

}


function js_hps_custom_css_render(  ) { 

	global $defaults;
	$options = wp_parse_args(get_option( 'js_hps_settings', $defaults ), $defaults);
	?>
	<textarea cols='80' rows='10' name='js_hps_settings[js_hps_custom_css]'><?php echo $options['js_hps_custom_css']; ?></textarea>
 	<p class='description'>Customize the appearance of the menu to match your site's theme.</p>
	<?php

}

function js_hps_options_page(  ) { 

	?>
	<form action='options.php' method='post'>

		<h2>Home Page Slider Settings</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php

}

?>