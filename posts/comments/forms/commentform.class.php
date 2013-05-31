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

	public function build(array $options = NULL) {
		$this
			->appendChild(
					$this->createFieldSet(array(
								FieldSet::PROPERTY_LEGEND => __("Author", "lowtone_wp")
							))
						->appendChild(
								$this->createInput(Input::TYPE_TEXT, array(
										Input::PROPERTY_NAME => "name",
										Input::PROPERTY_LABEL => __("Name", "lowtone_wp")
									))
							)
						->appendChild(
								$this->createInput(Input::TYPE_TEXT, array(
										Input::PROPERTY_NAME => "email",
										Input::PROPERTY_LABEL => __("Email", "lowtone_wp")
									))
							)
				)
			->appendChild(
					$this->createFieldSet(array(
								FieldSet::PROPERTY_LEGEND => __("Website", "lowtone_wp")
							))
						->appendChild(
								$this->createInput(Input::TYPE_TEXT, array(
										Input::PROPERTY_NAME => "url",
										Input::PROPERTY_LABEL => __("Website", "lowtone_wp")
									))
							)
				)
			->appendChild(
					$this->createFieldSet(array(
								FieldSet::PROPERTY_LEGEND => __("Message", "lowtone_wp")
							))
						->appendChild(
								$this->createInput(Input::TYPE_TEXT, array(
										Input::PROPERTY_NAME => "message",
										Input::PROPERTY_LABEL => __("Message", "lowtone_wp"),
										Input::PROPERTY_MULTIPLE => true
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