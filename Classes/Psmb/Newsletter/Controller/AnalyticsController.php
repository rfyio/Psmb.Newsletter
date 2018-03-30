<?php
namespace Psmb\Newsletter\Controller;

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
     * @Flow\Inject
     * @var ResourceManager
     */
    protected $resourceManager;

    /**
     * Gathers tracking information by parsing the User-Agent. We create a Newsletter entity which persists unique views, views, OS and Device information.
     *
     * @param string $trackingCode
     * @return string
     */
    public function trackAction($trackingCode) {
        if ($trackingCode !== '') {
            $trackingInfo = explode('|', $trackingCode);
            if (count($trackingInfo) > 1) {
                list($newsletterId, $subscriberId) = $trackingInfo;
                /** @var Newsletter $newsletter */
                $newsletter = $this->newsletterRepository->findByIdentifier($newsletterId);
                /** @var Subscriber $subscriber */
                $subscriber = $this->subscriberRepository->findByIdentifier($subscriberId);

                if ($newsletter) {
                    $newsletter->updateViewCount();
                    $userAgent = $this->request->getHttpRequest()->getHeader('User-Agent');
                    $parser = new RequestParser($userAgent);
                    $newsletter->updateDevice($parser->getDevice());
                    $newsletter->updateOS($parser->getOperatingSystem());

                    if ($subscriber) {
                        $subscriberTracking = $this->subscriberTrackingRepository->findByNewsletterAndSubscriber($newsletter, $subscriber);

                        if (!$subscriberTracking) {
                            $newsletter->updateUniqueViewCount();
                            $subscriberTracking = new SubscriberTracking();
                            $subscriberTracking->setNewsletter($newsletter);
                            $subscriberTracking->setSubscriber($subscriber);
                            $this->subscriberTrackingRepository->add($subscriberTracking);
                        }

                        $subscriberTracking->setSubscriptionIdentifier($newsletter->getSubscriptionIdentifier());
                        $newsletter->updateSentCount();
                        $subscriberTracking->updateViewCount();

                        $this->subscriberTrackingRepository->update($subscriberTracking);
                    } else {
                        $newsletter->updateUniqueViewCount();
                    }
                    $this->newsletterRepository->update($newsletter);
                    $this->persistenceManager->persistAll();
                }
            }
        }
        // Return a pixel
        $fileName = 'resource://Psmb.Newsletter/Public/Images/1x1.png';
        $fp = fopen($fileName, 'rb');

        $this->response->setHeader('Content-Type', 'image/png');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Content-Disposition', 'attachment; filename=1x1.png');
        $this->response->setHeader('Content-Length', filesize($fileName));
        $this->response->setContent($fp);
        return '';
    }
}
