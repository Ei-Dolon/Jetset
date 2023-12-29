<?php
/**
 * Reset options
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

/**
* Reset section
*/
// Add reset enable section
$wp_customize->add_section( 'next_travel_reset_section', array(
	'title'             => esc_html__('Reset all settings','next-travel'),
	'description'       => esc_html__( 'Caution: All settings will be reset to default. Refresh the page after clicking Save & Publish.', 'next-travel' ),
) );

// Add reset enable setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[reset_options]', array(
	'default'           => $options['reset_options'],
	'sanitize_callback' => 'next_travel_sanitize_checkbox',
	'transport'			  => 'postMessage',
) );

$wp_customize->add_control( 'next_travel_theme_options[reset_options]', array(
	'label'             => esc_html__( 'Check to reset all settings', 'next-travel' ),
	'section'           => 'next_travel_reset_section',
	'type'              => 'checkbox',
) );
