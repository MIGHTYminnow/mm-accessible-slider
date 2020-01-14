;(function () {
	/**
	 * Run function when customizer is ready.
	 */
	wp.customize.bind('ready', function () {
		wp.customize.control('mmas_autoplay_control', function (control) {

			const toggleControl = (value) => {

				if (value == 1) {
					wp.customize.control('mmas_delay_control').toggle(true);
				} else {
					wp.customize.control('mmas_delay_control').toggle(false);
				}
			};

			toggleControl(control.setting.get());
			control.setting.bind(toggleControl);
		});
	});
})();
