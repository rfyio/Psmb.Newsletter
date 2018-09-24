<?php

namespace Psmb\Newsletter\Command;

use Psmb\Newsletter\Domain\Model\Subscription;
use TYPO3\Flow\Annotations as Flow;
use Psmb\Newsletter\Domain\Model\Subscriber;
use Psmb\Newsletter\Domain\Repository\SubscriberRepository;
use Psmb\Newsletter\Service\FusionMailService;
use TYPO3\Flow\Cli\CommandController;

/**
 * @Flow\Scope("singleton")
 */
class NewsletterCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var FusionMailService
     */
    protected $fusionMailService;

    /**
     * @Flow\Inject
     * @var SubscriberRepository
     */
    protected $subscriberRepository;

    /**
     * @Flow\InjectConfiguration(package="TYPO3.Flow", path="http.baseUri")
     * @var string
     */
    protected $baseUri;

    /**
     * @Flow\InjectConfiguration(path="subscriptions")
     * @var array
     */
    protected $subscriptions;

    /**
     * Selects all subscriptions with given interval and sends a letter to each subscriber
     *
     * @param string $subscription Subscription id to send newsletter to
     * @param string $interval Alternatively select all subscriptions with the given interval (useful for cron jobs)
     * @param bool $dryRun DryRun: generate messages but don't send
     * @return void
     */
    public function sendCommand($subscription = null, $interval = null, $dryRun = null)
    {
        $subscriptions = [];
        if ($subscription) {
            $subscriptions = array_filter($this->subscriptions, function ($item) use ($subscription) {
                return $item['identifier'] == $subscription;
            });
        } else if ($interval) {
            $subscriptions = array_filter($this->subscriptions, function ($item) use ($interval) {
                return $item['interval'] == $interval;
            });
        } else {
            $this->outputLine('<error>Either an interval or a subscription must be set</error>');
            $this->outputLine();
            $this->sendAndExit(1);
        }

        array_walk($subscriptions, function ($subscription) use ($dryRun) {
            $this->sendLettersForSubscription($subscription, $dryRun);
        });
    }

    /**
     * Generate a letter for each subscriber in the subscription
     *
     * @param Subscription $subscription
     * @param bool $dryRun
     * @return void
     */
    protected function sendLettersForSubscription(Subscription $subscription, $dryRun)
    {
        // TODO
        $subscribers = $this->subscriberRepository->findAllByFilter($subscription)->toArray();

        $this->outputLine('Sending letters for subscription %s (%s subscribers)', [$subscription->getName(), count($subscribers)]);
        $this->outputLine('-------------------------------------------------------------------------------');

        array_walk($subscribers, function ($subscriber) use ($subscription, $dryRun) {
            $this->outputLine('Sending a letter for %s', [$subscriber->getEmail()]);
            if ($dryRun) {
                $letter = $this->fusionMailService->generateSubscriptionLetter($subscriber, $subscription);
                $this->outputLine(print_r($letter, true));
            } else {
                $this->fusionMailService->generateSubscriptionLetterAndSend($subscriber, $subscription);
            }
        });
    }

}
