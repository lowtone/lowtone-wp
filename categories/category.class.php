<?php
namespace lowtone\wp\categories;
use lowtone\wp\posts\Post;

/**
 * @author Paul van der Meijs <code@lowtone.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\libs\lowtone\wp\categories
 */
class Category extends Post {
	
	// Static
	
	public static function loadCategory($pageId) {
		return self::loadPost($pageId);
	}
	
}