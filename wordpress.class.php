<?php
namespace lowtone\wp;

/**
 * @author Paul van der Meijs <code@paulvandermeijs.nl>
 * @copyright Copyright (c) 2013, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.1
 * @package wordpress\libs\lowtone\wp
 */
class WordPress {

	public function query() {
		return new queries\Query();
	}

	public function context() {
		return self::query()->context();
	}

	public function doingCron() {
		return defined("DOING_CRON") && DOING_CRON;
	}

	public function doingAutosave() {
		return defined("DOING_AUTOSAVE") && DOING_AUTOSAVE;
	}

	public function doingAjax() {
		return defined("DOING_AJAX") && DOING_AJAX;
	}
	
	public function isDebug() {
		return defined("WP_DEBUG") && WP_DEBUG;
	}

	public function isScriptDebug() {
		return defined("SCRIPT_DEBUG") && SCRIPT_DEBUG;
	}
	
}