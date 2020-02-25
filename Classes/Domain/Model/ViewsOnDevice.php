<?php
namespace Psmb\Newsletter\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\Annotations as Flow;

/**
 * Thumbnail configuration value object
 */
class ViewsOnDevice
{
    /**
     * @var integer
     */
    protected $desktop;

    /**
     * @var integer
     */
    protected $tablet;

    /**
     * @var integer
     */
    protected $smartphone;

    /**
     * @var integer
     */
    protected $other;

    /**
     * ViewsOnDevice constructor.
     * @param null $desktop
     * @param null $tablet
     * @param null $smartphone
     * @param null $other
     */
    public function __construct($desktop = NULL, $tablet = NULL, $smartphone = NULL, $other = NULL)
    {
        $this->desktop = $desktop ? (integer)$desktop : 0;
        $this->tablet = $tablet ? (integer)$tablet : 0;
        $this->smartphone = $smartphone ? (integer)$smartphone : 0;
        $this->other = $other ? (integer)$other : 0;
    }

    /**
     * @return int
     */
    public function getDesktop()
    {
        return $this->desktop;
    }

    /**
     * @param int $desktop
     */
    public function setDesktop($desktop)
    {
        $this->desktop = $desktop;
    }

    /**
     * @return int
     */
    public function getTablet()
    {
        return $this->tablet;
    }

    /**
     * @param int $tablet
     */
    public function setTablet($tablet)
    {
        $this->tablet = $tablet;
    }

    /**
     * @return int
     */
    public function getSmartphone()
    {
        return $this->smartphone;
    }

    /**
     * @param int $smartphone
     */
    public function setSmartphone($smartphone)
    {
        $this->smartphone = $smartphone;
    }

    /**
     * @return int
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * @param int $other
     */
    public function setOther($other)
    {
        $this->other = $other;
    }

    /**
     * @param $type
     */
    public function updateDeviceCounter($type)
    {
        switch($type) {
            case 'desktop':
                $this->desktop;
                break;
            case 'tablet':
                $this->tablet++;
                break;
            case 'smartphone':
                $this->smartphone++;
                break;
            default:
                $this->other++;
                break;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = array_filter([
            'desktop' => $this->getDesktop(),
            'tablet' => $this->getTablet(),
            'smartphone' => $this->getSmartphone(),
            'other' => $this->getOther()
        ], function ($value) {
            return $value !== null;
        });
        ksort($data);
        return $data;
    }
}