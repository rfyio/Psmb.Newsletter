<?php
namespace Psmb\Newsletter\Domain\Dto;

use TYPO3\Flow\Annotations as Flow;
use Psmb\Newsletter\Domain\Model\Newsletter;

/**
 * Class OperatingSystemDataResult
 * @package Psmb\Newsletter\Domain\Dto
 */
class OperatingSystemDataResult
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
        $clientOperatingSystems = array(
            'GNU/Linux' => 0,
            'iOS' => 0,
            'Apple' => 0,
            'Windows' => 0,
            'Android' => 0,
            'Other' => 0
        );

        if ($this->newsletter instanceof Newsletter) {
            $totalViews = $this->newsletter->getViewsCount();
            $clientDevices['GNU/Linux'] = $this->newsletter->getViewsOnOSValue('unix');
            $clientDevices['iOS'] = $this->newsletter->getViewsOnOSValue('iOS');
            $clientDevices['Apple'] = $this->newsletter->getViewsOnOSValue('apple');
            $clientDevices['Windows'] = $this->newsletter->getViewsOnOSValue('windows');
            $clientDevices['Android'] = $this->newsletter->getViewsOnOSValue('android');
            $clientDevices['Other'] = $this->newsletter->getViewsOnOSValue('other');
        }
        
        return array(
            'totals' => array('uniquePageviews' => $totalViews),
            'rows' => array(
                array('osFamilies' => 'Apple', 'uniquePageviews' => $clientOperatingSystems['Apple'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['Apple'] * 100 / $totalViews)))),
                array('osFamilies' => 'iOS', 'uniquePageviews' => $clientOperatingSystems['iOS'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['iOS'] * 100 / $totalViews)))),
                array('osFamilies' => 'Windows', 'uniquePageviews' => $clientOperatingSystems['Windows'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['Windows'] * 100 / $totalViews)))),
                array('osFamilies' => 'GNU/Linux', 'uniquePageviews' => $clientOperatingSystems['GNU/Linux'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['GNU/Linux'] * 100 / $totalViews)))),
                array('osFamilies' => 'Android', 'uniquePageviews' => $clientOperatingSystems['Android'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['Android'] * 100 / $totalViews)))),
                array('osFamilies' => 'Other', 'uniquePageviews' => $clientOperatingSystems['Other'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['Other'] * 100 / $totalViews))))
            )
        );
    }

}