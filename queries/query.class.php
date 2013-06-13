<?php
namespace lowtone\wp\queries;
use WP_Query,
	lowtone\types\objects\Object,
	lowtone\wp\posts\Post,
	lowtone\wp\pages\Page;

/**
 * @author Paul van der Meijs <code@paulvandermeijs.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.1
 * @package wordpress\libs\lowtone\wp\queries
 */
class Query extends Object {
	
	/**
	 * @var WP_Query
	 */
	protected $itsQuery;

	const PROPERTY_QUERY_VARS = "query_vars",
		PROPERTY_TAX_QUERY = "tax_query",
		PROPERTY_META_QUERY = "meta_query",
		PROPERTY_POST_COUNT = "post_count",
		PROPERTY_CURRENT_POST = "current_post",
		PROPERTY_IN_THE_LOOP = "in_the_loop",
		PROPERTY_COMMENT_COUNT = "comment_count",
		PROPERTY_CURRENT_COMMENT = "current_comment",
		PROPERTY_FOUND_POSTS = "found_posts",
		PROPERTY_MAX_NUM_PAGES = "max_num_pages",
		PROPERTY_MAX_NUM_COMMENT_PAGES = "max_num_comment_pages",
		PROPERTY_IS_SINGLE = "is_single",
		PROPERTY_IS_PREVIEW = "is_preview",
		PROPERTY_IS_PAGE = "is_page",
		PROPERTY_IS_ARCHIVE = "is_archive",
		PROPERTY_IS_DATE = "is_date",
		PROPERTY_IS_YEAR = "is_year",
		PROPERTY_IS_MONTH = "is_month",
		PROPERTY_IS_DAY = "is_day",
		PROPERTY_IS_TIME = "is_time",
		PROPERTY_IS_AUTHOR = "is_author",
		PROPERTY_IS_CATEGORY = "is_category",
		PROPERTY_IS_TAG = "is_tag",
		PROPERTY_IS_TAX = "is_tax",
		PROPERTY_IS_SEARCH = "is_search",
		PROPERTY_IS_FEED = "is_feed",
		PROPERTY_IS_COMMENT_FEED = "is_comment_feed",
		PROPERTY_IS_TRACKBACK = "is_trackback",
		PROPERTY_IS_HOME = "is_home",
		PROPERTY_IS_404 = "is_404",
		PROPERTY_IS_COMMENTS_POPUP = "is_comments_popup",
		PROPERTY_IS_PAGED = "is_paged",
		PROPERTY_IS_ADMIN = "is_admin",
		PROPERTY_IS_ATTACHMENT = "is_attachment",
		PROPERTY_IS_SINGULAR = "is_singular",
		PROPERTY_IS_ROBOTS = "is_robots",
		PROPERTY_IS_POSTS_PAGE = "is_posts_page",
		PROPERTY_IS_POST_TYPE_ARCHIVE = "is_post_type_archive",
		PROPERTY_QUERY_VARS_HASH = "query_vars_hash",
		PROPERTY_QUERY_VARS_CHANGED = "query_vars_changed",
		PROPERTY_THUMBNAILS_CACHED = "thumbnails_cached",
		PROPERTY_QUERY = "query",
		PROPERTY_QUERIED_OBJECT = "queried_object",
		PROPERTY_QUERIED_OBJECT_ID = "queried_object_id",
		PROPERTY_REQUEST = "request",
		PROPERTY_POSTS = "posts",
		PROPERTY_POST = "post";
	
	const CONTEXT_404 = "404",
		CONTEXT_SEARCH = "search",
		CONTEXT_TAX = "tax",
		CONTEXT_FRONT_PAGE = "front_page",
		CONTEXT_HOME = "home",
		CONTEXT_ATTACHMENT = "attachment",
		CONTEXT_SINGLE = "single",
		CONTEXT_PAGE = "page",
		CONTEXT_CATEGORY = "category",
		CONTEXT_TAG = "tag",
		CONTEXT_AUTHOR = "author",
		CONTEXT_DATE = "date",
		CONTEXT_ARCHIVE = "archive",
		CONTEXT_COMMENTS_POPUP = "comments_popup",
		CONTEXT_PAGED = "paged";
	
	public function __construct(WP_Query $query = NULL) {
		if (is_null($query))
			$query = $GLOBALS["wp_query"];
			
		$this->itsQuery = $query;

		parent::__construct($this->itsQuery);
		
	}
	
	public function context() {
		return array_keys(array_filter(array(
			self::CONTEXT_404 => $this->itsQuery->is_404(),
			self::CONTEXT_SEARCH => $this->itsQuery->is_search(),
			self::CONTEXT_TAX => $this->itsQuery->is_tax(),
			self::CONTEXT_FRONT_PAGE => $this->itsQuery->is_front_page(),
			self::CONTEXT_HOME => $this->itsQuery->is_home(),
			self::CONTEXT_ATTACHMENT => $this->itsQuery->is_attachment(),
			self::CONTEXT_SINGLE => $this->itsQuery->is_single(),
			self::CONTEXT_PAGE => $this->itsQuery->is_page(),
			self::CONTEXT_CATEGORY => $this->itsQuery->is_category(),
			self::CONTEXT_TAG => $this->itsQuery->is_tag(),
			self::CONTEXT_AUTHOR => $this->itsQuery->is_author(),
			self::CONTEXT_DATE => $this->itsQuery->is_date(),
			self::CONTEXT_ARCHIVE => $this->itsQuery->is_archive(),
			self::CONTEXT_COMMENTS_POPUP => $this->itsQuery->is_comments_popup(),
			self::CONTEXT_PAGED => $this->itsQuery->is_paged()
		)));
	}
	
	/**
	 * Get a query var from the WordPress query object.
	 * @param string $var The name of the required variable.
	 * @return mixed Returns the value for the required variable.
	 */
	public function qvar($var) {
		return $this->itsQuery->get($var);
	}

	public function postType() {
		if ($postType = trim($this->qvar("post_type")))
			return $postType;

		if (($queriedObject = $this->{self::PROPERTY_QUERIED_OBJECT}) instanceof \WP_Post) 
			return $queriedObject->{Post::PROPERTY_POST_TYPE};

		return Post::__postType();
	}

	public function posts() {
		$postClass =  "lowtone\\wp\\posts\\Post";

		if (NULL != ($postType = get_post_type_object(self::postType()))) {

			if (isset($postType->post_class))
				$postClass = $postType->post_class;
			else if (Page::__postType() == $postType->name)
				$postClass = "lowtone\\wp\\pages\\Page";

		}
		
		return $postClass::__createCollection($this->itsQuery->posts);
	}
	
	/**
	 * Get the original WordPress query object.
	 * @return WP_Query Return the original WordPress query object.
	 */
	public function __getQuery() {
		return $this->itsQuery;
	}
	
}