<?php
namespace Psmb\Newsletter\Domain\Model;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Neos\Utility\Arrays;

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
     * @var string
     */
    protected $subscriptionIdentifier;

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