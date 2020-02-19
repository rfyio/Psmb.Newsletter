<?php
namespace Psmb\Newsletter\Domain\Dto;

use Neos\Flow\Annotations as Flow;
use Psmb\Newsletter\Domain\Model\Newsletter;
use Neos\Flow\Persistence\Generic\QueryResult;

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
    public function getData()
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
            $clientOperatingSystems['GNU/Linux'] = $this->newsletter->getViewsOnOSValue('unix');
            $clientOperatingSystems['iOS'] = $this->newsletter->getViewsOnOSValue('iOS');
            $clientOperatingSystems['Apple'] = $this->newsletter->getViewsOnOSValue('apple');
            $clientOperatingSystems['Windows'] = $this->newsletter->getViewsOnOSValue('windows');
            $clientOperatingSystems['Android'] = $this->newsletter->getViewsOnOSValue('android');
            $clientOperatingSystems['Other'] = $this->newsletter->getViewsOnOSValue('other');
        }
        
        return array(
            array('name' => 'Apple', 'y' => $clientOperatingSystems['Apple'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['Apple'] * 100 / $totalViews)))),
            array('name' => 'iOS', 'y' => $clientOperatingSystems['iOS'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['iOS'] * 100 / $totalViews)))),
            array('name' => 'Windows', 'y' => $clientOperatingSystems['Windows'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['Windows'] * 100 / $totalViews)))),
            array('name' => 'GNU/Linux', 'y' => $clientOperatingSystems['GNU/Linux'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['GNU/Linux'] * 100 / $totalViews)))),
            array('name' => 'Android', 'y' => $clientOperatingSystems['Android'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['Android'] * 100 / $totalViews)))),
            array('name' => 'Other', 'y' => $clientOperatingSystems['Other'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['Other'] * 100 / $totalViews))))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getCollectionData()
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

        /** @var Newsletter $row */
        foreach ($this->newsletter as $row) {
            $totalViews = $row->getViewsCount();
            $clientOperatingSystems['GNU/Linux'] += $row->getViewsOnOSValue('unix');
            $clientOperatingSystems['iOS'] += $row->getViewsOnOSValue('iOS');
            $clientOperatingSystems['Apple'] += $row->getViewsOnOSValue('apple');
            $clientOperatingSystems['Windows'] += $row->getViewsOnOSValue('windows');
            $clientOperatingSystems['Android'] += $row->getViewsOnOSValue('android');
            $clientOperatingSystems['Other'] += $row->getViewsOnOSValue('other');
        }

        return array(
            array('name' => 'Apple', 'y' => $clientOperatingSystems['Apple'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['Apple'] * 100 / $totalViews)))),
            array('name' => 'iOS', 'y' => $clientOperatingSystems['iOS'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['iOS'] * 100 / $totalViews)))),
            array('name' => 'Windows', 'y' => $clientOperatingSystems['Windows'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['Windows'] * 100 / $totalViews)))),
            array('name' => 'GNU/Linux', 'y' => $clientOperatingSystems['GNU/Linux'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['GNU/Linux'] * 100 / $totalViews)))),
            array('name' => 'Android', 'y' => $clientOperatingSystems['Android'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['Android'] * 100 / $totalViews)))),
            array('name' => 'Other', 'y' => $clientOperatingSystems['Other'], 'percent' => ($totalViews == 0 ? 0 : round(($clientOperatingSystems['Other'] * 100 / $totalViews))))
        );
    }

}