<?php 
namespace lowtone\wp\admin\menus;

/**
 * @author Paul van der Meijs <code@lowtone.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\libs\lowtone\wp\admin\menus
 */
class SubMenu extends Menu {

	public function __toWp() {
		return array_combine(range(0, 3), array_slice((array) $this, 0, 4));
	}
	
}