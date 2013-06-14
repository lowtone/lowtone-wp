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

	private static $__added = array();

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
	public function __add($priority = 10, $type = "filter", &$hooks = NULL) {
		if (($maxAdds = $this->__maxAdds()) > -1 && $this->__added() >= $maxAdds)
			return $this;

		$rc = new ReflectionClass(get_called_class());

		$func = "add_{$type}";

		$hooks = array();

		$priorities = (array) $this->__priorities();

		foreach ($rc->getMethods(ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED) as $method) {
			if ($method->isStatic())
				continue;

			if ("_" == $method->name[0])
				continue;

			$p = isset($priorities[$method->name]) ? $priorities[$method->name] : $priority;

			/*
			 * The Handler instance is added callback hook, not a direct 
			 * reference to the method, because the instance has access to 
			 * protected methods through its public __invoke() method.
			 */

			$func($method->name, $this, $p, $method->getNumberOfParameters());

			$hooks[] = $method->name;
		}

		self::$__added[$class = get_called_class()] = isset(self::$__added[$class]) ? ++self::$__added[$class] : 1;

		return $this;
	}

	/**
	 * Get the number of times hooks where regeistered for the called class.
	 * @return int Returns the number of times Handler::__add() was called.
	 */
	public function __added() {
		return isset(self::$__added[$class = get_called_class()]) ? (int) self::$__added[$class] : 0;
	}

	/**
	 * Get the maximum number of times hooks can be added for the called class.
	 * @return int Returns the maximum number of times hooks can be added.
	 */
	public function __maxAdds() {
		return 1;
	}

	/**
	 * Provide a custom list off priorities for each hook.
	 * @return array Returns a list of hook names and their priorities.
	 */
	public function __priorities() {
		return array();
	}

}