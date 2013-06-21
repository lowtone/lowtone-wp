<?php
namespace lowtone\wp\admin\menus\collections;
use lowtone\db\records\collections\Collection as Base;

class Collection extends Base {
	
	public function __toWp() {
		return array_map(function($item) {
			return $item->__toWp();
		}, (array) $this);
	}

}