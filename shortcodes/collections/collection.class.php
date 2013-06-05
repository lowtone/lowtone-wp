<?php
namespace lowtone\wp\shortcodes\collections;
use lowtone\db\records\collections\Collection as Base;

/**
 * @author Paul van der Meijs <code@paulvandermeijs.nl>
 * @copyright Copyright (c) 2011-2013, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\libs\lowtone\wp\shortcodes\collections
 */
class Collection extends Base {
	
	public function __toString() {
		return implode((array) $this);
	}

}