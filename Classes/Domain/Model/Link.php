<?php

namespace Psmb\Newsletter\Domain\Model;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Neos\Utility\Arrays;
use Neos\ContentRepository\Domain\Model\NodeData;

/**
 * Newsletter
 *
 * @Flow\Entity
 */
class Link
{

    /**
     * @var Newsletter
     * @ORM\ManyToOne(inversedBy="links")
     */
    protected $newsletter;

    /**
     * @ORM\ManyToOne(inversedBy="dimensions")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var NodeData
     */
    protected $node;

    /**
     * @var integer
     * @Flow\Validate(type="Integer")
     */
    protected $viewsCount = 0;

    /**
     * @var array<string>
     * @ORM\Column(type="flow_json_array")
     */
    protected $viewsOnDevice;

    /**
     * @var array<string>
     * @ORM\Column(type="flow_json_array")
     */
    protected $viewsOnOS;

    /**
     * Newsletter constructor.
     * @param ViewsOnDevice $viewsOnDevice
     * @param ViewsOnOperatingSystem $viewsOnOperatingSystem
     */
    public function __construct(ViewsOnDevice $viewsOnDevice, ViewsOnOperatingSystem $viewsOnOperatingSystem)
    {
        $this->setViewsOnDevice($viewsOnDevice);
        $this->setViewsOnOS($viewsOnOperatingSystem);
    }

    /**
     * @return string
     */
    public function getPersistenceObjectIdentifier()
    {
        return $this->Persistence_Object_Identifier;
    }

    /**
     * @return NodeData
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param NodeData $node
     */
    public function setNode($node)
    {
        $this->node = $node;
    }

    /**
     * @return Newsletter
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * @param Newsletter $newsletter
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * @return int
     */
    public function getViewsCount()
    {
        return $this->viewsCount;
    }

    /**
     * One up view count
     */
    public function updateViewCount()
    {
        $this->viewsCount++;
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function getViewsOnDeviceValue($value)
    {
        return Arrays::getValueByPath($this->viewsOnDevice, $value);
    }

    /**
     * @param ViewsOnDevice $viewsOnDevice
     */
    public function setViewsOnDevice(ViewsOnDevice $viewsOnDevice)
    {
        $this->viewsOnDevice = $viewsOnDevice->toArray();
    }

    /**
     * @param string $value
     * @return array
     */
    public function getViewsOnOSValue($value)
    {
        return Arrays::getValueByPath($this->viewsOnOS, $value);
    }

    /**
     * @param ViewsOnOperatingSystem $viewsOnOS
     */
    public function setViewsOnOS(ViewsOnOperatingSystem $viewsOnOS)
    {
        $this->viewsOnOS = $viewsOnOS->toArray();
    }

    /**
     * @param $type
     */
    public function updateDevice($type)
    {
        $count = Arrays::getValueByPath($this->viewsOnDevice, $type) + 1;
        $this->viewsOnDevice = Arrays::setValueByPath($this->viewsOnDevice, $type, $count);
    }

    /**
     * @param $os
     */
    public function updateOS($os)
    {
        $count = Arrays::getValueByPath($this->viewsOnOS, $os) + 1;
        $this->viewsOnOS = Arrays::setValueByPath($this->viewsOnOS, $os, $count);
    }
}
