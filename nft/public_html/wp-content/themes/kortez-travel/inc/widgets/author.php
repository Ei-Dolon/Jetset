<?php
/**
* Kortez Travel Author Widget
*
* @since Kortez Travel 1.0.0
*/
if( ! class_exists( 'kortez_travel_Author_Widget' ) ) :
    
    class kortez_travel_Author_Widget extends kortez_travel_Base_Widget {

        var $image_field = 'image';  // the image field ID

        public function __construct() {

            $widget_ops = array(
                'description' => esc_html__( 'Display your Profile Page with Social Media Links.', 'kortez-travel' ), 
                'customize_selective_refresh' => true
            );
            
            parent::__construct(
                'kortez_travel_author_widget', 
                esc_html__( 'Kortez Travel Author', 'kortez-travel' ),
                $widget_ops
            );

            $this->fields = array(
                'title' => array(
                    'label'   => esc_html__( 'Widget Title', 'kortez-travel' ),
                    'type'    => 'text',
                    'default' => esc_html__( 'About Author', 'kortez-travel' )
                ),
                'page_id' => array(
                    'label' => esc_html__( 'Select Page', 'kortez-travel' ),
                    'type'  => 'dropdown-pages',
                ),
                'full-square-thumb' => array(
                    'label'   => esc_html__( 'Enable Full Size Feature Image', 'kortez-travel' ),
                    'type'    => 'checkbox',
                    'default' => false,
                ),
                'page_title' => array(
                    'label'   => esc_html__( 'Enable Page Title', 'kortez-travel' ),
                    'type'    => 'checkbox',
                    'default' => true,
                ),
                'excerpt_count' => array(
                    'label'   => esc_html__( 'Excerpt Words count to show.', 'kortez-travel' ),
                    'type'    => 'number',
                    'default' => 20,
                ),
                'sub_title' => array(
                    'label'   => esc_html__( 'Sub Title', 'kortez-travel' ),
                    'type'    => 'text',
                    'default' => esc_html__( 'Lifestyle Blogger','kortez-travel' )
                ),
                'content_alignment' => array(
                    'label'   => esc_html__( 'Content Alignment', 'kortez-travel' ),
                    'type'    => 'select',
                    'choices' => array(
                        'center' => esc_html__( 'Center', 'kortez-travel' ),
                        'left'   => esc_html__( 'Left', 'kortez-travel' ),
                        'right'  => esc_html__( 'Right', 'kortez-travel' ),
                    ),
                ),
                'social_title' => array(
                    'label'   => esc_html__( '= Social Icons =', 'kortez-travel' ),
                    'type'    => 'description',
                ),
                 'social_desc' => array(
                    'label'   => esc_html__( 'Input Icon name. For Example:- fab fa-facebook For more icons https://fontawesome.com/icons?d=gallery&m=free', 'kortez-travel' ),
                    'type'    => 'description',
                ),
                'social_menu_icon_1' => array(
                    'label' => esc_html__( 'Social Icon 1', 'kortez-travel' ),
                    'type'  => 'text',
                ), 
                'social_menu_link_1' => array(
                    'label' => esc_html__( 'Social Icon Link 1', 'kortez-travel' ),
                    'type'  => 'text',
                ),
                'social_menu_icon_2' => array(
                    'label' => esc_html__( 'Social Icon 2', 'kortez-travel' ),
                    'type'  => 'text',
                ), 
                'social_menu_link_2' => array(
                    'label' => esc_html__( 'Social Icon Link 2', 'kortez-travel' ),
                    'type'  => 'text',
                ),
                'social_menu_icon_3' => array(
                    'label' => esc_html__( 'Social Icon 3', 'kortez-travel' ),
                    'type'  => 'text',
                ), 
                'social_menu_link_3' => array(
                    'label' => esc_html__( 'Social Icon Link 3', 'kortez-travel' ),
                    'type'  => 'text',
                ),
                'social_menu_icon_4' => array(
                    'label' => esc_html__( 'Social Icon 4', 'kortez-travel' ),
                    'type'  => 'text',
                ), 
                'social_menu_link_4' => array(
                    'label' => esc_html__( 'Social Icon Link 4', 'kortez-travel' ),
                    'type'  => 'text',
                ),
                'social_menu_icon_5' => array(
                    'label' => esc_html__( 'Social Icon 5', 'kortez-travel' ),
                    'type'  => 'text',
                ), 
                'social_menu_link_5' => array(
                    'label' => esc_html__( 'Social Icon Link 5', 'kortez-travel' ),
                    'type'  => 'text',
                ),
                'social_menu_icon_6' => array(
                    'label' => esc_html__( 'Social Icon 6', 'kortez-travel' ),
                    'type'  => 'text',
                ), 
                'social_menu_link_6' => array(
                    'label' => esc_html__( 'Social Icon Link 6', 'kortez-travel' ),
                    'type'  => 'text',
                ),
                'social_menu_icon_7' => array(
                    'label' => esc_html__( 'Social Icon 7', 'kortez-travel' ),
                    'type'  => 'text',
                ), 
                'social_menu_link_7' => array(
                    'label' => esc_html__( 'Social Icon Link 7', 'kortez-travel' ),
                    'type'  => 'text',
                ),
                'social_menu_icon_8' => array(
                    'label' => esc_html__( 'Social Icon 8', 'kortez-travel' ),
                    'type'  => 'text',
                ), 
                'social_menu_link_8' => array(
                    'label' => esc_html__( 'Social Icon Link 8', 'kortez-travel' ),
                    'type'  => 'text',
                ),
                'social_menu_icon_9' => array(
                    'label' => esc_html__( 'Social Icon 9', 'kortez-travel' ),
                    'type'  => 'text',
                ), 
                'social_menu_link_9' => array(
                    'label' => esc_html__( 'Social Icon Link 9', 'kortez-travel' ),
                    'type'  => 'text',
                ),
                'social_menu_icon_10' => array(
                    'label' => esc_html__( 'Social Icon 10', 'kortez-travel' ),
                    'type'  => 'text',
                ), 
                'social_menu_link_10' => array(
                    'label' => esc_html__( 'Social Icon Link 10', 'kortez-travel' ),
                    'type'  => 'text',
                ),

            );
        }

        public function widget( $args, $instance ) {
            
            echo $args[ 'before_widget' ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

            $instance = $this->init_defaults( $instance );
            $unique_id = uniqid();
            ?>
            <?php 
            $author_thumbnail_class = '';
            $instance[ 'page_id' ] = empty( $instance[ 'page_id' ] ) ? 2 : $instance[ 'page_id' ];
             if( $instance[ 'page_id' ] ){
                if( !$instance[ 'full-square-thumb' ] ){
                    $author_thumbnail_class = 'author-thumbnail';
                }
            }

            if( $instance[ 'content_alignment' ] == 'left' ){
                $alignment_class = 'text-left';
            }else if( $instance[ 'content_alignment' ] == 'right' ){
                $alignment_class = 'text-right';
            }else {
                $alignment_class = 'text-center';
            }
            ?>

            <section class="author-widget class-<?php echo esc_attr( $unique_id ); ?> <?php echo esc_attr( $author_thumbnail_class ); ?>">
                <?php
                if( !isset( $instance[ 'title' ] ) || empty( $instance[ 'title' ] ) ){
                    $instance[ 'title' ] = esc_html__( 'About Author', 'kortez-travel' );
                }
                echo '<div class="widget-title-wrap">' . $args[ 'before_title'] . esc_html( $instance[ 'title' ] ) . $args[ 'after_title' ] . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                $query = new WP_Query( array(
                    'p'         => $instance[ 'page_id' ],
                    'post_type' => 'page'
                ) );

                while( $query->have_posts() ){
                    $query->the_post();
                    if( $instance[ 'full-square-thumb' ] ){
                        $src = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
                    }else {
                        $src = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
                    }
                    $image_id = get_post_thumbnail_id();
                    $alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true);
                ?>
                <div class="widget-content <?php echo esc_attr( $alignment_class ); ?>">
                    <div class="profile">
                        <?php if( has_post_thumbnail() ){ ?>
                            <figure class="avatar">
                                 <a href="<?php the_permalink(); ?>">
                                    <img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr($alt); ?>">
                                 </a>
                            </figure>
                        <?php } ?>
                        <div class="text-content">
                            <div class="name-title">
                                <?php if( $instance[ 'page_title' ] == true ){ ?>
                                    <h3>
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                <?php } ?>
                                <span><?php echo esc_html( $instance[ 'sub_title' ] ); ?></span>
                            </div>
                            <?php
                                $excerpt_count = $instance[ 'excerpt_count' ];
                                kortez_travel_excerpt( $excerpt_count , true );
                            ?>
                        </div>
                        <?php if( $instance[ 'social_menu_icon_1' ] || $instance[ 'social_menu_icon_2' ] || $instance[ 'social_menu_icon_3' ] || $instance[ 'social_menu_icon_4' ] || $instance[ 'social_menu_icon_5' ] || $instance[ 'social_menu_icon_6' ] || $instance[ 'social_menu_icon_7' ] || $instance[ 'social_menu_icon_8' ] || $instance[ 'social_menu_icon_9' ] || $instance[ 'social_menu_icon_10' ] ){ ?>
                            <div class="socialgroup">
                                <ul>
                                    <?php
                                    if( $instance[ 'social_menu_icon_1' ] ){ ?>
                                        <li>
                                            <a target="_blank" href="<?php echo esc_url( $instance[ 'social_menu_link_1' ] ); ?>">
                                                <i class="<?php echo esc_attr( $instance[ 'social_menu_icon_1' ] ); ?>"></i>
                                            </a>
                                        </li>
                                    <?php }
                                    if( $instance[ 'social_menu_icon_2' ] ){
                                    ?>
                                    <li>
                                        <a target="_blank" href="<?php echo esc_url( $instance[ 'social_menu_link_2' ] ); ?>">
                                            <i class="<?php echo esc_attr( $instance[ 'social_menu_icon_2' ] ); ?>"></i>
                                        </a>
                                    </li>
                                    <?php }
                                    if( $instance[ 'social_menu_icon_3' ] ){
                                    ?>
                                    <li>
                                        <a target="_blank" href="<?php echo esc_url( $instance[ 'social_menu_link_3' ] ); ?>">
                                            <i class="<?php echo esc_attr( $instance[ 'social_menu_icon_3' ] ); ?>"></i>
                                        </a>
                                    </li>
                                    <?php }
                                    if( $instance[ 'social_menu_icon_4' ] ){
                                    ?>
                                    <li>
                                        <a target="_blank" href="<?php echo esc_url( $instance[ 'social_menu_link_4' ] ); ?>">
                                            <i class="<?php echo esc_attr( $instance[ 'social_menu_icon_4' ] ); ?>"></i>
                                        </a>
                                    </li>
                                    <?php }
                                    if( $instance[ 'social_menu_icon_5' ] ){
                                    ?>
                                    <li>
                                        <a target="_blank" href="<?php echo esc_url( $instance[ 'social_menu_link_5' ] ); ?>">
                                            <i class="<?php echo esc_attr( $instance[ 'social_menu_icon_5' ] ); ?>"></i>
                                        </a>
                                    </li>
                                    <?php }
                                    if( $instance[ 'social_menu_icon_6' ] ){
                                    ?>
                                    <li>
                                        <a target="_blank" href="<?php echo esc_url( $instance[ 'social_menu_link_6' ] ); ?>">
                                            <i class="<?php echo esc_attr( $instance[ 'social_menu_icon_6' ] ); ?>"></i>
                                        </a>
                                    </li>
                                    <?php }
                                    if( $instance[ 'social_menu_icon_7' ] ){
                                    ?>
                                    <li>
                                        <a target="_blank" href="<?php echo esc_url( $instance[ 'social_menu_link_7' ] ); ?>">
                                            <i class="<?php echo esc_attr( $instance[ 'social_menu_icon_7' ] ); ?>"></i>
                                        </a>
                                    </li>
                                    <?php }
                                    if( $instance[ 'social_menu_icon_8' ] ){
                                    ?>
                                    <li>
                                        <a target="_blank" href="<?php echo esc_url( $instance[ 'social_menu_link_8' ] ); ?>">
                                            <i class="<?php echo esc_attr( $instance[ 'social_menu_icon_8' ] ); ?>"></i>
                                        </a>
                                    </li>
                                    <?php }
                                    if( $instance[ 'social_menu_icon_9' ] ){
                                    ?>
                                    <li>
                                        <a target="_blank" href="<?php echo esc_url( $instance[ 'social_menu_link_9' ] ); ?>">
                                            <i class="<?php echo esc_attr( $instance[ 'social_menu_icon_9' ] ); ?>"></i>
                                        </a>
                                    </li>
                                    <?php }
                                    if( $instance[ 'social_menu_icon_10' ] ){
                                    ?>
                                    <li>
                                        <a target="_blank" href="<?php echo esc_url( $instance[ 'social_menu_link_10' ] ); ?>">
                                            <i class="<?php echo esc_attr( $instance[ 'social_menu_icon_10' ] ); ?>"></i>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php
                    }
                ?>
            </section>
            <?php
            
            wp_reset_postdata();
            echo $args[ 'after_widget' ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

endif;