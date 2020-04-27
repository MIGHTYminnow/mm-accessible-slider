<?php
/**
 * MM Accessible Slider Widget.
 *
 * Elementor widget that inserts an accessible slider.
 *
 * @since 2.0.0
 */
class MM_Accessible_Slider_Widget extends \Elementor\Widget_Base {

	public static $sliders_count = 0;

	/**
	 * Get widget name.
	 *
	 * Retrieve MM Accessible Slider widget name.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mm-accessible-slider';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve MM Accessible Slider widget title.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MM Accesible Slider', 'mm-accessible-slider' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve MM Accessible Slider widget icon.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-slides';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the MM Accesible Slider widget belongs to.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'mightyminnow' ];
	}

	/**
	 * Register MM Accessible Slider widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => __( 'Content', 'mm-accessible-slider' ),
			]
		);

		$post_types = get_post_types( [], 'objects' );
		$options = [];
		foreach ( $post_types as $post_type ) {
			$options[ $post_type->name ] = $post_type->label;
		}

		$this->add_control(
			'content_type',
			[
				'label' => __( 'Type of Content', 'mm-accessible-slider' ),
				'type' => Elementor\Controls_Manager::SELECT,
				'options' => [
					'posts' => __( 'All Posts', 'mm-accessible-slider' ),
					'attachments' => __( 'Post Attachments', 'mm-accessible-slider' ),
				],
				'default' => 'posts',
				'description' => '<ul>'
						. '<li>' . __( '<b>All Posts</b> creates a slide for every post entry.', 'mm-accessible-slider' ) . '</li>'
						. '<li>' . __( '<b>Post Attachments</b> creates a slide for every image attached to a specific post.', 'mm-accessible-slider' ) . '</li>'
					. '</ul>',
			]
		);

		$this->add_control(
			'post_type',
			[
				'label' => __( 'Post Type', 'mm-accessible-slider' ),
				'type' => Elementor\Controls_Manager::SELECT,
				'options' => $options,
				'default' => 'post',
				'condition' => [
					'content_type' => 'posts',
				],
			]
		);

		$this->add_control(
			'post_id',
			[
				'label' => __( 'Post ID', 'mm-accessible-slider' ),
				'type' => Elementor\Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'content_type' => 'attachments',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render MM Accessible Slider widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 2.0.0
	 * @access protected
	 */
	protected function render() {
		self::$sliders_count++;
		$settings = $this->get_settings_for_display();
		?>
		<section>
			<h2>Slider #<?php echo self::$sliders_count; ?></h2>
			<figure class="mm-accessible-slider">
				<div id="slide-<?php echo self::$sliders_count; ?>" class="carousel">
					<ul class="wcag-slides">
						<?php
						if ( 'posts' == $settings['content_type'] ) {
							$slides = new WP_Query( array(
								'post_type' => $settings['post_type'],
								'posts_per_page' => -1,
							) );
						}

						if ( 'attachments' == $settings['content_type'] ) {
							$slides = new WP_Query( array(
								'post_type' => 'attachment',
								'post_mime_type' => 'image',
								'numberposts' => -1,
								'post_status' => 'any',
								'post_parent' => $settings['post_id'],
							) );
						}

						if ( $slides->have_posts() ) {
							while ( $slides->have_posts() ) {
								$slides->the_post();
								$thumb_id = ( 'posts' == $settings['content_type'] ) ? get_post_thumbnail_id() : get_the_ID();
								$thumb_src = wp_get_attachment_image_src( $thumb_id );
								?>
								<li class="slide" data-thumb="<?php echo $thumb_src[0]; ?>">
									<div class="wcag-slide">
										<div class="dyk">
											<div class="dyk-info">
												<h3><?php the_title(); ?></h3>
											</div>
											<div class="dyk-image">
												<?php echo wp_get_attachment_image( $thumb_id, 'full' ); ?>
											</div>
										</div>
									</div>
								</li>
								<?php
							}
						}
						?>
					</ul>
				</div>
			</figure>
			<script stype="text/javascript">
			jQuery(document).ready(function($){
				var slide<?php echo self::$sliders_count; ?> = {"id":"slide-<?php echo self::$sliders_count; ?>","slidenav":true,"animate":true,"startAnimated":true,"delay":2000};
				var c = new myCarousel();
				c.init( slide<?php echo self::$sliders_count; ?> );
			});
			</script>
		</section>
		<?php
	}

}
