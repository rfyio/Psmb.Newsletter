<?php
namespace Psmb\Newsletter\Controller\Module\Newsletter;

use Psmb\Newsletter\Domain\Model\Subscriber;
use Psmb\Newsletter\Domain\Model\Subscription;
use Psmb\Newsletter\Domain\Repository\SubscriberTrackingRepository;
use Psmb\Newsletter\Domain\Repository\SubscriptionRepository;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Configuration\ConfigurationManager;
use TYPO3\Flow\Configuration\Source\YamlSource;
use TYPO3\Flow\Mvc\View\ViewInterface;
use TYPO3\Flow\Package\PackageManagerInterface;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Media\Domain\Session\BrowserState;
use TYPO3\Neos\Controller\Module\AbstractModuleController;
use Psmb\Newsletter\Domain\Repository\SubscriberRepository;

/**
 * Class SubscriptionController
 * @package Psmb\Newsletter\Controller\Module
 */
class SubscriptionController extends AbstractModuleController
{
    /**
     * @Flow\InjectConfiguration(package="Psmb.Newsletter", path="subscriptions")
     * @var array
     */
    protected $subscriptions;

    /**
     * @Flow\Inject
     * @var SubscriptionRepository
     */
    protected $subscriptionRepository;

    /**
     * @Flow\Inject(lazy = false)
     * @var BrowserState
     */
    protected $browserState;

    /**
     * Set common variables on the view
     *
     * @param ViewInterface $view
     * @return void
     */
    protected function initializeView(ViewInterface $view)
    {
        $view->assignMultiple(array(
            'sortBy' => $this->browserState->get('sortBy'),
            'sortDirection' => $this->browserState->get('sortDirection'),
            'filter' => $this->browserState->get('filter')
        ));
    }

    /**
     * @param string $filter
     * @param string $sortBy
     * @param string $sortDirection
     * @param string $searchTerm
     */
    public function indexAction($filter = null, $sortBy = null, $sortDirection = null, $searchTerm = null)
    {
        $subscriptions = $this->subscriptionRepository->findAll();

        $this->view->assignMultiple([
            'subscriptions' => $subscriptions,
        ]);
    }

    /**
     * An edit view for a subscription
     *
     * @return void
     */
    public function newAction()
    {
        $baseSubscriptions = [];
        foreach ($this->subscriptions as $subscription) {
            $baseSubscriptions[$subscription['identifier']] = $subscription['label'];
        }

        $this->view->assign('baseSubscriptions', $baseSubscriptions);
    }

    /**
     * @param Subscription $subscription
     * @throws \TYPO3\Flow\Mvc\Exception\StopActionException
     * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
     */
    public function createAction(Subscription $subscription)
    {
        $this->subscriptionRepository->add($subscription);
        $this->redirect('index');
    }

    /**
     * An edit view for a subscription
     *
     * @param Subscription $subscription
     * @return void
     */
    public function editAction(Subscription $subscription)
    {
        $baseSubscriptions = [];
        foreach ($this->subscriptions as $baseSubscription) {
            $baseSubscriptions[$baseSubscription['identifier']] = $baseSubscription['label'];
        }

        $this->view->assign('subscription', $subscription);
        $this->view->assign('baseSubscriptions', $baseSubscriptions);
    }

    /**
     * Update Subscriber
     * @param Subscription $subscription
     * @throws \TYPO3\Flow\Mvc\Exception\StopActionException
     * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
     */
    public function updateAction(Subscription $subscription)
    {
        $this->subscriptionRepository->update($subscription);
        $this->addFlashMessage('Subscription was updated.');
        $this->redirect('index');
    }

    /**
     * Delete Subscription
     * @param Subscription $subscription
     * @throws \TYPO3\Flow\Mvc\Exception\StopActionException
     * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
     */
    public function deleteAction(Subscription $subscription)
    {
        $this->subscriptionRepository->remove($subscription);
        $this->persistenceManager->persistAll();
        $this->addFlashMessage('Subscription was removed.');
        $this->redirect('index');
    }
}