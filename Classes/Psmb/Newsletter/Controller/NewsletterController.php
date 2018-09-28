<?php
namespace Psmb\Newsletter\Controller;

use Flowpack\JobQueue\Common\Annotations as Job;
use Psmb\Newsletter\Domain\Model\Subscription;
use Psmb\Newsletter\Domain\Repository\SubscriptionRepository;
use TYPO3\Flow\Annotations as Flow;
use Psmb\Newsletter\Domain\Model\Subscriber;
use Psmb\Newsletter\Domain\Repository\SubscriberRepository;
use Psmb\Newsletter\Service\FusionMailService;
use TYPO3\Flow\Mvc\View\JsonView;
use TYPO3\Flow\I18n\Service as I18nService;
use TYPO3\Flow\I18n\Translator;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

class NewsletterController extends ActionController
{
    /**
     * @Flow\Inject
     * @var I18nService
     */
    protected $i18nService;

    /**
     * @Flow\Inject
     * @var Translator
     */
    protected $translator;
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
     * @var array
     */
    protected $viewFormatToObjectNameMap = array(
        'json' => JsonView::class
    );

    /**
     * Get manual subscriptions for AJAX sending
     *
     * @return void
     */
    public function getSubscriptionsAction() {
        $manualSubscriptions = array_filter($this->subscriptions, function ($item) {
            return $item['interval'] == 'manual';
        });

        $subscriptions = $this->subscriptionRepository->findByManualSubscriptions($manualSubscriptions);

        $subscriptionsJsonArray = array_map(function ($item) {
            /** @var  Subscription $item */
            return ['label' => $item->getName(), 'value' => $item->getPersistenceObjectIdentifier()];
        }, $subscriptions->toArray());
        $this->view->assign('value', array_values($subscriptionsJsonArray));
    }

    /**
     * Registers a new subscriber
     *
     * @param Subscription $subscription Subscription to send newsletter to
     * @param NodeInterface $node Node of the current newsletter item
     * @return void
     */
    public function sendAction(Subscription $subscription, NodeInterface $node)
    {
        $this->sendLettersForSubscription($subscription, $node);
        $this->view->assign('value', ['status' => 'success']);
    }

    /**
     * Sends a test letter for subscription
     *
     * @param Subscription $subscription Subscription id to send newsletter to
     * @param NodeInterface $node Node of the current newsletter item
     * @param string $email Test email address
     * @return void
     */
    public function testSendAction(Subscription $subscription, NodeInterface $node, $email)
    {
        $subscriber = new Subscriber();
        $subscriber->setEmail($email);
        $subscriber->setName('Test User');

        $this->fusionMailService->generateSubscriptionLetterAndSend($subscriber, $subscription, $node);

        $this->view->assign('value', ['status' => 'success']);
    }

    /**
     * Generate a letter for each subscriber in the subscription
     *
     * @Job\Defer(queueName="psmb-newsletter-web")
     * @param Subscription $subscription
     * @param NodeInterface $node Node of the current newsletter item
     * @return void
     */
    public function sendLettersForSubscription(Subscription $subscription, $node)
    {
        $subscribers = $this->subscriberRepository->findAllBySearchTermAndSubscription(null, $subscription)->toArray();

        array_walk($subscribers, function ($subscriber) use ($subscription, $node) {
            $this->fusionMailService->generateSubscriptionLetterAndSend($subscriber, $subscription, $node);
        });
    }

}
