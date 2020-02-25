<?php
namespace Psmb\Newsletter\Domain\Dto;

use Neos\Flow\Annotations as Flow;
use Psmb\Newsletter\Domain\Model\Newsletter;
use Neos\Flow\Persistence\Generic\QueryResult;

/**
 * Class DeviceDataResult
 * @package Psmb\Newsletter\Domain\Dto
 */
class OverallDataResult
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
     * @return array
     */
    public function getCollectionData()
    {
        $overall = [];
        $result = [];
        $sentRow = [];
        $viewsRow = [];
        $uniqueRow = [];

        /** @var Newsletter $row */
        foreach ($this->newsletter as $row) {
            $key = $row->getSubscriptionIdentifier();

            if ($key === '') {
                $key = 'Other';
            }

            if (!isset($overall[$key])) {
                $overall[$key]['sentCount'] = 0;
                $overall[$key]['viewsCount'] = 0;
                $overall[$key]['uniqueViewCount'] = 0;
            }

            $result[$key] = $key;
            $overall[$key]['sentCount'] += $row->getSentCount();
            $overall[$key]['viewsCount'] += $row->getViewsCount();
            $overall[$key]['uniqueViewCount'] += $row->getUniqueViewCount();
        }

        foreach($overall as $key => $row) {
            $sentRow[] = $row['sentCount'];
            $viewsRow[] = $row['viewsCount'];
            $uniqueRow[] = $row['uniqueViewCount'];
        }


        return [
            'xAxis' => array_values($result),
            'series' => [
                [
                    'name' => 'Sent',
                    'data' => $sentRow
                ],
                [
                    'name' => 'Total Views',
                    'data' => $viewsRow
                ],
                [
                    'name' => 'Unique View',
                    'data' => $uniqueRow
                ]
            ]
        ];
    }
}