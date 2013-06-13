<?php
namespace lowtone\wp\queries\pagination;
use lowtone\wp\queries\Query;

/**
 * @author Paul van der Meijs <code@paulvandermeijs.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.1
 * @package wordpress\libs\lowtone\wp\queries\pagination
 */
class Pagination extends \lowtone\ui\pagination\Pagination {
	
	/**
	 * @var Query
	 */
	protected $itsQuery;
	
	public function __construct(Query $query) {
		$this->itsQuery = $query;
	}
	
	// Getters
	
	public function getPageURL($number) {
		return apply_filters("paginate_links", get_pagenum_link($number, false));
	}
	
	public function getQuery() {
		return $this->itsQuery;
	}
	
	public function getCurrent() {
		return $this->itsQuery->qvar("paged") ?: 1;
	}
	
	public function getTotal() {
		return $this->itsQuery->{Query::PROPERTY_MAX_NUM_PAGES};
	}
	
	public function getItemsPerPage() {
		return $this->itsQuery->{Query::PROPERTY_POST_COUNT};
	}
	
	public function getTotalItems() {
		return $this->itsQuery->{Query::PROPERTY_FOUND_POSTS};
	}
	
}