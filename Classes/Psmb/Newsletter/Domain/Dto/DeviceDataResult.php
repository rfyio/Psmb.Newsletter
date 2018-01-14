<?php
namespace Psmb\Newsletter\Domain\Dto;

use TYPO3\Flow\Annotations as Flow;
use Psmb\Newsletter\Domain\Model\Newsletter;

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
    function getData()
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
            'totals' => array('uniquePageviews' => $totalViews),
            'rows' => array(
                array('deviceCategory' => 'desktop', 'uniquePageviews' => $clientDevices['Desktop'], 'percent' => ($totalViews == 0 ? 0 : round(($clientDevices['Desktop'] * 100 / $totalViews)))),
                array('deviceCategory' => 'tablet', 'uniquePageviews' => $clientDevices['Tablet'], 'percent' => ($totalViews == 0 ? 0 : round(($clientDevices['Tablet'] * 100 / $totalViews)))),
                array('deviceCategory' => 'smartphone', 'uniquePageviews' => $clientDevices['Smartphone'], 'percent' => ($totalViews == 0 ? 0 : round(($clientDevices['Smartphone'] * 100 / $totalViews)))),
                array('deviceCategory' => 'other', 'uniquePageviews' => $clientDevices['Other'], 'percent' => ($totalViews == 0 ? 0 : round(($clientDevices['Other'] * 100 / $totalViews))))
            )
        );
    }
}