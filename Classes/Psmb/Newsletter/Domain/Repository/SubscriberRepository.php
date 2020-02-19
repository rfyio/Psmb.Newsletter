<?php

namespace Psmb\Newsletter\Domain\Repository;

use Psmb\Newsletter\Domain\Model\Subscription;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class SubscriberRepository extends Repository
{

    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'name' => \Neos\Flow\Persistence\QueryInterface::ORDER_ASCENDING
    );


    /**
     * @param $filter
     * @return \Neos\Flow\Persistence\QueryResultInterface
     * @throws \Neos\Flow\Persistence\Exception\InvalidQueryException
     */
    public function findAllByFilter($filter)
    {
        $query = $this->createQuery();

        return $query->matching(
            $query->like('subscriptions', '%"' . $filter . '"%')
        )->execute();
    }

    /**
     * @param string|null $searchTerm
     * @param Subscription|null $subscription
     * @return \Neos\Flow\Persistence\QueryResultInterface
     * @throws \Neos\Flow\Persistence\Exception\InvalidQueryException
     */
    public function findAllBySearchTermAndSubscription($searchTerm = null, Subscription $subscription = null)
    {
        $query = $this->createQuery();

        $constraints = [];
        if ($searchTerm) {
            $constraints = [
                $query->like('name', '%' . $searchTerm . '%'),
                $query->like('email', '%' . $searchTerm . '%'),
                $query->like('metadata', '%' . $searchTerm . '%')
            ];
        }

        if ($subscription) {
            $constraints[] = $query->contains('subscribedSubscriptions', $subscription);
        }

        return $query->matching(
            $query->logicalOr($constraints)
        )->execute();
    }

    /**
     * @param Subscription $subscription
     * @return int
     * @throws \Neos\Flow\Persistence\Exception\InvalidQueryException
     */
    public function countBySubscription(Subscription $subscription): int
    {
        $query = $this->createQuery();

        return $query->matching($query->contains('subscribedSubscriptions', $subscription))->count();
    }
}
