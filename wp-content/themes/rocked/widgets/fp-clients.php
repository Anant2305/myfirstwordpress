<?php

class Rocked_Clients extends WP_Widget {

    function __construct() {
		$widget_ops = array('classname' => 'rocked_clients_widget', 'description' => __( 'Display your clients list.', 'rocked') );
        parent::__construct(false, $name = __('Rocked FP: Clients', 'rocked'), $widget_ops);
		$this->alt_option_name = 'rocked_clients_widget';
		
    }
	
	function form($instance) {
		$title     		= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    		= isset( $instance['number'] ) ? intval( $instance['number'] ) : -1;
		$category   	= isset( $instance['category'] ) ? esc_attr( $instance['category'] ) : '';
		$see_all   		= isset( $instance['see_all'] ) ? esc_url_raw( $instance['see_all'] ) : '';
		$see_all_text  	= isset( $instance['see_all_text'] ) ? esc_html( $instance['see_all_text'] ) : '';		
	?>

	<p><?php _e('In order to display this widget, you must first add some clients from your admin area. Set your client logos as featured images.', 'rocked'); ?></p>
	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'rocked'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of clients to show (-1 shows all of them):', 'rocked' ); ?></label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
    <p><label for="<?php echo $this->get_field_id('see_all'); ?>"><?php _e('The URL for your button [In case you want a button below your clients block]', 'rocked'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'see_all' ); ?>" name="<?php echo $this->get_field_name( 'see_all' ); ?>" type="text" value="<?php echo $see_all; ?>" size="3" /></p>	
    <p><label for="<?php echo $this->get_field_id('see_all_text'); ?>"><?php _e('The text for the button [Defaults to <em>See all our clients</em> if left empty]', 'rocked'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'see_all_text' ); ?>" name="<?php echo $this->get_field_name( 'see_all_text' ); ?>" type="text" value="<?php echo $see_all_text; ?>" size="3" /></p>		
	<p><label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Enter the slug for your category or leave empty to show all clients.', 'rocked' ); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" type="text" value="<?php echo $category; ?>" size="3" /></p>
		
	<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] 			= strip_tags($new_instance['title']);
		$instance['number'] 		= strip_tags($new_instance['number']);
		$instance['see_all'] 		= esc_url_raw( $new_instance['see_all'] );	
		$instance['see_all_text'] 	= strip_tags($new_instance['see_all_text']);
		$instance['category'] 		= strip_tags($new_instance['category']);		

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['rocked_clients']) )
			delete_option('rocked_clients');		  
		  
		return $instance;
	}

	// display widget
	function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'rocked_clients', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();
		extract($args);

		$title 			= ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$title 			= apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$see_all 		= isset( $instance['see_all'] ) ? esc_url($instance['see_all']) : '';
		$see_all_text 	= isset( $instance['see_all_text'] ) ? esc_html($instance['see_all_text']) : '';
		$number 		= ( ! empty( $instance['number'] ) ) ? intval( $instance['number'] ) : -1;
		if ( ! $number ) {
			$number = -1;
		}			
		$category 		= isset( $instance['category'] ) ? esc_attr($instance['category']) : '';

		$clients = new WP_Query( array(
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'post_type' 		  => 'clients',
			'posts_per_page'	  => $number,
			'category_name'		  => $category
		) );
		
		echo $args['before_widget'];

		if ($clients->have_posts()) :
?>


			<?php if ( $title ) echo $before_title . $title . $after_title; ?>
				<div class="roll-clients">
				<?php while ( $clients->have_posts() ) : $clients->the_post(); ?>
					<?php $link = get_post_meta( get_the_ID(), 'wpcf-client-link', true ); ?>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="client-item">
							<?php if ($link) : ?>
								<a href="<?php echo esc_url($link); ?>"><?php the_post_thumbnail(); ?></a>
							<?php else : ?>
								<?php the_post_thumbnail('rocked-small-thumb'); ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				<?php endwhile; ?>
				</div>		

			<?php if ($see_all != '') : ?>
				<a href="<?php echo esc_url($see_all); ?>" class="roll-button more-button">
					<?php if ($see_all_text) : ?>
						<?php echo $see_all_text; ?>
					<?php else : ?>
						<?php echo __('See all our clients', 'rocked'); ?>
					<?php endif; ?>
				</a>
			<?php endif; ?>	


	<?php
	
		echo $args['after_widget'];
		wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'rocked_clients', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}
	
}