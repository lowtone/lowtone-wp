<?php
namespace lowtone\wp\posts\comments\out;
use lowtone\Util,
	lowtone\dom\Document,
	lowtone\wp\dom\interfaces\Document as WpDocument,
	lowtone\wp\posts\Post,
	lowtone\wp\posts\comments\forms\CommentForm,
	lowtone\types\objects\out\ObjectDocument;

/**
 * @author Paul van der Meijs <code@lowtone.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\libs\lowtone\wp\posts\comments\out
 */
class CommentFormDocument extends Document implements WpDocument {

	const BUILD_ACTIONS = "build_actions";

	/**
	 * @var Post
	 */
	protected $itsPost;

	public function __construct(Post $post) {
		parent::__construct();

		$this->itsPost = $post;

		$this->updateBuildOptions(array(
				self::BUILD_ACTIONS => true,
				self::BUILD_LOCALES => true
			));

	}

	public function build(array $options = NULL) {
		$this->updateBuildOptions((array) $options);

		$commentFormElement = $this->createAppendElement("comment_form");

		$formDocument = CommentForm::create()
			->build()
			->createDocument()
			->build(array(
					ObjectDocument::OBJECT_ELEMENT_NAME => "form"
				));

		if ($formElement = $this->importDocument($formDocument))
			$commentFormElement->appendChild($formElement);

		$commenter = wp_get_current_commenter();
		$user = wp_get_current_user();
		$userIdentity = $user->exists() ? $user->display_name : '';

		$permalink = apply_filters("the_permalink", get_permalink($this->itsPost->{Post::PROPERTY_ID}));

		$isLoggedIn = is_user_logged_in();
		$mustLogIn = get_option("comment_registration") && !$isLoggedIn;

		if ($this->getBuildOption(self::BUILD_ACTIONS)) {

			$actions = array(
					"comment_form_before" => NULL,
					"comment_form_top" => NULL,
					"comment_form" => array($this->itsPost->{Post::PROPERTY_ID}),
					"comment_form_after" => NULL,
				);

			$catchAction = function($action, $args) {
				$args = (array) $args;

				array_unshift($args, $action);

				return Util::catchOutput(true, "do_action", $args);
			};

			$actionOutput = array();

			foreach ($actions as $action => $args) 
				$actionOutput[$action] = $catchAction($action, $args);

			$commentFormElement->createAppendElement("actions", $actionOutput);

		}

		if ($this->getBuildOption(self::BUILD_LOCALES)) {

			$locales = array(
					"title" => __("Post comment", "lowtone_wp"),
				);

			if ($mustLogIn) 
				$locales["must_log_in"] = sprintf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url($permalink));
			
			if ($isLoggedIn)
				$locales["is_logged_in"] = sprintf(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>'), get_edit_user_link(), $userIdentity, wp_logout_url($permalink));

			$commentFormElement->createAppendElement("locales", $locales);

		}

		return $this;
	}

}