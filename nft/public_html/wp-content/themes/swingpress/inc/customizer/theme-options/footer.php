<?php
/**
 * Footer options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

// Footer Section
$wp_customize->add_section( 'swingpress_section_footer',
	array(
		'title'      			=> esc_html__( 'Footer Options', 'swingpress' ),
		'priority'   			=> 900,
		'panel'      			=> 'swingpress_theme_options_panel',
	)
);

// footer text
$wp_customize->add_setting( 'swingpress_theme_options[copyright_text]',
	array(
		'default'       		=> $options['copyright_text'],
		'sanitize_callback'		=> 'swingpress_santize_allow_tag',
		'transport'				=> 'postMessage',
	)
);
$wp_customize->add_control( 'swingpress_theme_options[copyright_text]',
    array(
		'label'      			=> esc_html__( 'Copyright Text', 'swingpress' ),
		'section'    			=> 'swingpress_section_footer',
		'type'		 			=> 'textarea',
    )
);

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'swingpress_theme_options[copyright_text]', array(
		'selector'            => '.site-info .copyright p',
		'settings'            => 'swingpress_theme_options[copyright_text]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'swingpress_copyright_text_partial',
    ) );
}


// scroll top visible
$wp_customize->add_setting( 'swingpress_theme_options[scroll_top_visible]',
	array(
		'default'       		=> $options['scroll_top_visible'],
		'sanitize_callback' => 'swingpress_sanitize_switch_control',
	)
);
$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[scroll_top_visible]',
    array(
		'label'      			=> esc_html__( 'Display Scroll Top Button', 'swingpress' ),
		'section'    			=> 'swingpress_section_footer',
		'on_off_label' 		=> swingpress_switch_options(),
    )
) );