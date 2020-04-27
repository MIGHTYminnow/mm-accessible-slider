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
		?>
		<section>
			<h2>Slider #<?php echo self::$sliders_count; ?></h2>
			<figure class="mm-accessible-slider">
				<div id="slide-<?php echo self::$sliders_count; ?>" class="carousel">
					<ul class="wcag-slides">
						<?php
						for( $slide = 1; $slide <= 4; $slide++ ) {
							?>
							<li class="slide" data-thumb="https://via.placeholder.com/150">
								<div class="wcag-slide">
									<div class="dyk">
										<div class="dyk-info">
											<h3>Slide <?php echo $slide; ?></h3>
										</div>
										<div class="dyk-image">
											<img width="500" height="375" src="https://via.placeholder.com/500x375" />
										</div>
									</div>
								</div>
							</li>
							<?php
						}
						?>
					</ul>
				</div>
			</figure>
		</section>
		<?php
	}

}
