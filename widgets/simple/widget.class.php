<?php
namespace lowtone\wp\widgets\simple;
use WP_Widget_Factory,
	lowtone\types\strings\String,
	lowtone\ui\forms\Form,
	lowtone\ui\forms\Input,
	lowtone\ui\forms\base\FormElement,
	lowtone\wp\widgets\Widget as Base;

/**
 * Simple Widget
 * The Simple Widget class provides a way to create anonymous widgets in a 
 * similar manner as anonymous functions are created.
 * 
 * @author Paul van der Meijs <code@lowtone.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\libs\lowtone\wp\widgets\simple
 */
final class Widget extends Base {

	/**
	 * @var Form|callable
	 */
	protected $itsForm;

	/**
	 * @var callable
	 */
	protected $itsUpdate;
		
	/**
	 * @var callable
	 */
	protected $itsWidget;

	const PROPERTY_FORM = "form",
		PROPERTY_UPDATE = "update",
		PROPERTY_WIDGET = "widget";

	public function __construct(array $properties = NULL) {
		$properties = (array) $properties;

		if (!(isset($properties[self::PROPERTY_NAME]) && ($name = trim($properties[self::PROPERTY_NAME])))) {
			trigger_error("Simple Widget created without a name", E_USER_NOTICE);

			$name = static::__createName();
		}

		if (!(isset($properties[self::PROPERTY_ID]) && ($id = trim($properties[self::PROPERTY_ID]))))
			$id = static::__createId($name);

		$args = array_diff_key($properties, array_flip(array(
				self::PROPERTY_ID, 
				self::PROPERTY_NAME, 
				self::PROPERTY_FORM, 
				self::PROPERTY_UPDATE, 
				self::PROPERTY_WIDGET
			)));

		parent::__construct($id, $name, $args);

		if (isset($properties[self::PROPERTY_FORM]))
			$this->itsForm = $properties[self::PROPERTY_FORM];

		if (isset($properties[self::PROPERTY_UPDATE]))
			$this->itsUpdate = $properties[self::PROPERTY_UPDATE];

		if (isset($properties[self::PROPERTY_WIDGET]))
			$this->itsWidget = $properties[self::PROPERTY_WIDGET];

	}
	
	public function form(array $instance = NULL) {
		$form = $this->itsForm;

		if (is_callable($form))
			$form = call_user_func($form, $instance, $this);

		if (!($form instanceof Form))
			return true;

		$form = clone $form;

		$widget = $this;
		
		$form
			->setValues($instance)
			->walkChildren(function($element) use ($widget) {
				if (!($element instanceof Input))
					return;
				
				$element[Input::PROPERTY_NAME] = $widget->get_field_name($element[Input::PROPERTY_NAME]);
			})
			->out(array(
				"template" => realpath(LIB_DIR . "/lowtone/ui/forms/templates/form-content.xsl")
			));

		return true;
	}
	
	public function update(array $newInstance, array $oldInstance) {
		if (!is_callable($this->itsUpdate))
			return $newInstance;

		$args = func_get_args();

		$args[] = $this;

		return call_user_func_array($this->itsUpdate, $args);
	}
	
	public function widget(array $args, array $instance = NULL) {
		if (!is_callable($this->itsWidget))
			return true;

		$args = func_get_args();

		$args[] = $this;

		return call_user_func_array($this->itsWidget, $args);
	}

	// Static

	protected static function create(array $properties = NULL) {
		return new Widget((array) $properties);
	}

	/**
	 * Register a new Simple Widget. WordPress requires the widget factory to be 
	 * set up before widgets can be registered. This method checks if the 
	 * environment was set up correctly and the widget_init action was triggered
	 * and registers a callback to register the widget if it hadn't yet.
	 * @param array|NULL $properties The properties for the simple widget.
	 * @return Widget|bool Returns the simple widget instance if the widget was 
	 * registered successfully, TRUE if the a callback to register the widget 
	 * was created or FALSE if the widget registration failed.
	 */
	public static function register(array $properties = NULL) {

		// Check for the widget factory
		
		if (!(isset($GLOBALS["wp_widget_factory"]) && ($factory = $GLOBALS["wp_widget_factory"]) instanceof WP_Widget_Factory)) {

			// Check if widgets_init was triggered
			
			if (did_action("widgets_init") < 1) {

				// Register callback

				add_action("widgets_init", function() use ($properties) {
					Widget::register($properties);
				});

				return true;
			}

			// On failure

			trigger_error("Can't register Simple Widget without widget factory", E_USER_NOTICE);

			return false;
		}

		// Register the widget

		return ($factory->widgets[sprintf("Widget_%s", md5(@$properties[self::PROPERTY_ID]))] = static::create($properties));
	}

	public static function __register(array $options = NULL) {
		trigger_error("Can't register the Simple Widget class as a Widget; use lowtone\wp\widgets\simple\Widget::register() to register an Simple Widget instance");

		return false;
	}

	public static function __createName() {
		$caller = function() {
			foreach (debug_backtrace() as $trace) {
				if (__FILE__ == $trace["file"])
					continue;

				return $trace["file"];
			}

			return false;
		};

		$name = basename($caller(), ".php");

		$name = preg_replace("/[^a-z]/i", " ", $name);

		$name = preg_replace('/([^A-Z])([A-Z])/', "$1 $2", $name);

		$name = preg_replace("/ +/", " ", $name);

		return ucwords($name);
	}

	public static function __createId($name) {
		return trim(strtolower(preg_replace("/[^a-z]+/i", "_", $name)), "_ ");
	}
	
}