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

	public function doShortcode() {
		return do_shortcode_tag($this->__toMatch());
	}

	public function __toString() {
		return "[" . $this->{self::PROPERTY_TAG} . 
			" " . self::formatAtts() . 
			(!is_null($this->{self::PROPERTY_CONTENT}) ? "]" . $this->{self::PROPERTY_CONTENT} . "[/" . $this->{self::PROPERTY_TAG} . "]" : " /]");
	}

	public function formatAtts($atts = NULL) {
		if (!isset($atts)) {
			if (!(isset($this) && $this instanceof self))
				throw new \ErrorException(sprintf("%s can not be called statically without attribute definition", __FUNCTION__));

			$atts = $this->{self::PROPERTY_ATTS};
		}

		$atts = (array) $atts;

		return implode(" ", array_map(function($value, $name) {
				return $name . '="' . addslashes($value)  . '"';
			}, $atts, array_keys($atts)));
	}

	/**
	 * Create a matches array like the result from the regular expression.
	 * @return array Returns an array of matches.
	 */
	public function __toMatch() {
		return array(
				1 => NULL,
				2 => $this->{self::PROPERTY_TAG},
				3 => $this->formatAtts(),
				4 => NULL,
				5 => $this->{self::PROPERTY_CONTENT},
				6 => NULL
			);
	}

	// Static
	
	public static function extract($content, $filter = NULL) {
		$shortcodes = static::__createCollection();

		if (!preg_match_all('/'. get_shortcode_regex() .'/s', $content, $matches))
			return $shortcodes;

		$filter = array_map("strtolower", (array) $filter);

		$doFilter = count($filter) > 0;

		foreach ($matches[0] as $index => $shortcode) {
			if ($doFilter && !in_array(strtolower($matches[2][$index]), $filter))
				continue;

			$shortcodes[] = new Shortcode(array(
					self::PROPERTY_TAG => $matches[2][$index],
					self::PROPERTY_ATTS => shortcode_parse_atts($matches[3][$index]),
					self::PROPERTY_CONTENT => "/" == $matches[4][$index] ? NULL : $matches[5][$index]
				));

		}

		return $shortcodes;
	}

}