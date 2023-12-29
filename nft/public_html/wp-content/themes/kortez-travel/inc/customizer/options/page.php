<?php
/**
 * Pages Customizer options
 *
 * @package Kortez Travel
 */

/**
 * Pages
 */
Kirki::add_section( 'pages_options', array(
    'title'          => esc_html__( 'Pages', 'kortez-travel' ),
    'capability'     => 'edit_theme_options',
    'priority'       => '150',
) );

Kirki::add_field( 'kortez-travel',  array(
    'label'       => esc_html__( 'Page Title', 'kortez-travel' ),
    'type'        => 'select',
    'settings'    => 'disable_page_title',
    'section'     => 'pages_options',
    'default'     => 'disable_front_page',
    'choices'     => array(
        'disable_all_pages'   => esc_html__( 'Disable from all', 'kortez-travel' ),
        'enable_all_pages'    => esc_html__( 'Enable in all', 'kortez-travel' ),
        'disable_front_page'  => esc_html__( 'Disable from frontpage only', 'kortez-travel' ),
    ),
    'priority'    => 10,
) );

Kirki::add_field( 'kortez-travel',  array(
    'label'       => esc_html__( 'Page Title Position', 'kortez-travel' ),
    'type'        => 'select',
    'settings'    => 'page_title_position',
    'section'     => 'pages_options',
    'default'     => 'above_feature_image',
    'choices'     => array(
        'below_feature_image' => esc_html__( 'Below Feature Image', 'kortez-travel' ),
        'above_feature_image' => esc_html__( 'Top of the Page', 'kortez-travel' ),
    ),
    'priority'    => 20,
) );

Kirki::add_field( 'kortez-travel', array(
    'label'       => esc_html__( 'Feature Image', 'kortez-travel' ),
    'type'        => 'select',
    'settings'    => 'page_feature_image',
    'section'     => 'pages_options',
    'default'     => 'show_in_all_pages',
    'choices' => array(
        'show_in_all_pages'    => esc_html__( 'Show in all Pages', 'kortez-travel' ),
        'disable_in_all_pages' => esc_html__( 'Disable in all Pages', 'kortez-travel' ),
        'disable_in_frontpage' => esc_html__( 'Disable in Frontpage only', 'kortez-travel' ),
        'show_in_frontpage'    => esc_html__( 'Show in Frontpage only', 'kortez-travel' ),
    ),
    'priority'    => 30,
) );

Kirki::add_field( 'kortez-travel', array(
    'label'       => esc_html__( 'Choose Image Size', 'kortez-travel' ),
    'type'        => 'select',
    'settings'    => 'render_pages_image_size',
    'section'     => 'pages_options',
    'default'     => 'kortez-travel-1370-550',
    'placeholder' => esc_html__( 'Select Image Size', 'kortez-travel' ),
    'choices'     => kortez_travel_get_intermediate_image_sizes(),
    'priority'    => 40,
    'active_callback' => array(
        array(
            array(
                'setting'  => 'page_feature_image',
                'operator' => 'contains',
                'value'    => array( 'show_in_all_pages', 'show_in_frontpage', 'disable_in_frontpage' ),
            ),
        ),
    ),
) );

/**
 * 404 Error Page
 */
Kirki::add_section( 'error404_options', array(
    'title'          => esc_html__( '404 Page', 'kortez-travel' ),
    'capability'     => 'edit_theme_options',
    'priority'       => '160',
) );

Kirki::add_field( 'kortez-travel', array(
    'label'       => esc_html__( 'Image', 'kortez-travel' ),
    'description' => esc_html__( 'Recommended image size 360x200 pixel.', 'kortez-travel' ),
    'type'        => 'image',
    'settings'    => 'error404_image',
    'section'     => 'error404_options',
    'default'     => '',
    'choices'     => array(
        'save_as' => 'id',
    ),
    'priority'    => 10,
) );
Kirki::add_field( 'kortez-travel', array(
    'label'       => esc_html__( 'Choose Image Size', 'kortez-travel' ),
    'type'        => 'select',
    'settings'    => 'render_error404_image_size',
    'section'     => 'error404_options',
    'default'     => 'full',
    'placeholder' => esc_html__( 'Select Image Size', 'kortez-travel' ),
    'choices'     => kortez_travel_get_intermediate_image_sizes(),
    'priority'    => 20,
) );