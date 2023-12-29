<?php

/**
 * Poll Form
 */
class Youzify_Poll_Form {

	function __construct() {

		// Actions.
		add_action( 'bp_register_activity_actions', array( $this, 'register_wall_post_action' ) );
		add_action( 'youzify_after_wall_post_form_textarea', array( $this, 'add_activity_form_poll_fields' ) );

		// Filters.
		add_filter( 'youzify_wall_form_post_types_buttons', array( $this, 'add_activity_form_poll_button' ) );
		add_filter( 'youzify_allowed_form_post_types', array( $this, 'allowed_form_post_types' ) );

		// Save Poll Data.
		add_action( 'youzify_before_adding_wall_post', array( $this, 'validate' ) );
		add_action( 'youzify_after_adding_wall_post', array( $this, 'save' ) );

	}

	/**
	 * Validate Poll Data.
	 */
	function validate()	{

		// Get Post Type.
		$post_type = sanitize_text_field( $_POST['post_type'] );

		// Get Options.
		$form_options = get_option(
			'youzify_poll_form_options',
			array(
				'options_limit' 	   => '5',
				'multi_options' 	   => 'on',
				'options_selection'    => 'single',
				'options_image' 	   => 'off',
				'options_image_enable' => 'on',
			)
		);

		// Get Post Type.
		if ( $post_type == 'activity_poll' ) {

			// Show Error Empty.
			if ( empty( sanitize_text_field( $_POST['question'] ) ) ) {
				$this->show_error( __( 'Please enter a question.', 'youzify' ) );
			}

			// Get Poll Options Count
			$count = isset( $_POST['poll_options'] ) && is_array( $_POST['poll_options'] ) ? count( $_POST['poll_options'] ) : 0;

			if ( $count < 2 ) {

				// Get %inimum Options Number.
				$min_options = 2;

				// Show Error Option Limit.
				$this->show_error( sprintf( __( 'Please enter at least %d options.', 'youzify' ), $min_options ) );

			}

			// Show Error Option Limit.
			if ( $count > $form_options['options_limit'] ) {
				$this->show_error( sprintf( __( 'Sorry, max number of allowed options is %d.', 'youzify' ), $form_options['options_limit'] ) );
			}

			foreach ( $_POST['poll_options'] as $poll_option ) {
				// Check All Options.
				if ( empty( $poll_option['option'] ) ) {
					$this->show_error( __( 'Sorry, all options needs to be filled.', 'youzify' ) );
				}
			}

			if ( $form_options['options_image'] == 'on' ) {
				// Check All Attachment.
				foreach ( $_POST['poll_options'] as $poll_option ) {
					if ( empty( $poll_option['attachment'] ) ) {
						$this->show_error( __( 'Sorry, all poll images are required.', 'youzify' ) );

					}
				}
			}

		}
	}

	/**
	 * Save Poll Data.
	 */
	function save( $activity_id ) {

		// Get Form Option.
		$form_options = get_option(
			'youzify_poll_form_options',
			array(
				'options_limit' 	   => '5',
				'multi_options' 	   => 'on',
				'options_selection'    => 'single',
				'options_image' 	   => 'off',
				'options_image_enable' => 'on',
			)
		);

		// Get Activity.
		$activity = new BP_Activity_Activity( $activity_id );

		// Check All Attachment.
		foreach( $_POST['poll_options'] as $key => $attachment ) {

			// Check All Attachment.
			if ( isset( $attachment['attachment'] ) && ! empty( $attachment['attachment'] ) ) {

				// Get ID Image.
				$image = apply_filters( 'youzify_upload_activity_meta_attachments', $attachment['attachment'], $activity_id, $activity->component );

				if ( ! empty ( $image ) ) {
					foreach ( $image as $image_id => $value ) {
						// Set ID Image
					 	$_POST['poll_options'][ $key ]['attachment'] = $image_id;
					}
				}

			}

		}

		if ( $form_options['multi_options'] == 'off' ) {

			// Check if Multi Option is Allowed.
			$_POST['allow_multiple_options'] = $form_options['options_selection'] == 'multi' ? 1 : 0;

		} else {

			// Check If Multi Option is Checked.
			$_POST['allow_multiple_options'] = isset( $_POST['allow_multiple_options'] ) ? 1 : 0;

		}
		

		// Get Poll Data.
		$poll_data = array(
			'question' => $_POST['question'],
			'poll_options' => $_POST['poll_options'],
			'allow_multiple_options' => $_POST['allow_multiple_options']
		);

		// Save Poll Data.
		bp_activity_update_meta( $activity_id , 'youzify_poll_element' , $poll_data );

	}

