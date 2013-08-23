<?php
namespace lowtone\wp\posts\comments\forms;
use lowtone\ui\forms\Form,
	lowtone\ui\forms\FieldSet,
	lowtone\ui\forms\Input,
	lowtone\util\buildables\interfaces\Buildable;

/**
 * @author Paul van der Meijs <code@paulvandermeijs.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\libs\lowtone\wp\posts\comments\forms
 */
class CommentForm extends Form implements Buildable {

	const OPTION_FIELDS = "fields";

	public function build(array $options = NULL) {
		$options = array_merge(array(
				self::OPTION_FIELDS => array("name", "email", "url"),
			), (array) $options);

		if (!is_user_logged_in()) {

			$fields = (array) $options[self::OPTION_FIELDS];

			$doName = in_array("name", $fields);
			$doEmail = in_array("email", $fields);

			if ($doName || $doEmail) {

				$authorFieldSet = $this->createFieldSet(array(
						FieldSet::PROPERTY_LEGEND => __("Author", "lowtone_wp")
					));

				if ($doName) {

					$authorFieldSet
						->appendChild(
							$this->createInput(Input::TYPE_TEXT, array(
									Input::PROPERTY_NAME => "name",
									Input::PROPERTY_LABEL => __("Name", "lowtone_wp"),
									Input::PROPERTY_PLACEHOLDER => __("Your name", "lowtone_wp"),
								))
						);

				}
				
				if ($doEmail) {

					$authorFieldSet
						->appendChild(
							$this->createInput(Input::TYPE_TEXT, array(
									Input::PROPERTY_NAME => "email",
									Input::PROPERTY_LABEL => __("Email", "lowtone_wp"),
									Input::PROPERTY_PLACEHOLDER => __("Your email address", "lowtone_wp"),
								))
						);

				}		

				$this->appendChild($authorFieldSet);

			}

			if (in_array("url", $fields)) {

				$this
					->appendChild(
							$this->createFieldSet(array(
										FieldSet::PROPERTY_LEGEND => __("Website", "lowtone_wp")
									))
								->appendChild(
										$this->createInput(Input::TYPE_TEXT, array(
												Input::PROPERTY_NAME => "url",
												Input::PROPERTY_LABEL => __("Website", "lowtone_wp"),
												Input::PROPERTY_PLACEHOLDER => __("Your website", "lowtone_wp"),
											))
									)
						);

			}

		}


		$this
			->appendChild(
					$this->createFieldSet(array(
								FieldSet::PROPERTY_LEGEND => __("Message", "lowtone_wp")
							))
						->appendChild(
								$this->createInput(Input::TYPE_TEXT, array(
										Input::PROPERTY_NAME => "message",
										Input::PROPERTY_LABEL => __("Message", "lowtone_wp"),
										Input::PROPERTY_PLACEHOLDER => __("Your message", "lowtone_wp"),
										Input::PROPERTY_MULTIPLE => true,
									))
							)
				)
			->appendChild(
					$this->createInput(Input::TYPE_SUBMIT, array(
							Input::PROPERTY_NAME => "submit",
							Input::PROPERTY_VALUE => __("Post comment", "lowtone_wp")
						))
				);

		return $this;
	}

}