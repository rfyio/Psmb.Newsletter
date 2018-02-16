<?php
namespace Psmb\Newsletter\Controller\Module;

use Psmb\Newsletter\Domain\Model\Subscriber;
use Psmb\Newsletter\Domain\Repository\SubscriberTrackingRepository;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Configuration\ConfigurationManager;
use TYPO3\Flow\Configuration\Source\YamlSource;
use TYPO3\Flow\Package\PackageManagerInterface;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Neos\Controller\Module\AbstractModuleController;
use Psmb\Newsletter\Domain\Repository\SubscriberRepository;

/**
 * Class SubscriberController
 * @package Psmb\Newsletter\Controller\Module
 */
class SubscriberController extends AbstractModuleController
{

    /**
     * @Flow\Inject
     * @var ConfigurationManager
     */
    protected $configurationManager;

    /**
     * @Flow\Inject
     * @var YamlSource
     */
    protected $configurationSource;

    /**
     * @Flow\Inject
     * @var PackageManagerInterface
     */
    protected $packageManager;

    /**
     * @Flow\Inject
     * @var SubscriberRepository
     */
    protected $subscriberRepository;

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

    /**
     * @param string $filter
     * @return string
     */
    public function exportAction($filter = '')
    {
        $subscribers = $filter ? $this->subscriberRepository->findAllByFilter($filter) : $this->subscriberRepository->findAll();

        $output = array();
        $objectProperties = array('email', 'name');
        $output[] = $objectProperties;
        foreach ($subscribers as $singleResult) {
            $row = array();

            $properties = ObjectAccess::getGettableProperties($singleResult);
            foreach ($objectProperties as $propertyName) {
                $property = $properties[$propertyName];
                if (is_string($property)) {
                    $row[$propertyName] = ObjectAccess::getProperty($singleResult, $propertyName);
                }

                if ($property === NULL) {
                    $row[$propertyName] = '';
                }
            }
            $output[] = $row;

        }

        $this->response->setCharset('ISO-8859-1');
        $this->response->setHeader('Content-Type', 'text/x-csv');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Content-Disposition', 'attachment; filename=Subscribers-Export.csv;');
        // Convert to Excel CSV
        $this->response->setContent($this->convertArrayToCsv($output));
        return '';

    }

    /**
     * An edit view for a subscriber
     *
     * @return void
     */
    public function newAction()
    {
        $this->view->assign('subscriptions', $this->subscriptions);
    }

    /**
     * @param Subscriber $subscriber
     */
    public function createAction(Subscriber $subscriber)
    {
        $this->subscriberRepository->add($subscriber);
        $this->redirect('index');
    }


    /**
     * An edit view for a subscriber
     *
     * @param Subscriber $subscriber
     * @return void
     */
    public function editAction(Subscriber $subscriber)
    {
        $trackingRecords = $this->subscriberTrackingRepository->findBySubscriber($subscriber);
        $this->view->assign('subscriber', $subscriber);
        $this->view->assign('subscriptions', $this->subscriptions);
        $this->view->assign('trackingRecords', $trackingRecords);
    }

    /**
     * Update Subscriber
     *
     * @param Subscriber $subscriber
     * @return void
     */
    public function updateAction(Subscriber $subscriber)
    {
        $this->subscriberRepository->update($subscriber);
        $this->redirect('index');
    }

    /**
     * Update Subscriber
     *
     * @param Subscriber $subscriber
     * @return void
     */
    public function deleteAction(Subscriber $subscriber)
    {
        $this->subscriberRepository->remove($subscriber);
        $this->persistenceManager->persistAll();
        $this->redirect('index');
    }

    /**
     * @param array $array
     * @return string
     */
    protected function convertArrayToCsv(array $array) {
        $string = '';
        $delimiter = ';';
        $enclosure = '"';

        foreach ($array as $dataArray) {
            // loop over arrays here to avoid having to do utf8 decoding all over the place
            $writeDelimiter = FALSE;
            foreach ($dataArray as $dataElement) {
                // Replaces a double quote with two double quotes
                $dataElement = str_replace('"', '""', $dataElement);

                // Adds a delimiter before each field (except the first)
                if($writeDelimiter) $string .= $delimiter;

                // Encloses each field with $enclosure and adds it to the string
                $string .= $enclosure . $dataElement . $enclosure;

                // Delimiters are used every time except the first.
                $writeDelimiter = TRUE;
            }
            $string .= "\n";
        }
        return $string;
    }
}