	/**
	 * Add poll hidden Form the activity stream form.
	 */
	function add_activity_form_poll_fields() { 

		$form_options = get_option(
			'youzify_poll_form_options',
			array(
				'options_limit' 	   => '5',
				'multi_options' 	   => 'on',
				'options_selection'    => 'single',
				'options_image' 	   => 'off',
				'options_image_enable' => 'on',
			)
		);

		// Hide Images.
		$image_hidden = $form_options['options_image_enable'] == 'on' ? '' : 'display:none';

		?>

		<style type="text/css">
			.youzify-cf-attachment{
				<?php echo $image_hidden; ?>
			}
		</style>

		<div class="youzify-wall-custom-form youzify-wall-poll-form" data-post-type="activity_poll">

		    <div class="youzify-wall-cf-item">
		        <input type="text" class="youzify-wall-cf-input" name="question"
		            placeholder="<?php _e( 'Ask your question here!', 'youzify' ); ?>" />
		    </div>

		    <div class="youzify-option-holder">
		        <div class="youzify-wall-cf-item youzify-wall-cf-dragable-item youzify-allow-image-upload">
		            <i class="fas fa-expand-arrows-alt youzify-wall-form-drag-item"></i>
		            <?php if ( $form_options['options_image_enable'] == 'on' ): ?>
			            <div class="youzify-attachments youzify-poll-attachment youzify-cf-attachment" data-name="poll_options[1][attachment]">
			            	<i class="fas fa-upload youzify-wall-item-upload youzify-current-bg-color"></i>
							<input hidden="true" class="youzify-upload-attachments" type="file" name="poll_options[1][attachment]">
							<div class="youzify-form-attachments"></div>
						</div>
		            <?php endif; ?>
		            <input type="text" class="youzify-wall-cf-input" value="" name="poll_options[1][option]"
		                placeholder="<?php _e( 'Option 1', 'youzify' ); ?>" />
		            <i class="fas fa-trash-alt youzify-wall-form-remove-item"></i>
		        </div>

		        <div class="youzify-wall-cf-item youzify-wall-cf-dragable-item youzify-allow-image-upload">
		            <i class="fas fa-expand-arrows-alt youzify-wall-form-drag-item"></i>
		            <?php if ( $form_options['options_image_enable'] == 'on' ): ?>
		            <div class="youzify-attachments youzify-poll-attachment youzify-cf-attachment" data-name="poll_options[2][attachment]" >
		            	<i class="fas fa-upload youzify-wall-item-upload youzify-current-bg-color"></i>
						<input hidden="true" class="youzify-upload-attachments" type="file" name="poll_options[2][attachment]">
						<div class="youzify-form-attachments"></div>
					</div>
					<?php endif; ?>
		            <input type="text" class="youzify-wall-cf-input" value="" name="poll_options[2][option]"
		                placeholder="<?php _e( 'Option 2', 'youzify' ); ?>" />
		            <i class="fas fa-trash-alt youzify-wall-form-remove-item"></i>
		        </div>
		    </div>

		    <div class="youzify-wall-cf-item youzify-add-new-option" data-options-limit="<?php echo $form_options['options_limit']; ?>"><span class="youzify-add-new-option-title"><?php _e( 'Add Option', 'youzify' ); ?></span></div>

		   	<?php if ( $form_options['multi_options'] == 'on' ) : ?>
		    <div class="youzify-wall-cf-item">
			        <label class="youzify-cf-option-item">
			            <input type="checkbox" class="youzify-cf-option-input youzify-cf-checkbox-input youzify-current-checked-bg-color"
			                name="allow_multiple_options" <?php echo $form_options['options_selection'] == 'multi' ? 'checked': ''; ?> />
			            <span class="youzify-cf-option-title"><?php _e( 'Allow people to choose multiple answers', 'youzify' ); ?></span>
			        </label>
		    </div>
		    <?php endif; ?>

		</div>

		<?php

	}

	/**
	 * Add "Poll" button to the activity stream form.
	 */
	function add_activity_form_poll_button( $post_types ) {

		// Button Data.
		$post_types['activity_poll'] = array(
			'uploader' => 'off',
			'icon' 	=> 'fas fa-poll-h',
			'name'  => __( 'Poll', 'youzify' )
		);

		return $post_types;
	}

	/**
	 * Add "poll" To the allowed form post types.
	 */
	function allowed_form_post_types( $post_types ) {
		$post_types[] = 'activity_poll';
		return $post_types;
	}

	/**
	 * Register Wall Poll Action.
	 */
	function register_wall_post_action() {

		// Init Vars
		$bp = buddypress();

		bp_activity_set_action(
			$bp->activity->id,
			'activity_poll',
			__( 'Added a new poll', 'youzify' ),
			'youzify_activity_action_wall_posts',
			__( 'Poll', 'youzify' ),
			array( 'activity', 'group', 'member', 'member_groups' )
		);
	}

	/**
	 * Display Form Poup
	 */
	function show_error( $msg ) {
		wp_send_json_error( array( 'error' => $msg ) );
	}

}

new Youzify_Poll_Form;