<?php 
namespace lowtone\wp\admin\menus;
use lowtone\db\records\Record;

/**
 * @author Paul van der Meijs <code@lowtone.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\libs\lowtone\wp\admin\menus
 */
class Menu extends Record {

	private static $indexes = array(
			0 => self::PROPERTY_TITLE,
			1 => self::PROPERTY_CAPABILITY,
			2 => self::PROPERTY_SLUG,
			3 => self::PROPERTY_PAGE_TITLE,
			4 => self::PROPERTY_CLASS,
			5 => self::PROPERTY_HOOK_NAME,
			6 => self::PROPERTY_ICON_URL,
		);
	
	const PROPERTY_TITLE = "title",
		PROPERTY_CAPABILITY = "capability",
		PROPERTY_SLUG = "slug",
		PROPERTY_PAGE_TITLE = "page_title",
		PROPERTY_CLASS = "class",
		PROPERTY_HOOK_NAME = "hook_name",
		PROPERTY_ICON_URL = "icon_url";

	public function offsetGet($index) {
		return parent::offsetGet(static::__translateIndex($index));
	}

	public function offsetSet($index, $newval) {
		return parent::offsetSet(static::__translateIndex($index), $newval);
	}

	public function submenu() {
		$submenus = isset($GLOBALS["submenu"]) ? $GLOBALS["submenu"] : array();

		$submenu = isset($submenus[$this->{self::PROPERTY_SLUG}]) ? $submenus[$this->{self::PROPERTY_SLUG}] : array();

		return Submenu::__createCollection($submenu);
	}

	public function __translateIndex($index) {
		if (!isset(self::$indexes[$index]))
			return $index;

		return self::$indexes[$index];
	}

	public function __toWp() {
		return array_combine(range(0, 6), (array) $this);
	}

	// Static

	public static function get() {
		return self::__createCollection(isset($GLOBALS["menu"]) ? $GLOBALS["menu"] : array());
	}

	public static function __getCollectionClass() {
		return __NAMESPACE__ . "\\collections\\Collection";
	}
	
}