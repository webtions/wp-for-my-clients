<?php
/*
Plugin Name: Embed Widget by Themeist
Plugin URI: http://themeist.co
Description: A simple but powerful widget to Embed Videos.
Version: 1.0.1
Author: themeist, hchouhan
Author URI: http://themeist.co
*/

class Themeist_Embed_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		$widget_ops = array('description' => __('Display Embed Video', 'dot') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'embed' );
		parent::__construct(
			'embed',
			__('Themeist Embed Widget', 'dot'),
			$widget_ops,
			$control_ops
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$embed = $instance['embed'];
		$description = $instance['description'];

		// ------
		echo $before_widget;
		echo $before_title . $title . $after_title;

		echo '<div class="embed_code">';
		echo $embed;
		if (!empty($description)) { echo '<p>' . $description . '</p>'; }
		echo '</div>';

		echo $after_widget;
		// ------
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	function form( $instance ) {

		$defaults = array( 'title' => 'Embed Widget', 'embed' => '', 'description' => '' ); // Default Values
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'embed' ); ?>">Embed Code:</label>
			<textarea class="widefat" rows="4" cols="20" id="<?php echo $this->get_field_id( 'embed' ); ?>" name="<?php echo $this->get_field_name( 'embed' ); ?>"><?php echo $instance['embed']; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'description' ); ?>">Description:</label>
			<textarea class="widefat" rows="2" cols="20" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"><?php echo $instance['description']; ?></textarea>
		</p>

	<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['embed'] = $new_instance['embed'];
		$instance['description'] = $new_instance['description'];

		return $instance;
	}

} // classs Themeist_Embed_Widget
?>