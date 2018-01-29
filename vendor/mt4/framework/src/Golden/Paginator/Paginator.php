<?php

namespace Golden\Paginator;


/**
 * Class Paginator
 *
 * @package Golden\Paginator
 */
class Paginator
{
	const NUM_PLACEHOLDER = '(:num)';

	/**
	 * @var int
	 */
	protected $total_items;
	/**
	 * @var
	 */
	protected $num_pages;
	/**
	 * @var int
	 */
	protected $items_per_page;
	/**
	 * @var int
	 */
	protected $current_page;
	/**
	 * @var string
	 */
	protected $url_pattern;
	/**
	 * @var int
	 */
	protected $max_pages_to_show = 10;
	/**
	 * @var string
	 */
	protected $previous_text = 'Anterior';
	/**
	 * @var string
	 */
	protected $next_text = 'Próxima';


	/**
	 * @param int $totalItems The total number of items.
	 * @param int $itemsPerPage The number of items per page.
	 * @param int $currentPage The current page number.
	 * @param string $urlPattern A URL for each page, with (:num) as a placeholder for the page number. Ex. '/foo/page/(:num)'
	 */
	public function __construct($totalItems, $itemsPerPage, $currentPage, $urlPattern = '')
	{
		$this->total_items    = $totalItems;
		$this->items_per_page = $itemsPerPage;
		$this->current_page   = $currentPage;
		$this->url_pattern    = $urlPattern;
		$this->refreshNumPages();
	}

	/**
	 *
	 */
	protected function refreshNumPages()
	{
		$this->num_pages = ( $this->items_per_page == 0 ? 0 : (int) ceil( $this->total_items / $this->items_per_page));
	}

	/**
	 * @param int $max_pages_to_show
	 *
	 * @throws \InvalidArgumentException if $max_pages_to_show is less than 3.
	 */
	public function setMaxPagestoshow( $max_pages_to_show)
	{
		if ( $max_pages_to_show < 3)
			throw new \InvalidArgumentException('max_pages_to_show cannot be less than 3.');

		$this->max_pages_to_show = $max_pages_to_show;
	}

	/**
	 * @return int
	 */
	public function getMaxPagestoshow()
	{
		return $this->max_pages_to_show;
	}

	/**
	 * @param int $current_page
	 */
	public function setCurrentPage($current_page)
	{
		$this->current_page = $current_page;
	}

	/**
	 * @return int
	 */
	public function getCurrentPage()
	{
		return $this->current_page;
	}

	/**
	 * @param int $items_per_page
	 */
	public function setItemsPerpage($items_per_page)
	{
		$this->items_per_page = $items_per_page;
		$this->refreshNumPages();
	}

	/**
	 * @return int
	 */
	public function getItemsPerpage()
	{
		return $this->items_per_page;
	}

	/**
	 * @param int $total_items
	 */
	public function setTotalItems($total_items)
	{
		$this->total_items = $total_items;
		$this->refreshNumPages();
	}

	/**
	 * @return int
	 */
	public function getTotalItems()
	{
		return $this->total_items;
	}

	/**
	 * @return int
	 */
	public function getNumPages()
	{
		return $this->num_pages;
	}

	/**
	 * @param string $url_pattern
	 */
	public function setUrlPattern($url_pattern)
	{
		$this->url_pattern = $url_pattern;
	}

	/**
	 * @return string
	 */
	public function getUrlPattern()
	{
		return $this->url_pattern;
	}

	/**
	 * @param int $pageNum
	 * @return string
	 */
	public function getPageUrl($pageNum)
	{
		return str_replace(self::NUM_PLACEHOLDER, $pageNum, $this->url_pattern);
	}

	/**
	 * @return int|null
	 */
	public function getNextPage()
	{
		if ( $this->current_page < $this->num_pages)
			return $this->current_page + 1;

		return null;
	}

	/**
	 * @return int|null
	 */
	public function getPrevPage()
	{
		if ( $this->current_page > 1)
			return $this->current_page - 1;

		return null;
	}

	/**
	 * @return null|string
	 */
	public function getNextUrl()
	{
		if (!$this->getNextPage())
			return null;

		return $this->getPageUrl($this->getNextPage());
	}

	/**
	 * @return string|null
	 */
	public function getPrevUrl()
	{
		if (!$this->getPrevPage())
			return null;

		return $this->getPageUrl($this->getPrevPage());
	}

