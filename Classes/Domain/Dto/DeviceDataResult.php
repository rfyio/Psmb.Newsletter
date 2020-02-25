<?php
namespace Psmb\Newsletter\Domain\Dto;

use Neos\Flow\Annotations as Flow;
use Psmb\Newsletter\Domain\Model\Newsletter;
use Neos\Flow\Persistence\Generic\QueryResult;

/**
 * Class DeviceDataResult
 * @package Psmb\Newsletter\Domain\Dto
 */
class DeviceDataResult
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
        $totalViews = 0;
        $clientDevices = array(
            'Desktop' => 0,
            'Tablet' => 0,
            'Smartphone' => 0,
            'Other' => 0
        );

        if ($this->newsletter instanceof Newsletter) {
            $totalViews = $this->newsletter->getViewsCount();
            $clientDevices['Desktop'] = $this->newsletter->getViewsOnDeviceValue('desktop');
            $clientDevices['Tablet'] = $this->newsletter->getViewsOnDeviceValue('tablet');
            $clientDevices['Smartphone'] = $this->newsletter->getViewsOnDeviceValue('smartphone');
            $clientDevices['Other'] = $this->newsletter->getViewsOnDeviceValue('other');
        }

        return array(
            array('name' => 'desktop', 'y' => $clientDevices['Desktop'], 'percent' => ($totalViews == 0 ? 0 : round(($clientDevices['Desktop'] * 100 / $totalViews)))),
            array('name' => 'tablet', 'y' => $clientDevices['Tablet'], 'percent' => ($totalViews == 0 ? 0 : round(($clientDevices['Tablet'] * 100 / $totalViews)))),
            array('name' => 'smartphone', 'y' => $clientDevices['Smartphone'], 'percent' => ($totalViews == 0 ? 0 : round(($clientDevices['Smartphone'] * 100 / $totalViews)))),
            array('name' => 'other', 'y' => $clientDevices['Other'], 'percent' => ($totalViews == 0 ? 0 : round(($clientDevices['Other'] * 100 / $totalViews))))
        );
    }

    /**
     * @return array
     */
    public function getCollectionData()
    {
        $totalViews = 0;
        $clientDevices = array(
            'Desktop' => 0,
            'Tablet' => 0,
            'Smartphone' => 0,
            'Other' => 0
        );

        /** @var Newsletter $row */
        foreach ($this->newsletter as $row) {
            $totalViews = $row->getViewsCount();
            $clientDevices['Desktop'] += $row->getViewsOnDeviceValue('desktop');
            $clientDevices['Tablet'] += $row->getViewsOnDeviceValue('tablet');
            $clientDevices['Smartphone'] += $row->getViewsOnDeviceValue('smartphone');
            $clientDevices['Other'] += $row->getViewsOnDeviceValue('other');
        }

        return array(
            array('name' => 'desktop', 'y' => $clientDevices['Desktop'], 'percent' => ($totalViews == 0 ? 0 : round(($clientDevices['Desktop'] * 100 / $totalViews)))),
            array('name' => 'tablet', 'y' => $clientDevices['Tablet'], 'percent' => ($totalViews == 0 ? 0 : round(($clientDevices['Tablet'] * 100 / $totalViews)))),
            array('name' => 'smartphone', 'y' => $clientDevices['Smartphone'], 'percent' => ($totalViews == 0 ? 0 : round(($clientDevices['Smartphone'] * 100 / $totalViews)))),
            array('name' => 'other', 'y' => $clientDevices['Other'], 'percent' => ($totalViews == 0 ? 0 : round(($clientDevices['Other'] * 100 / $totalViews))))
        );
    }
}