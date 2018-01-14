<?php
namespace Psmb\Newsletter\Domain\Model;

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Utility\Arrays;

/**
 * Class SubscriberTracking
 * @package Psmb\Newsletter\Domain\Model
 * @Flow\Entity
 */
class SubscriberTracking {

    /**
     * @var Subscriber
     * @ORM\ManyToOne
     */
    protected $subscriber;

    /**
     * @var Newsletter
     * @ORM\ManyToOne
     */
    protected $newsletter;

    /**
     * @var integer
     */
    protected $viewCount = 0;

    /**
     * @return string
     */
    public function getPersistenceObjectIdentifier() {
        return $this->Persistence_Object_Identifier;
    }

    /**
     * @return Subscriber
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }

    /**
     * @param Subscriber $subscriber
     */
    public function setSubscriber($subscriber)
    {
        $this->subscriber = $subscriber;
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
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     *
     */
    public function updateViewCount()
    {
        $this->viewCount++;
    }
}