<?php
/*
Plugin Name: Contact Widget by Themeist
Plugin URI: http://themeist.co
Description: A simple but powerful widget to display Contact details.
Version: 1.0.1
Author: themeist, hchouhan
Author URI: http://themeist.co
*/

class Themeist_Contact_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	function __construct() {
		$widget_ops = array('description' => __('Display your Contact Informations', 'dot') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'contact' );
		parent::__construct(
			'contact',
			__('Themeist Contact Card', 'dot'),
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
	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		// ------
		echo $before_widget;
		echo $before_title . $title . $after_title;
		?>

		<address>
			<?php if($instance['address']): ?>
			<span class="address"><?php echo $instance['address']; ?></span>
			<?php endif; ?>

			<?php if($instance['phone']): ?>
			<span class="phone"><strong><?php _e( 'Phone', 'dot' ) ?>:</strong> <?php echo $instance['phone']; ?></span>
			<?php endif; ?>

			<?php if($instance['fax']): ?>
			<span class="fax"><strong><?php _e( 'Fax', 'dot' ) ?>:</strong> <?php echo $instance['fax']; ?></span>
			<?php endif; ?>

			<?php if($instance['email']): ?>
			<span class="email"><strong><?php _e( 'E-Mail', 'dot' ) ?>:</strong> <a href="mailto:<?php echo $instance['email']; ?>"><?php echo $instance['email']; ?></a></span>
			<?php endif; ?>

			<?php if($instance['web']): ?>
			<span class="web"><strong><?php _e( 'Web', 'dot' ) ?>:</strong> <a href="<?php echo $instance['web']; ?>"><?php echo $instance['web']; ?></a></span>
			<?php endif; ?>
		</address>

		<?php
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
	public function form( $instance ) {

		$defaults = array('title' => 'Contact Info', 'address' => '', 'phone' => '', 'fax' => '', 'email' => '', 'web' => '');
		$instance = wp_parse_args((array) $instance, $defaults);
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('address'); ?>">Address:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" value="<?php echo $instance['address']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('phone'); ?>">Phone:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" value="<?php echo $instance['phone']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('fax'); ?>">Fax:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('fax'); ?>" name="<?php echo $this->get_field_name('fax'); ?>" value="<?php echo $instance['fax']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('email'); ?>">Email:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" value="<?php echo $instance['email']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('web'); ?>">Website URL:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('web'); ?>" name="<?php echo $this->get_field_name('web'); ?>" value="<?php echo $instance['web']; ?>" />
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
		$instance['title'] = $new_instance['title'];
		$instance['address'] = $new_instance['address'];
		$instance['phone'] = $new_instance['phone'];
		$instance['fax'] = $new_instance['fax'];
		$instance['email'] = $new_instance['email'];
		$instance['web'] = $new_instance['web'];
		return $instance;
	}

} // class Themeist_Contact_Widget
?>