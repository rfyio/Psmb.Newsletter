<?php
namespace Psmb\Newsletter\Domain\Repository;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class SubscriptionRepository extends Repository
{

    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'name' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_ASCENDING
    );
}
