<?php
namespace lowtone\wp\hooks;
use ReflectionClass,
	ReflectionMethod;

/**
 * @author Paul van der Meijs <code@lowtone.nl>
 * @copyright Copyright (c) 2011-2013, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\libs\lowtone\wp\hooks
 */
abstract class Handler {

	public function __invoke() {
		global $wp_current_filter;

		if (!($hook = end($wp_current_filter))) 
			return trigger_error("Hook handler called outside filter or action", E_USER_WARNING) && false;

		if (method_exists($this, $hook)) 
			return call_user_func_array(array($this, $hook), func_get_args());

		trigger_error(sprintf("Call to undefined hook handler %s::%s()", get_called_class(), $hook), E_USER_WARNING);

		return func_get_arg(0);
	}

	/**
	 * Register all hooks for the class.
	 * @param int $priority Specifies a priority for the execution of the 
	 * method. Defaults to 10.
	 * @param string $type Whether to add an action or filter. Defaults to 
	 * filter since WordPress adds actions as filters anyway.
	 * @return array Returns a list of added hooks.
	 */
	public function __add($priority = 10, $type = "filter") {
		$rc = new ReflectionClass(get_called_class());

		$hooks = array();

		foreach ($rc->getMethods() as $method) {
			if ("_" == $method->name[0])
				continue;

			$func = "add_{$type}";

			$func($method->name, $this, $priority, $method->getNumberOfParameters());

			$hooks[] = $method->name;
		}

		return $hooks;
	}

}