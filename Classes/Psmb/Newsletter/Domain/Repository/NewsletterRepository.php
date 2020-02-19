<?php
namespace Psmb\Newsletter\Domain\Repository;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class NewsletterRepository extends Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'publicationDate' => \Neos\Flow\Persistence\QueryInterface::ORDER_DESCENDING
    );
}
