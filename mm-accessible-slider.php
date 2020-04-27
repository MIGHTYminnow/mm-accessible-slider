<?php
/**
 * Plugin Name: MM Accessible Slider
 * Description: Accessible Slider by MIGHTYminnow
 * Plugin URI:  https://github.com/MIGHTYminnow/mm-accessible-slider
 * Version:     2.0.0-alpha
 * Author:      MIGHTYminnow
 * Author URI:  https://mightyminnow.com/
 * Text Domain: mm-accessible-slider
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main MM Accessible Slider Extension Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 2.0.0
 */
final class MM_Accessible_Slider_Extension {

	/**
	 * Plugin Version
	 *
	 * @since 2.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '2.0.0-alpha';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 2.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.9.8';

	/**
	 * Minimum PHP Version
	 *
	 * @since 2.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.3';

	/**
	 * Instance
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var MM_Accessible_Slider_Extension The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return MM_Accessible_Slider_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );

	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( 'mm-accessible-slider' );

	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// Register widget categories
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_widget_categories' ] );

		// Init widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

		// Enqueue styles
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );

		// Enqueue scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'mm-accessible-slider' ),
			'<strong>' . esc_html__( 'MM Accessible Slider', 'mm-accessible-slider' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'mm-accessible-slider' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'mm-accessible-slider' ),
			'<strong>' . esc_html__( 'MM Accessible Slider', 'mm-accessible-slider' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'mm-accessible-slider' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'mm-accessible-slider' ),
			'<strong>' . esc_html__( 'MM Accessible Slider', 'mm-accessible-slider' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'mm-accessible-slider' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Register widget categories
	 *
	 * Add custom widget categories.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public function register_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'mightyminnow',
			[
			 'title' => __( 'MIGHTYminnow', 'mm-accessible-slider' ),
			 'icon' => 'fa fa-plug',
			]
		);

	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {

		// Include Widget files
		require_once( __DIR__ . '/widgets/mm-accessible-slider.php' );

		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \MM_Accessible_Slider_Widget() );

	}

	/**
	 * Enqueue Styles
	 *
	 * Load the stylesheets on the front-end.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'mm-accessible-slider', plugins_url( 'assets/css/mm-accessible-slider.css', __FILE__ ), [], '1.0.0' );
	}

	/**
	 * Enqueue Scripts
	 *
	 * Load the javascript files on the front-end.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'mm-accessible-slider', plugins_url( 'assets/js/mm-accessible-slider.js', __FILE__ ), [ 'jquery' ], '1.0.0', true );
	}

}

MM_Accessible_Slider_Extension::instance();

add_action( 'wp_footer', function(){
?>
	<script type="text/javascript">
	<?php
	for ( $i = 1; $i <= MM_Accessible_Slider_Widget::$sliders_count; $i++ ) {
		?>
		var slide<?php echo $i; ?> = {"id":"slide-<?php echo $i; ?>","slidenav":true,"animate":true,"startAnimated":true,"delay":2000};
		var c = new myCarousel();
		c.init( slide<?php echo $i; ?> );
		<?php
	}
	?>
	</script>
<?php
}, 9999 );
