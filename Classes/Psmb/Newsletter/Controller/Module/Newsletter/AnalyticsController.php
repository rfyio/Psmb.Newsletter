<?php
namespace Psmb\Newsletter\Controller\Module\Newsletter;

use Psmb\Newsletter\Domain\Model\Newsletter;
use Psmb\Newsletter\Domain\Model\Subscriber;
use Psmb\Newsletter\Domain\Model\SubscriberTracking;
use Psmb\Newsletter\Domain\Repository\NewsletterRepository;
use Psmb\Newsletter\Domain\Repository\SubscriberRepository;
use Psmb\Newsletter\Domain\Repository\SubscriberTrackingRepository;
use Psmb\Newsletter\Parser\RequestParser;
use Psmb\Newsletter\Service\Reporting;
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\View\JsonView;
use TYPO3\Flow\Resource\ResourceManager;
use TYPO3\Fluid\View\TemplateView;

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
     * @var Reporting
     * @Flow\Inject
     */
    protected $reporting;

    /**
     * @Flow\InjectConfiguration(package="Psmb.Newsletter", path="subscriptions")
     * @var string
     */
    protected $subscriptions;

    /**
     * @var array
     */
    protected $viewFormatToObjectNameMap = array(
        'html' => TemplateView::class,
        'json' => JsonView::class
    );

    /**
     * @param string $filter
     */
    public function indexAction($filter = '')
    {
        $this->view->assignMultiple([
            'filter' => $filter,
            'subscriptions' => $this->subscriptions,
            'newsletters' => $filter !== '' ? $this->newsletterRepository->findBySubscriptionIdentifier($filter) : $this->newsletterRepository->findAll(),
        ]);
    }

    /**
     * @param Newsletter $newsletter
     * @param string $type
     * @param string $filter
     * @throws \TYPO3\TYPO3CR\Exception\NodeException
     */
    public function dataSourceAction(Newsletter $newsletter = null, $type = '', $filter = '')
    {
        if ($newsletter instanceof Newsletter) {
            $result = $this->reporting->getNewsletterStatistics($newsletter, $type);
        } else {
            $result = $this->reporting->getGlobalStatistics($type, ['filter' => $filter]);
        }
        $this->view->assign('value', $result);
    }

    /**
     * @param Newsletter $newsletter
     */
    public function showAction(Newsletter $newsletter)
    {
        $this->view->assignMultiple([
            'newsletters' => $this->newsletterRepository->findBySubscriptionIdentifier($newsletter->getSubscriptionIdentifier()),
            'newsletter' => $newsletter,
        ]);
    }
}
