<?php
/*
Plugin Name: Flickr Widget by Themeist
Plugin URI: http://themeist.co
Description: A simple but powerful widget to display Flickr Feed.
Version: 1.0.0
Author: themeist, hchouhan
Author URI: http://themeist.co
*/

class Themeist_Flickr_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		$widget_ops = array('description' => __('Display your latest Flickr Photos', 'dot') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'flickr' );
		parent::__construct(
			'flickr',
			__('Themeist Flickr Gallery', 'dot'),
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
		$username = $instance['username'];
		$pics = $instance['pics'];

		// ------
		echo $before_widget;
		echo $before_title . $title . $after_title;

		echo '<div id="flickr_tab" class="clearfix">';
		echo '<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count='.$pics.'&display=latest&size=s&layout=x&source=user&user='.$username.'"></script>';
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

		$defaults = array( 'title' => 'Flickr Widget', 'pics' => '9', 'username' => '77282389@N05' ); // Default Values
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>">Flickr ID:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username']; ?>" /><br /><a href="<?php _e('http://idgettr.com/', 'dot'); ?>" target="_blank"><?php _e('Flickr idGettr', 'dot'); ?></a>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'pics' ); ?>">Number of Photos:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'pics' ); ?>" name="<?php echo $this->get_field_name( 'pics' ); ?>" value="<?php echo $instance['pics']; ?>" />
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
		$instance['username'] = strip_tags( $new_instance['username'] );
		$instance['pics'] = strip_tags( $new_instance['pics'] );

		return $instance;
	}

} // class Themeist_Flickr_Widget
?>