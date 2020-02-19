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
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\View\JsonView;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\FluidAdaptor\View\TemplateView;

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
     * @throws \Neos\ContentRepository\Exception\NodeException
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
