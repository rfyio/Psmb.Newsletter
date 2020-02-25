<?php
namespace Psmb\Newsletter\Command;

use Psmb\Newsletter\Domain\Model\Subscription;
use Neos\Flow\Annotations as Flow;
use Psmb\Newsletter\Domain\Model\Subscriber;
use Psmb\Newsletter\Domain\Repository\SubscriptionRepository;
use Psmb\Newsletter\Domain\Repository\SubscriberRepository;
use Neos\Flow\Cli\CommandController;

/**
 * @Flow\Scope("singleton")
 */
class MigrateCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var SubscriberRepository
     */
    protected $subscriberRepository;

    /**
     * @Flow\Inject
     * @var SubscriptionRepository
     */
    protected $subscriptionRepository;

    /**
     * @Flow\InjectConfiguration(path="subscriptions")
     * @var array
     */
    protected $subscriptions;

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Persistence\PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * Migrate all subscriptions from yaml to persistent entity
     *
     * @return void
     */
    public function subscriptionsCommand()
    {
        foreach ($this->subscriptions as $yamlSubscription) {
            if ($count = $this->subscriptionRepository->countByFusionIdentifier($yamlSubscription['identifier']) === 0) {
                $subscription = new Subscription();
                $subscription->setName($yamlSubscription['label']);
                $subscription->setFusionIdentifier($yamlSubscription['identifier']);
                $this->subscriptionRepository->add($subscription);
            } else {
                $this->outputLine(\sprintf('<error>Subscription %s already exists</error>', $yamlSubscription['identifier']));
            }
        }

        $this->persistenceManager->persistAll();

        $subscribers = $this->subscriberRepository->findAll();

        /** @var Subscriber $subscriber */
        foreach($subscribers as $subscriber) {

            if ($subscriptions = $subscriber->getSubscriptions()) {
                foreach ($subscriptions as $stringSubscription) {
                    /** @var Subscription $subscription */
                    $subscription = $this->subscriptionRepository->findByFusionIdentifier($stringSubscription)->getFirst();

                    if ($subscription instanceof Subscription) {
                        $subscriber->addSubscription($subscription);
                        $subscription->addSubscriber($subscriber);

                        $this->subscriptionRepository->update($subscription);
                        $this->subscriberRepository->update($subscriber);
                    } else {
                        $this->outputLine(\sprintf('<error>Subscription %s does not exists!!</error>', $stringSubscription));
                    }
                }
            }

        }
    }

}
