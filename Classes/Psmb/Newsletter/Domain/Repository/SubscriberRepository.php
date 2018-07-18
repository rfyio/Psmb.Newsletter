<?php
namespace Psmb\Newsletter\Domain\Repository;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class SubscriberRepository extends Repository
{

    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'name' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_ASCENDING
    );


    /**
     * @param $filter
     * @return \TYPO3\Flow\Persistence\QueryResultInterface
     * @throws \TYPO3\Flow\Persistence\Exception\InvalidQueryException
     */
    public function findAllByFilter($filter)
    {
        $query = $this->createQuery();

         return $query->matching(
             $query->like('subscriptions', '%"' . $filter . '"%')
         )->execute();
     }

    /**
     * @param string $searchTerm
     * @param array $filter
     * @return \TYPO3\Flow\Persistence\QueryResultInterface
     * @throws \TYPO3\Flow\Persistence\Exception\InvalidQueryException
     */
    public function findAllBySearchTermAndFilter($searchTerm = null, $filter = array())
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

        if (!empty($filter)) {
            $constraints[] = $query->like('subscriptions', '%"' . $filter . '"%');
        }

        return $query->matching(
            $query->logicalOr($constraints)
        )->execute();
    }
}
