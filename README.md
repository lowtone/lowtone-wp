# WordPress extension for the Lowtone Library

Adds real classes for Posts, Taxonomies, Terms and Users (based on the Lowtone Record class), easy Widget creation and more.

Requires the [Lowtone Library for WordPress](https://github.com/lowtone/lowtone).

## Simple Widgets

By default WordPress requires you to extend the WP_Widget class and register your class using [register_widget()](http://codex.wordpress.org/Function_Reference/register_widget). Assuming you need other widgets to be able to extend yours or you're extending an existing widget there's nothing wrong with that. In another case you might just want to define a function for widget output and register that. This can be achieved using the simple widget class.

A widget can be registered using `\lowtone\wp\widgets\simple\Widget::register()` which requires an options array as its only argument.

```php
use lowtone\wp\widgets\simple\Widget,
	lowtone\ui\forms\Form,
	lowtone\ui\forms\Input;

add_action("widgets_init", function() {

	Widget::register(array(
			Widget::PROPERTY_ID => "hello_simple_widget",
			Widget::PROPERTY_NAME => __("Simple Widget"),
			Widget::PROPERTY_DESCRIPTION => __("Testing Simple Widgets"),
			Widget::PROPERTY_WIDGET => function(array $args, array $instance = NULL) {
				echo "Hello " . (@$instance["name"] ?: "Simple Widget") . "!";
			},
			Widget::PROPERTY_FORM => function(array $instance = NULL) {
				$form = new Form();

				$form
					->appendChild(
						$form->createInput(Input::TYPE_TEXT, array(
								Input::PROPERTY_NAME => "name",
								Input::PROPERTY_LABEL => __("Name")
							))
					)
					->setValues($instance);

				return $form;
			}
		));
});
```

The above example creates a widget using the provided ID, name, description, widget function, and form function. Only a name is required. If no ID is supplied it is created from the name (this could cause a problem when the name is localized so it's recommended to always define an ID).

The widget function is called to generete the widget's output using an `$args` and `$instance` parameter (just like `WP_Widget::widget()`).

The form function is called when a widget needs to be configured e.g. on the Widgets page in the back-end and is required to return an instance of the `\lowtone\ui\forms\Form` class.