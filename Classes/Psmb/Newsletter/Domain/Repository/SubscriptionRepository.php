<?php
namespace Psmb\Newsletter\Domain\Repository;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Flow\Persistence\QueryResultInterface;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class SubscriptionRepository extends Repository
{

    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'name' => QueryInterface::ORDER_ASCENDING
    );

    /**
     * @param array $manualSubscriptions
     * @return QueryResultInterface
     * @throws \Neos\Flow\Persistence\Exception\InvalidQueryException
     */
    public function findByManualSubscriptions(array $manualSubscriptions): QueryResultInterface
    {
        $findSubscriptions = array_map(function ($item) {
            return $item['identifier'];
        }, $manualSubscriptions);

        $query = $this->createQuery();
        return $query->matching($query->in('fusionIdentifier', $findSubscriptions))->execute(true);
    }
}
