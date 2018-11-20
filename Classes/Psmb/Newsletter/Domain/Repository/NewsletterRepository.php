<?php
namespace Psmb\Newsletter\Domain\Repository;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class NewsletterRepository extends Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'publicationDate' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_DESCENDING
    );
}
