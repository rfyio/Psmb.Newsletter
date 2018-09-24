<?php

namespace Psmb\Newsletter\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * Subscriber
 *
 * @Flow\Entity
 */
class Subscription
{

    /**
     * @var string
     * @ORM\Column(length=80)
     * @Flow\Validate(type="StringLength", options={"minimum"=1, "maximum"=80})
     */
    protected $fusionIdentifier;

    /**
     * @var string
     * @Flow\Validate(type="Text")
     * @Flow\Validate(type="StringLength", options={"minimum"=1, "maximum"=80})
     * @ORM\Column(length=80)
     */
    protected $name;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection<\Psmb\Newsletter\Domain\Model\Subscriber>
     * @ORM\ManyToMany(mappedBy="subscribedSubscriptions", cascade={"persist"})
     * @ORM\OrderBy({"email"="ASC"})
     * @Flow\Lazy
     */
    protected $subscribers;

    /**
     *
     */
    public function __construct()
    {
        $this->subscribers = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getPersistenceObjectIdentifier()
    {
        return $this->Persistence_Object_Identifier;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFusionIdentifier()
    {
        return $this->fusionIdentifier;
    }

    /**
     * @param string $fusionIdentifier
     */
    public function setFusionIdentifier($fusionIdentifier)
    {
        $this->fusionIdentifier = $fusionIdentifier;
    }

    /**
     * @return Collection
     */
    public function getSubscribers()
    {
        return $this->subscribers;
    }

    /**
     * @param Collection $subscribers
     */
    public function setSubscribers($subscribers)
    {
        $this->subscribers = $subscribers;
    }

    /**
     * @param Subscriber $subscriber
     */
    public function addSubscriber(Subscriber $subscriber)
    {
        if (!$this->subscribers->contains($subscriber)) {
            $this->subscribers->add($subscriber);
        }
    }

    /**
     * @param Subscriber $subscriber
     */
    public function removeSubscriber(Subscriber $subscriber)
    {
        $this->subscribers->removeElement($subscriber);
    }
}
