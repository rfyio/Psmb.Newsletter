<?php
namespace Psmb\Newsletter\Domain\Dto;

use Psmb\Newsletter\Domain\Model\Newsletter;
use TYPO3\Flow\Annotations as Flow;

/**
 * Class ColumnDataResult
 * @package Psmb\Newsletter\Domain\Dto
 */
class ColumnDataResult
{
    /**
     * @var Newsletter
     */
    protected $newsletter;

    /**
     * ColumnDataResult constructor.
     * @param $newsletter
     */
    public function __construct($newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $uniqueViews = 0;
        $totalViews = 0;

        if ($this->newsletter instanceof Newsletter) {
            $uniqueViews = $this->newsletter->getUniqueViewCount();
            $totalViews = $this->newsletter->getViewsCount();
        }

        return array(
            'totals' => array('unique_views' => $uniqueViews, 'total_views' => $totalViews),
            'rows' => array(array('unique_views' => $uniqueViews, 'total_views' => $totalViews)),
        );
    }
}