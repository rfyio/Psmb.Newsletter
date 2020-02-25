<?php
namespace Psmb\Newsletter\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\Annotations as Flow;

/**
 * Thumbnail configuration value object
 */
class ViewsOnOperatingSystem
{
    /**
     * @var integer
     */
    protected $apple;

    /**
     * @var integer
     */
    protected $iOS;

    /**
     * @var integer
     */
    protected $windows;

    /**
     * @var integer
     */
    protected $unix;

    /**
     * @var integer
     */
    protected $android;

    /**
     * @var integer
     */
    protected $other;

    /**
     * ViewsOnOperatingSystem constructor.
     * @param null $apple
     * @param null $iOS
     * @param null $windows
     * @param null $unix
     * @param null $android
     * @param null $other
     */
    public function __construct($apple = null, $iOS = null, $windows = null, $unix = null, $android = null, $other = null)
    {
        $this->apple = $apple ? (integer)$apple : 0;
        $this->iOS = $iOS ? (integer)$iOS : 0;
        $this->windows = $windows ? (integer)$windows : 0;
        $this->unix = $unix ? (integer)$unix : 0;
        $this->android = $android ? (integer)$android : 0;
        $this->other = $other ? (integer)$other : 0;
    }

    /**
     * @return int
     */
    public function getApple()
    {
        return $this->apple;
    }

    /**
     * @param int $apple
     */
    public function setApple($apple)
    {
        $this->apple = $apple;
    }

    /**
     * @return int
     */
    public function getIOS()
    {
        return $this->iOS;
    }

    /**
     * @param int $iOS
     */
    public function setIOS($iOS)
    {
        $this->iOS = $iOS;
    }

    /**
     * @return int
     */
    public function getWindows()
    {
        return $this->windows;
    }

    /**
     * @param int $windows
     */
    public function setWindows($windows)
    {
        $this->windows = $windows;
    }

    /**
     * @return int
     */
    public function getUnix()
    {
        return $this->unix;
    }

    /**
     * @param int $unix
     */
    public function setUnix($unix)
    {
        $this->unix = $unix;
    }

    /**
     * @return int
     */
    public function getAndroid()
    {
        return $this->android;
    }

    /**
     * @param int $android
     */
    public function setAndroid($android)
    {
        $this->android = $android;
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
    public function setOther()
    {
        $this->other++;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = array_filter([
            'apple' => $this->getApple(),
            'iOS' => $this->getIOS(),
            'windows' => $this->getWindows(),
            'unix' => $this->getUnix(),
            'android' => $this->getAndroid(),
            'other' => $this->getOther(),
        ], function ($value) {
            return $value !== null;
        });
        ksort($data);
        return $data;
    }
}
