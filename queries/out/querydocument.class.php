<?php
namespace lowtone\wp\queries\out;
use lowtone\types\objects\out\ObjectDocument,
	lowtone\ui\pagination\out\PaginationDocument,
	lowtone\wp\queries\Query,
	lowtone\wp\queries\pagination\Pagination;

/**
 * @author Paul van der Meijs <code@paulvandermeijs.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.1
 * @package wordpress\libs\lowtone\wp\queries\out
 */
class QueryDocument extends ObjectDocument {
	
	/**
	 * @var Query
	 */
	protected $itsQuery;
	
	const BUILD_POSTS = "build_posts",
		POST_COLLECTION_DOCUMENT_OPTIONS = "post_collection_document_options",
		BUILD_PAGINATION = "build_pagination",
		PAGINATION_DOCUMENT_OPTIONS = "pagination_document_options";
	
	public function __construct(Query $query) {
		parent::__construct($query);
		
		$this->itsQuery = $query;

		$this->updateBuildOptions(array(
				self::BUILD_ELEMENTS => array(
					Query::PROPERTY_QUERY_VARS,
					Query::PROPERTY_POST_COUNT,
					Query::PROPERTY_FOUND_POSTS,
					Query::PROPERTY_MAX_NUM_PAGES,
					Query::PROPERTY_CURRENT_POST,
				),
				self::PROPERTY_FILTERS => array(
					Query::PROPERTY_QUERY_VARS => function($value) {
						return array_filter($value, function($var) {
							return $var || is_numeric($var);
						});
					}
				)
			));

		// Set element name filter to handle numeric query vars

		$this->setElementNameFilter(function($name, $document) {
			if (!is_numeric($name))
				return $name;

			$element = $document->createElement("param");
			
			$element->setAttribute("key", $name);

			return $element;
		});
		
	}
	
	public function build(array $options = NULL) {
		parent::build($options);

		$queryElement = $this->documentElement;
		
		foreach ($this->itsQuery->context() as $context) {
			if (is_numeric($context))
				$context = "is_" . $context; // @todo Numeric values can't be attribute names, change this.

			$queryElement->setAttribute($context, "1");
		}
		
		if ($this->getBuildOption(self::BUILD_POSTS)) {
			$postCollectionDocument = $this
				->itsQuery
				->posts()
				->createDocument()
				->build((array) $this->getBuildOption(self::POST_COLLECTION_DOCUMENT_OPTIONS));
			
			if ($postCollectionElement = $this->importDocument($postCollectionDocument))
				$queryElement->appendChild($postCollectionElement);
				
		}
		
		if ($this->getBuildOption(self::BUILD_PAGINATION) && $this->itsQuery->{Query::PROPERTY_MAX_NUM_PAGES} > 1) {
			$paginationDocument = new PaginationDocument(new Pagination($this->itsQuery));
			
			$paginationDocument->build($this->getBuildOption(self::PAGINATION_DOCUMENT_OPTIONS));
			
			if ($paginationElement = $this->importDocument($paginationDocument))
				$queryElement->appendChild($paginationElement);
				
		}
		
		return $this;
	}
	
}