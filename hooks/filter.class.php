<?php
namespace lowtone\wp\hooks;
use lowtone\db\records\Record;

class Filter extends Record {

	const PROPERTY_NAME = "name";

	public function name() {
		return $this->name;
	}

	public function apply($value) {
		$args = func_get_args();

		if (isset($this) && $this instanceof Filter) {

			array_unshift($args, $this->name());

			return call_user_func_array("lowtone\\wp\\hooks\\Filter::apply", $args);
		}

		$filters = (array) $args[0];

		$value = $args[1];

		$args[0] = &$filter;
		$args[1] = &$value;

		foreach ($filters as $filter) 
			$value = call_user_func_array("apply_filters", $args);
		
		return $value;
	}
	
}