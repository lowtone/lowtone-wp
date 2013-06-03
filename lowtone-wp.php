<?php
/*
 * Plugin Name: WordPress library
 * Plugin URI: http://wordpress.lowtone.nl/wp
 * Plugin Type: lib
 * Description: WordPress library.
 * Version: 1.0
 * Author: Lowtone <info@lowtone.nl>
 * Author URI: http://lowtone.nl
 * License: http://wordpress.lowtone.nl/license
 */

namespace lowtone\wp {

	use lowtone\Util;
	
	if (!class_exists("lowtone\\Lowtone"))
		return;

	
	Util::addMergedPath(__NAMESPACE__);
	 
	Util::call(function() {

		// Admin notices
		
		admin\notices\Notice::init();

		// Load text domain
		
		$loadTextDomain = function() {
			if (is_textdomain_loaded("lowtone_wp"))
				return;

			load_textdomain("lowtone_wp", __DIR__ . "/assets/languages/" . get_locale() . ".mo");
		};

		add_action("plugins_loaded", $loadTextDomain);

		add_action("after_setup_theme", $loadTextDomain);
	});
	
}