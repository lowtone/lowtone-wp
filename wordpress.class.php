<?php
namespace lowtone\wp;

class WordPress {

	public function query() {
		return new queries\Query();
	}

	public function context() {
		return self::query()->context();
	}
	
}