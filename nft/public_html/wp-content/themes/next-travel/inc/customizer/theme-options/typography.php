<?php
/**
 * Typography options
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

// Typography Section
$wp_customize->add_section( 'next_travel_section_typography',
	array(
		'title'      		=> esc_html__( 'Typography', 'next-travel' ),
		'priority'   		=> 600,
		'panel'      		=> 'next_travel_theme_options_panel',
	)
);

$wp_customize->add_setting( 'next_travel_theme_options[theme_site_title_typography]',
	array(
		'sanitize_callback'	=> 'next_travel_sanitize_select',
	)
);
$wp_customize->add_control( 'next_travel_theme_options[theme_site_title_typography]',
    array(
		'label'       		=> esc_html__( 'Choose Site Title Typography', 'next-travel' ),
		'section'     		=> 'next_travel_section_typography',
		'settings'    		=> 'next_travel_theme_options[theme_site_title_typography]',
		'type'		  		=> 'select',
		'choices'			=> next_travel_typography_options(),
    )
);

$wp_customize->add_setting( 'next_travel_theme_options[theme_site_description_typography]',
	array(
		'sanitize_callback'	=> 'next_travel_sanitize_select',
	)
);
$wp_customize->add_control( 'next_travel_theme_options[theme_site_description_typography]',
    array(
		'label'       		=> esc_html__( 'Choose Site Description Typography', 'next-travel' ),
		'section'     		=> 'next_travel_section_typography',
		'settings'    		=> 'next_travel_theme_options[theme_site_description_typography]',
		'type'		  		=> 'select',
		'choices'			=> next_travel_typography_options(),
    )
);

$wp_customize->add_setting( 'next_travel_theme_options[theme_menu_typography]',
	array(
		'sanitize_callback'	=> 'next_travel_sanitize_select',
	)
);
$wp_customize->add_control( 'next_travel_theme_options[theme_menu_typography]',
    array(
		'label'       		=> esc_html__( 'Choose Menu Typography', 'next-travel' ),
		'section'     		=> 'next_travel_section_typography',
		'settings'    		=> 'next_travel_theme_options[theme_menu_typography]',
		'type'		  		=> 'select',
		'choices'			=> next_travel_typography_options(),
    )
);

$wp_customize->add_setting( 'next_travel_theme_options[theme_head_typography]',
	array(
		'sanitize_callback'	=> 'next_travel_sanitize_select',
	)
);

$wp_customize->add_control( 'next_travel_theme_options[theme_head_typography]',
    array(
		'label'       		=> esc_html__( 'Choose Heading Typography', 'next-travel' ),
		'section'     		=> 'next_travel_section_typography',
		'settings'    		=> 'next_travel_theme_options[theme_head_typography]',
		'type'		  		=> 'select',
		'choices'			=> next_travel_typography_options(),
    )
);

$wp_customize->add_setting( 'next_travel_theme_options[theme_body_typography]',
	array(
		'sanitize_callback'	=> 'next_travel_sanitize_select',
	)
);
$wp_customize->add_control( 'next_travel_theme_options[theme_body_typography]',
    array(
		'label'       		=> esc_html__( 'Choose Body Typography', 'next-travel' ),
		'section'     		=> 'next_travel_section_typography',
		'settings'    		=> 'next_travel_theme_options[theme_body_typography]',
		'type'		  		=> 'select',
		'choices'			=> next_travel_typography_options(),
    )
);

$wp_customize->add_setting( 'next_travel_theme_options[theme_btn_label_typography]',
	array(
		'sanitize_callback'	=> 'next_travel_sanitize_select',
	)
);
$wp_customize->add_control( 'next_travel_theme_options[theme_btn_label_typography]',
    array(
		'label'       		=> esc_html__( 'Choose Button Label Typography', 'next-travel' ),
		'section'     		=> 'next_travel_section_typography',
		'settings'    		=> 'next_travel_theme_options[theme_btn_label_typography]',
		'type'		  		=> 'select',
		'choices'			=> next_travel_typography_options(),
    )
);