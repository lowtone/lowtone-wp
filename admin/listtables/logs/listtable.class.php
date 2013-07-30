<?php 
namespace lowtone\wp\admin\listtables\logs;
use lowtone\io\logging\Log,
	lowtone\io\logging\entries\Entry,
	lowtone\wp\admin\listtables\ListTable as Base;

/**
 * @author Paul van der Meijs <code@lowtone.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\libs\lowtone\wp\admin\listtables\logs
 */
class ListTable extends Base {
	
	protected $itsFiles = array();

	public function __construct($args = array()) {
		if (isset($args["files"]))
			$this->itsFiles = (array) $args["files"];

		parent::__construct(array(
				"plural" => "entries",
				"singular" => "entry",
			));
	}

	public function prepare_items() {
		$items = array();

		foreach ((array) $this->itsFiles as $file) {
			if (false === ($lines = file($file)))
				continue;

			$items = array_merge($items, $lines);
		}

		$totalItems = count($items);

		$order = isset($_REQUEST["order"]) ? $_REQUEST["order"] : "desc";
		$orderBy = isset($_REQUEST["order_by"]) ? $_REQUEST["order_by"] : Entry::PROPERTY_TIMESTAMP;

		switch ($orderBy) {
			case Entry::PROPERTY_TIMESTAMP:

				if ("desc" == $order)
					$items = array_reverse($items);

				break;
		}

		$itemsPerPage = 25;

		$offset = ($this->get_pagenum() - 1) * $itemsPerPage;

		$items = array_slice($items, $offset, $itemsPerPage);

		$this->items = $items;

		$this->_column_headers = array( 
			$this->get_columns(),
			$this->get_hidden_columns(),
			$this->get_sortable_columns(),
		);

		$this->set_pagination_args(array(
			"total_items" => $totalItems,
			"per_page"    => $itemsPerPage,
			"total_pages" => ceil($totalItems / $itemsPerPage)
		));

	}

	public function has_items() {
		return count($this->items) > 0;
	}

	public function get_columns() {
		return array(
				Entry::PROPERTY_TIMESTAMP => __("Timestamp", "lowtone_wp"),
				Entry::PROPERTY_DOMAIN => __("Domain", "lowtone_wp"),
				Entry::PROPERTY_MESSAGE => __("Message", "lowtone_wp"),
			);
	}

	public function get_sortable_columns() {
		return array(
				Entry::PROPERTY_TIMESTAMP => array(Entry::PROPERTY_TIMESTAMP, false)
			);
	}

	public function get_hidden_columns() {
		return array(
				Entry::PROPERTY_DOMAIN,
			);
	}

	public function column_default($item, $column) {
		$entry = Entry::fromString($item);

		return (string) $entry[$column];
	}

}