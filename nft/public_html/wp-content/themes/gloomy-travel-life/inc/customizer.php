<?php

function gloomy_travel_life_customize_register( $wp_customize ) {

	class Gloomy_Travel_Life_Switch_Control extends WP_Customize_Control{

		public $type = 'switch';

		public $on_off_label = array();

		public function __construct( $manager, $id, $args = array() ){
	        $this->on_off_label = $args['on_off_label'];
	        parent::__construct( $manager, $id, $args );
	    }

		public function render_content(){
	    ?>
		    <span class="customize-control-title">
				<?php echo esc_html( $this->label ); ?>
			</span>

			<?php if( $this->description ){ ?>
				<span class="description customize-control-description">
				<?php echo wp_kses_post( $this->description ); ?>
				</span>
			<?php } ?>

			<?php
				$switch_class = ( $this->value() == 'true' ) ? 'switch-on' : '';
				$on_off_label = $this->on_off_label;
			?>
			<div class="onoffswitch <?php echo esc_attr( $switch_class ); ?>">
				<div class="onoffswitch-inner">
					<div class="onoffswitch-active">
						<div class="onoffswitch-switch"><?php echo esc_html( $on_off_label['on'] ) ?></div>
					</div>

					<div class="onoffswitch-inactive">
						<div class="onoffswitch-switch"><?php echo esc_html( $on_off_label['off'] ) ?></div>
					</div>
				</div>	
			</div>
			<input <?php $this->link(); ?> type="hidden" value="<?php echo esc_attr( $this->value() ); ?>"/>
			<?php
	    }
	}

	class Gloomy_Travel_Life_Dropdown_Chooser extends WP_Customize_Control{

		public $type = 'dropdown_chooser';

		public function render_content(){
			if ( empty( $this->choices ) )
	                return;
			?>
	            <label>
	                <span class="customize-control-title">
	                	<?php echo esc_html( $this->label ); ?>
	                </span>

	                <?php if($this->description){ ?>
		            <span class="description customize-control-description">
		            	<?php echo wp_kses_post($this->description); ?>
		            </span>
		            <?php } ?>

	                <select class="gloomy-travel-life-chosen-select" <?php $this->link(); ?>>
	                    <?php
	                    foreach ( $this->choices as $value => $label )
	                        echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . esc_html( $label ) . '</option>';
	                    ?>
	                </select>
	            </label>
			<?php
		}
	}


	$wp_customize->remove_section( 'colors' );

	// Add Blog section
	$wp_customize->add_section( 'travel_life_blog_section', array(
		'title'             => esc_html__( 'Blog','gloomy-travel-life' ),
		'description'       => esc_html__( 'Blog Section options.', 'gloomy-travel-life' ),
		'panel'             => 'travel_life_front_page_panel',
		'priority'			=> 163,
	) );

	// Blog content enable control and setting
	$wp_customize->add_setting( 'gloomy_travel_life_blog_section_enable', array(
		'default'			=> 	false,
		'sanitize_callback' => 'travel_life_sanitize_switch_control',
	) );

	$wp_customize->add_control( new Gloomy_Travel_Life_Switch_Control( $wp_customize, 'gloomy_travel_life_blog_section_enable', array(
		'label'             => esc_html__( 'Blog Section Enable', 'gloomy-travel-life' ),
		'section'           => 'travel_life_blog_section',
		'on_off_label' 		=> travel_life_switch_options(),
	) ) );

	if ( isset( $wp_customize->selective_refresh ) ) {
	    $wp_customize->selective_refresh->add_partial( 'gloomy_travel_life_blog_section_enable', array(
			'selector'            => '#latest-posts .tooltiptext',
			'settings'            => 'gloomy_travel_life_blog_section_enable',
	    ) );
	}


	// Blog title setting and control
	$wp_customize->add_setting( 'gloomy_travel_life_blog_title', array(
		'sanitize_callback' => 'sanitize_text_field',
		'default'			=> '',
	) );

	$wp_customize->add_control( 'gloomy_travel_life_blog_title', array(
		'label'           	=> esc_html__( 'Section Title', 'gloomy-travel-life' ),
		'section'        	=> 'travel_life_blog_section',
		'active_callback' 	=> 'gloomy_travel_life_is_blog_section_enable',
		'type'				=> 'text',
	) );

	for ( $i = 1; $i <= 4; $i++ ) :
		// Blog pages drop down chooser control and setting
		$wp_customize->add_setting( 'gloomy_travel_life_blog_content_post_' . $i, array(
			'sanitize_callback' => 'travel_life_sanitize_page',
		) );

		$wp_customize->add_control( new Gloomy_Travel_Life_Dropdown_Chooser( $wp_customize, 'gloomy_travel_life_blog_content_post_' . $i, array(
			'label'             => sprintf( esc_html__( 'Select Post %d', 'gloomy-travel-life' ), $i ),
			'section'           => 'travel_life_blog_section',
			'choices'			=> travel_life_post_choices(),
			'active_callback'	=> 'gloomy_travel_life_is_blog_section_enable',
		) ) );
	
	endfor;

}
add_action( 'customize_register', 'gloomy_travel_life_customize_register' );


function gloomy_travel_life_is_blog_section_enable( $control ) {
	return ( $control->manager->get_setting( 'gloomy_travel_life_blog_section_enable' )->value() );
}


