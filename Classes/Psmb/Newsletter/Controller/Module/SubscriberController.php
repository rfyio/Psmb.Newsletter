<?php
namespace Psmb\Newsletter\Controller\Module;

use Psmb\Newsletter\Domain\Model\Subscriber;
use Psmb\Newsletter\Domain\Repository\SubscriberTrackingRepository;
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
     * @throws \TYPO3\Flow\Persistence\Exception\InvalidQueryException
     */
    public function indexAction($filter = null, $sortBy = null, $sortDirection = null, $searchTerm = null)
    {
        if ($sortBy !== null) {
            $this->browserState->set('sortBy', $sortBy);
            $this->view->assign('sortBy', $sortBy);
        }
        if ($sortDirection !== null) {
            $this->browserState->set('sortDirection', $sortDirection);
            $this->view->assign('sortDirection', $sortDirection);
        }

        if ($filter !== null) {
            $this->browserState->set('filter', $filter);
            $this->view->assign('filter', $filter);
        }

        if ($searchTerm !== null) {
            $this->browserState->set('searchTerm', $searchTerm);
            $this->view->assign('searchTerm', $searchTerm);
        }

        switch ($this->browserState->get('sortBy')) {
            case 'Name':
                $this->subscriberRepository->setDefaultOrderings(array('name' => $this->browserState->get('sortDirection') ?: 'ASC'));
                break;
            case 'Email':
            default:
                $this->subscriberRepository->setDefaultOrderings(array('email' => $this->browserState->get('sortDirection') ?: 'ASC'));
                break;
        }

        if ($searchTerm !== null || $filter !== null) {
            $subscribers = $this->subscriberRepository->findAllBySearchTermAndFilter($searchTerm, $filter);
        } else {
            $subscribers = $this->subscriberRepository->findAll();
        }

        $this->view->assignMultiple([
            'subscribers' => $subscribers,
            'subscriptions' => $this->subscriptions,
            'argumentNamespace' => $this->request->getArgumentNamespace(),
        ]);
    }

    /**
     * @param string $filter
     * @param string $sortBy
     * @param string $sortDirection
     * @param string $searchTerm
     * @return string
     * @throws \TYPO3\Flow\Persistence\Exception\InvalidQueryException
     * @throws \TYPO3\Flow\Reflection\Exception\PropertyNotAccessibleException
     */
    public function exportAction($filter = null, $sortBy = null, $sortDirection = null, $searchTerm = null)
    {
        if ($sortBy !== null) {
            $this->browserState->set('sortBy', $sortBy);
            $this->view->assign('sortBy', $sortBy);
        }
        if ($sortDirection !== null) {
            $this->browserState->set('sortDirection', $sortDirection);
            $this->view->assign('sortDirection', $sortDirection);
        }

        if ($filter !== null) {
            $this->browserState->set('filter', $filter);
            $this->view->assign('filter', $filter);
        }

        if ($searchTerm !== null) {
            $this->browserState->set('searchTerm', $searchTerm);
            $this->view->assign('searchTerm', $searchTerm);
        }

        switch ($this->browserState->get('sortBy')) {
            case 'Name':
                $this->subscriberRepository->setDefaultOrderings(array('name' => $this->browserState->get('sortDirection') ?: 'ASC'));
                break;
            case 'Email':
            default:
                $this->subscriberRepository->setDefaultOrderings(array('email' => $this->browserState->get('sortDirection') ?: 'ASC'));
                break;
        }

        $subscribers = $filter || $searchTerm ? $this->subscriberRepository->findAllBySearchTermAndFilter($searchTerm, $filter) : $this->subscriberRepository->findAll();

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
     * @throws \TYPO3\Flow\Mvc\Exception\StopActionException
     * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
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
     * @param Subscriber $subscriber
     * @throws \TYPO3\Flow\Mvc\Exception\StopActionException
     * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
     */
    public function updateAction(Subscriber $subscriber)
    {
        $this->subscriberRepository->update($subscriber);
        $this->redirect('index');
    }

    /**
     * Delete Subscriber
     * @param Subscriber $subscriber
     * @throws \TYPO3\Flow\Mvc\Exception\StopActionException
     * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
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