<?php

namespace Psmb\Newsletter\Domain\Model;

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Utility\Arrays;
use TYPO3\TYPO3CR\Domain\Model\NodeData;

/**
 * Newsletter
 *
 * @Flow\Entity
 */
class Newsletter
{

    /**
     * @ORM\ManyToOne(inversedBy="dimensions")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var NodeData
     */
    protected $node;

    /**
     * @var string
     */
    protected $subscriptionIdentifier;

    /**
     * @var \DateTime
     * @Flow\Validate(type="\DateTime")
     * @ORM\Column(nullable=true)
     */
    protected $publicationDate;

    /**
     * @var integer
     * @Flow\Validate(type="Integer")
     */
    protected $sentCount = 0;

    /**
     * @var integer
     * @Flow\Validate(type="Integer")
     */
    protected $viewsCount = 0;

    /**
     * @var integer
     * @Flow\Validate(type="Integer")
     */
    protected $uniqueViewCount = 0;

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
     * @return string
     */
    public function getSubscriptionIdentifier()
    {
        return $this->subscriptionIdentifier;
    }

    /**
     * @param string $subscriptionIdentifier
     */
    public function setSubscriptionIdentifier($subscriptionIdentifier)
    {
        $this->subscriptionIdentifier = $subscriptionIdentifier;
    }

    /**
     * @return \DateTime
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * @param \DateTime $publicationDate
     */
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
    }


    /**
     * @return int
     */
    public function getSentCount()
    {
        return $this->sentCount;
    }

    /**
     * One up sent count
     */
    public function updateSentCount()
    {
        $this->sentCount++;
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
     * @return int
     */
    public function getUniqueViewCount()
    {
        return $this->uniqueViewCount;
    }

    /**
     * One up unique view count
     */
    public function updateUniqueViewCount()
    {
        $this->uniqueViewCount++;
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
