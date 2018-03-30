<?php
namespace Psmb\Newsletter\Controller\Module;

use Psmb\Newsletter\Domain\Model\Newsletter;
use Psmb\Newsletter\Domain\Model\Subscriber;
use Psmb\Newsletter\Domain\Model\SubscriberTracking;
use Psmb\Newsletter\Domain\Repository\NewsletterRepository;
use Psmb\Newsletter\Domain\Repository\SubscriberRepository;
use Psmb\Newsletter\Domain\Repository\SubscriberTrackingRepository;
use Psmb\Newsletter\Parser\RequestParser;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Resource\ResourceManager;

/**
 * Class AnalyticsController
 * @Flow\Scope("singleton")
 * @package Psmb\Newsletter\Controller
 */
class AnalyticsController extends ActionController
{

    /**
     * @Flow\Inject
     * @var SubscriberRepository
     */
    protected $subscriberRepository;

    /**
     * @Flow\Inject
     * @var NewsletterRepository
     */
    protected $newsletterRepository;

    /**
     * @Flow\Inject
     * @var SubscriberTrackingRepository
     */
    protected $subscriberTrackingRepository;

    /**
     * @Flow\InjectConfiguration(package="Psmb.Newsletter", path="subscriptions")
     * @var string
     */
    protected $subscriptions;

    /**
     * @param string $filter
     */
    public function indexAction($filter = '')
    {
        $subscribers = $filter ? $this->subscriberRepository->findAllByFilter($filter) : $this->subscriberRepository->findAll();

        $this->view->assign('filter', $filter);
        $this->view->assign('subscribers', $subscribers);
        $this->view->assign('subscriptions', $this->subscriptions);
    }
}
