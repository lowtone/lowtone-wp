<?php
/*
 * Plugin Name: WordPress library
 * Plugin URI: http://wordpress.lowtone.nl/wp
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
	
}