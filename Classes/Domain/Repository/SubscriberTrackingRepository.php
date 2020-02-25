<?php
namespace Psmb\Newsletter\Domain\Repository;

use Psmb\Newsletter\Domain\Model\Newsletter;
use Psmb\Newsletter\Domain\Model\Subscriber;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class SubscriberTrackingRepository extends Repository
{
    /**
     * @param Newsletter $newsletter
     * @param Subscriber $subscriber
     * @return object
     */
    public function findByNewsletterAndSubscriber(Newsletter $newsletter, Subscriber $subscriber)
    {
        $query = $this->createQuery();

        return $query->matching(
            $query->logicalAnd(
                [
                    $query->equals('newsletter', $newsletter),
                    $query->equals('subscriber', $subscriber)
                ]
            )
        )->execute()
        ->getFirst();
    }

}
