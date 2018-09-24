<?php
namespace Psmb\Newsletter\Domain\Repository;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\QueryInterface;
use TYPO3\Flow\Persistence\QueryResultInterface;
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
        'name' => QueryInterface::ORDER_ASCENDING
    );

    /**
     * @param array $manualSubscriptions
     * @return QueryResultInterface
     * @throws \TYPO3\Flow\Persistence\Exception\InvalidQueryException
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