	/**
	 * Get an array of paginated page data.
	 *
	 * Example:
	 * array(
	 *     array ('num' => 1,     'url' => '/example/page/1',  'isCurrent' => false),
	 *     array ('num' => '...', 'url' => NULL,               'isCurrent' => false),
	 *     array ('num' => 3,     'url' => '/example/page/3',  'isCurrent' => false),
	 *     array ('num' => 4,     'url' => '/example/page/4',  'isCurrent' => true ),
	 *     array ('num' => 5,     'url' => '/example/page/5',  'isCurrent' => false),
	 *     array ('num' => '...', 'url' => NULL,               'isCurrent' => false),
	 *     array ('num' => 10,    'url' => '/example/page/10', 'isCurrent' => false),
	 * )
	 *
	 * @return array
	 */
	public function getPages()
	{
		$pages = array();

		if ( $this->num_pages <= 1)
			return array();

		if ( $this->num_pages <= $this->max_pages_to_show)
		{
			for ($i = 1; $i <= $this->num_pages; $i++)
				$pages[] = $this->createPage($i, $i == $this->current_page);
		}
		else
		{
			// Determine the sliding range, centered around the current page.
			$numAdjacents = (int) floor( ( $this->max_pages_to_show - 3) / 2);

			if ( $this->current_page + $numAdjacents > $this->num_pages)
				$slidingStart = $this->num_pages - $this->max_pages_to_show + 2;
			else
				$slidingStart = $this->current_page - $numAdjacents;

			if ($slidingStart < 2)
				$slidingStart = 2;

			$slidingEnd = $slidingStart + $this->max_pages_to_show - 3;

			if ($slidingEnd >= $this->num_pages)
				$slidingEnd = $this->num_pages - 1;

			// Build the list of pages.
			$pages[] = $this->createPage(1, $this->current_page == 1);

			if ($slidingStart > 2)
				$pages[] = $this->createPageEllipsis();

			for ($i = $slidingStart; $i <= $slidingEnd; $i++)
				$pages[] = $this->createPage($i, $i == $this->current_page);

			if ($slidingEnd < $this->num_pages - 1)
				$pages[] = $this->createPageEllipsis();

			$pages[] = $this->createPage($this->num_pages, $this->current_page == $this->num_pages);
		}

		return $pages;
	}

	/**
	 * Create a page data structure.
	 *
	 * @param int $pageNum
	 * @param bool $isCurrent
	 * @return array
	 */
	protected function createPage($pageNum, $isCurrent = false)
	{
		return [
			'num' => $pageNum,
			'url' => $this->getPageUrl($pageNum),
			'isCurrent' => $isCurrent,
		];
	}

	/**
	 * @return array
	 */
	protected function createPageEllipsis()
	{
		return array(
			'num' => '...',
			'url' => null,
			'isCurrent' => false,
		);
	}

	/**
	 * Render an HTML pagination control.
	 *
	 * @return string
	 */
	public function toHtml()
	{
		if ( $this->num_pages <= 1)
			return '';

		$html = '<ul class="pagination">';

		if ($this->getPrevUrl())
			$html .= '<li><a href="' . $this->getPrevUrl() . '">&laquo; '. $this->previous_text . '</a></li>';

		foreach ($this->getPages() as $page)
		{
			if ($page['url'])
			{
				$html .= '<li' . ($page['isCurrent'] ? ' class="active"' : '') . '><a href="' . $page['url'] . '">'
				         . $page['num'] . '</a></li>';
			}
			else
			{
				$html .= '<li class="disabled"><span>' . $page['num'] . '</span></li>';
			}
		}

		if ($this->getNextUrl())
			$html .= '<li><a href="' . $this->getNextUrl() . '">'. $this->next_text . ' &raquo;</a></li>';

		$html .= '</ul>';
		return $html;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toHtml();
	}

	/**
	 * @return float|int|null
	 */
	public function getCurrentPageFirstItem()
	{
		$first = ( $this->current_page - 1) * $this->items_per_page + 1;
		if ($first > $this->total_items)
			return null;

		return $first;
	}

	/**
	 * @return float|int|null
	 */
	public function getCurrentPageLastItem()
	{
		$first = $this->getCurrentPageFirstItem();

		if ($first === null)
			return null;

		$last = $first + $this->items_per_page - 1;

		if ($last > $this->total_items)
			return $this->total_items;

		return $last;
	}

	/**
	 * @param $text
	 *
	 * @return $this
	 */
	public function setPreviousText($text)
	{
		$this->previous_text = $text;
		return $this;
	}

	/**
	 * @param $text
	 *
	 * @return $this
	 */
	public function setNextText($text)
	{
		$this->next_text = $text;
		return $this;
	}
}