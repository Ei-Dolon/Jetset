<?php
/**
 * Activity Feeling / Activity.
 */
class Youzify_Mood {

	function __construct( ) {

		// Add Tool
		add_action( 'bp_activity_after_post_form_tools', array( $this, 'tool' ) );
		add_action( 'youzify_after_wall_post_form_textarea', array( $this, 'search_box' ) );

		// Handle Save Form Post - Ajax Request.
		add_action( 'wp_ajax_youzify_feeling_activity_get_categories', array( $this, 'get_categories_list' ) );

		// Add Posts Mood Action.
		add_filter( 'youzify_activity_post_mood', array( $this, 'action' ), 10, 2 );

	}

	/**
	 * Mood Action.
	 */
	function action( $action, $activity ) {

		// Get Post Mood
		$mood = bp_activity_get_meta( $activity->id, 'mood' );

		if ( empty( $mood ) ) {
			return $mood;
		}

		// Get Mood Categories.
		$moods = youzify_wall_mood_categories();

		if ( isset( $moods[ $mood['type'] ] ) ) {

			// Get Data.
			$data = $moods[ $mood['type'] ];

			// Get Mood Title.
			$mood_title = youzify_get_mood_feeling_emojis();

			// Get Mood Icon.
			$icon = '<i class="' . $data['icon'] . '" style="background-color:' . $data['color'] . ';"></i>';

			if ( $mood['type'] == 'feeling' ) {
				$icon = '<img class="youzify-mood-feeling-image" src="' . youzify_get_mood_emojis_image( $mood['value'] ) . '" alt="">';
			}

			// Get Mood Value.
			$mood_value = isset( $mood_title[ $mood['value'] ] ) ?  $mood_title[ $mood['value'] ] : $mood['value'];

			// HTML
			$action .= sprintf( '<span class="youzify-wall-mood">%1s %2s %3s<span>', $icon, $data['title'], $mood_value );
		}

		return apply_filters( 'youzify_activity_mood_action', $action, $icon, $data['title'], $mood_value );
	}

	/**
	 * Add Feeling/Activity Tool.
	 */
	function tool() {
		if ( apply_filters( 'youzify_enable_activity_form_mood', true ) ) { ?>
		<div class="youzify-user-mood-tool youzify-form-tool" data-youzify-tooltip="<?php _e( 'Feeling / Activity', 'youzify' ); ?>"><i class="far fa-smile"></i></div>
		<?php }
	}

	/**
	 * Search Box.
	 */
	function search_box() { ?>

		<div class="youzify-wall-list youzify-wall-feeling">

			<div class="youzify-list-selected-items youzify-feeling-selected-items">
				<input type="hidden" name="mood_type" value="">
				<div class="youzify-list-items-title youzify-feeling-title"></div>
			</div>

			<div class="youzify-list-search-form youzify-feeling-form">
				<div class="youzify-list-search-box youzify-feeling-search-box">
					<div class="youzify-list-search-container">
						<div class="youzify-list-search-icon youzify-feeling-search-icon"><i class="fas fa-search"></i></div>
						<input type="text" class="youzify-list-search-input youzify-feeling-search-input" name="mood_search" placeholder="<?php _e( 'Choose a feeling or activity !', 'youzify' ); ?>" >
						<div class="youzify-list-submit-button youzify-feeling-submit-button"><?php _e( 'Enter', 'youzify' ); ?></div>
						<div class="youzify-list-close-icon youzify-feeling-close-icon"><i class="fas fa-times"></i></div>
					</div>
				</div>
				<div class="youzify-wall-list-items youzify-wall-feeling-list"></div>
			</div>

		</div>

		<?php

	}

	/**
	 * Get User Friends.
	 */
	function get_categories_list() {

		// Get Current User Friends.
		$categories = youzify_wall_mood_categories();

		ob_start();

		if ( empty( $categories ) ) { ?>
			<div class="youzify-list-notice"><i class="fas fa-times"></i><?php _e( 'No categories found !', 'youzify' ); ?></div>
		<?php } else {

			echo '<div class="youzify-list-categories">';

			foreach ( $categories as $cat_name => $category ) { ?>

				<div class="youzify-list-item youzify-feeling-item youzify-category-<?php echo $cat_name; ?>" data-category="<?php echo $cat_name; ?>" data-category-title="<?php echo $category['title']; ?>">
					<div class="youzify-item-icon"><i class="<?php echo $category['icon']; ?>"></i></div>
					<div class="youzify-item-content">
						<div class="youzify-item-left">
							<div href=">" class="youzify-item-title"><?php echo $category['title']; ?></div>
							<div class="youzify-item-description"><?php echo $category['question']; ?></div>
						</div>
						<div class="youzify-item-right">
							<div class="youzify-item-button youzify-feeling-button"><i class="fas fa-chevron-right"></i></div>
						</div>
					</div>
				</div>

			<?php } ?>

			</div>

			<div class="youzify-list-category-items" data-category="feeling">
				<?php $feeling_emojis = youzify_get_mood_feeling_emojis(); foreach ( $feeling_emojis as $name => $title ) : ?>
					<div class="youzify-list-item youzify-feeling-item youzify-feeling-emoji-<?php echo $name; ?>" data-emoji="<?php echo $name; ?>" data-category-title="<?php echo $title; ?>">
						<div class="youzify-item-img" style="background-image: url(<?php echo youzify_get_mood_emojis_image( $name ); ?>"></div>
						<div class="youzify-item-content">
							<div class="youzify-item-left">
								<div href=">" class="youzify-item-title"><?php echo $title; ?></div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php do_action( 'youzify_mood_categories_items_list' ); ?>

		<?php }

		$content = ob_get_clean();

		wp_send_json_success( $content );

		die();
	}

}

new Youzify_Mood();