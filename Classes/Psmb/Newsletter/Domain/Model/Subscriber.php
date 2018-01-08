<?php
namespace Psmb\Newsletter\Domain\Model;

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * Subscriber
 *
 * @Flow\Entity
 */
class Subscriber {

    /**
     * @var string
     * @ORM\Column(length=80)
     * @Flow\Identity
     * @Flow\Validate(type="EmailAddress")
     * @Flow\Validate(type="StringLength", options={"minimum"=1, "maximum"=80})
     */
    protected $email;

    /**
	 * @var string
	 * @Flow\Validate(type="Text")
	 * @Flow\Validate(type="StringLength", options={"minimum"=1, "maximum"=80})
	 * @ORM\Column(length=80)
	 */
	protected $name;

	/**
	 * @var array
	 */
	protected $subscriptions;

	/**
	 * @var array
	 */
	protected $metadata;

    /**
     * @var array<string>
     * @ORM\Column(type="flow_json_array")
     */
	protected $trackingInfo;

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param array $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

	/**
	 * @return string
	 */
	public function getPersistenceObjectIdentifier() {
		return $this->Persistence_Object_Identifier;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param string $email
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @return array
	 */
	public function getSubscriptions() {
		return $this->subscriptions;
	}

	/**
	 * @param array $subscriptions
	 * @return void
	 */
	public function setSubscriptions($subscriptions) {
		$this->subscriptions = $subscriptions;
	}

    /**
     * @return array
     */
    public function getTrackingInfo()
    {
        return $this->trackingInfo;
    }

    /**
     * @param array $trackingInfo
     */
    public function setTrackingInfo($trackingInfo)
    {
        $this->trackingInfo = $trackingInfo;
    }
}
