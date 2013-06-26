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

	use lowtone\content\packages\Package;

	// Includes
	
	if (!include_once WP_PLUGIN_DIR . "/lowtone-content/lowtone-content.php") 
		return trigger_error("Lowtone Content plugin is required", E_USER_ERROR) && false;

	$__i = Package::init(array(
			Package::INIT_PACKAGES => array("lowtone"),
			Package::INIT_MERGED_PATH => __NAMESPACE__,
			Package::INIT_SUCCESS => function() {

				// Fix WordPress
				
				include_once "inc/fixes.inc.php";

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

				return true;
			}
		));
	
}