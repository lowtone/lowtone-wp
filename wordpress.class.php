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
	
}