<?php
namespace lowtone\wp\posts\collections\out;
use lowtone\types\objects\collections\out\CollectionDocument as Base,
	lowtone\wp\posts\Post,
	lowtone\wp\posts\collections\Collection;

/**
 * @author Paul van der Meijs <code@paulvandermeijs.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\libs\lowtone\types\objects\out
 */
class CollectionDocument extends Base {
	
	public function build(array $options = NULL) {
		parent::build($options);

		$collectionElement = $this->documentElement;
		
		// Locales
		
		if ($this->getBuildOption(self::BUILD_LOCALES)) {
			$locales = array(
					"title" => __("Posts", "lowtone_wp"),
					"no_posts" => __("No posts.", "lowtone_wp")
				);

			if (NULL !== ($postType = get_post_type_object($this->itsCollection->postType()))) {

				$locales = array_merge($locales, array_filter(array(
						"title" => $postType->labels->name,
						"no_posts" => $postType->labels->not_found
					)));

			}
			
			$collectionElement
				->appendCreateElement("locales", $locales);
			
		}
		
		return $this;
	}

}