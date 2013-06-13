<?php
namespace lowtone\wp\posts\collections;
use lowtone\db\records\collections\Collection as Base,
	lowtone\wp\posts\Post;

/**
 * @author Paul van der Meijs <code@lowtone.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\libs\lowtone\wp\posts\collections
 */
class Collection extends Base {

	public function offsetGet($index) {
		if (is_string($index))
			return reset($this->find(array(Post::PROPERTY_POST_NAME => $index)));

		return parent::offsetGet($index);
	}

	public function postType() {
		foreach ($this as $object) {
			if (!($object instanceof Post))
				continue;

			return $object->{Post::PROPERTY_POST_TYPE};
		}

		return NULL;
	}

	// Static

	public static function __getObjectClass() {
		return "lowtone\\wp\\posts\\Post";
	}
	
	public static function __getDocumentClass() {
		return __NAMESPACE__ . "\\out\\CollectionDocument";
	}

}