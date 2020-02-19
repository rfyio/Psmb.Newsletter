<?php
namespace Psmb\Newsletter\Domain\Repository;

use Psmb\Newsletter\Domain\Model\Newsletter;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;
use Neos\ContentRepository\Domain\Model\NodeData;

/**
 * @Flow\Scope("singleton")
 */
class LinkRepository extends Repository
{
    /**
     * @param Newsletter $newsletter
     * @param NodeData $nodeData
     * @return object|null
     */
    public function findByNewsletterAndNode(Newsletter $newsletter, NodeData $nodeData)
    {
        $query = $this->createQuery();

        $constraints = [];
        $constraints[] = $query->equals('newsletter', $newsletter);
        $constraints[] = $query->equals('node', $nodeData);
        return $query->matching(
            $query->logicalAnd($constraints)
        )->execute()->getFirst();
    }
}
