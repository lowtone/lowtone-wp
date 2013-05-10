<?php
namespace lowtone\wp\shortcodes;
use lowtone\db\records\Record;

/**
 * @author Paul van der Meijs <code@paulvandermeijs.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.1
 * @package wordpress\libs\lowtone\wp\shortcodes
 */
class Shortcode extends Record {

	const PROPERTY_TAG = "tag",
		PROPERTY_ATTS = "atts",
		PROPERTY_CONTENT = "content";
	
	public static function extractShortcodes($content) {
		$shortcodes = array();

		if (!preg_match_all('/'. get_shortcode_regex() .'/s', $content, $matches))
			return array();

		foreach ($matches[0] as $index => $shortcode) {

			$shortcodes[] = new Shortcode(array(
					self::PROPERTY_TAG => $matches[2][$index],
					self::PROPERTY_ATTS => shortcode_parse_atts($matches[3][$index]),
					self::PROPERTY_CONTENT => $matches[5][$index]
				));

		}

		return $shortcodes;
	}

}