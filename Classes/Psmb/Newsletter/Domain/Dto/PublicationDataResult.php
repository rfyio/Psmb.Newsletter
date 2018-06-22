<?php
namespace Psmb\Newsletter\Domain\Dto;

use TYPO3\Flow\Annotations as Flow;
use Psmb\Newsletter\Domain\Model\Newsletter;
use TYPO3\Flow\Persistence\Generic\QueryResult;

/**
 * Class DeviceDataResult
 * @package Psmb\Newsletter\Domain\Dto
 */
class PublicationDataResult
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
     * @throws \TYPO3\TYPO3CR\Exception\NodeException
     */
    public function getCollectionData()
    {
        $overall = [];
        $result = [];
        $sentRow = [];
        $viewsRow = [];
        $uniqueRow = [];

        $i = 0;
        $limit = 10;

        /** @var Newsletter $row */
        foreach ($this->newsletter as $row) {
            $key = $row->getNode()->getProperty('title');

            if (!isset($overall[$key])) {
                $overall[$key]['sentCount'] = 0;
                $overall[$key]['viewsCount'] = 0;
                $overall[$key]['uniqueViewCount'] = 0;
            }

            $result[$key] = $key;
            $overall[$key]['sentCount'] += $row->getSentCount();
            $overall[$key]['viewsCount'] += $row->getViewsCount();
            $overall[$key]['uniqueViewCount'] += $row->getUniqueViewCount();

            $i++;
            if ($i === $limit) {
                break;
            }
        }

        foreach($overall as $key => $row) {
            $sentRow[] = $row['sentCount'];
            $viewsRow[] = $row['viewsCount'];
            $uniqueRow[] = $row['uniqueViewCount'];
        }

        return [
            'xAxis' => array_reverse(array_values($result)),
            'series' => [
                [
                    'name' => 'Sent',
                    'data' => array_reverse($sentRow)
                ],
                [
                    'name' => 'Total Views',
                    'data' => array_reverse($viewsRow)
                ],
                [
                    'name' => 'Unique View',
                    'data' => array_reverse($uniqueRow)
                ]
            ]
        ];
    }
}