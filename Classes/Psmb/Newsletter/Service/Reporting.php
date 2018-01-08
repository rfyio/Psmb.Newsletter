<?php
namespace Psmb\Newsletter\Service;

use Psmb\Newsletter\Domain\Dto\ColumnDataResult;
use Psmb\Newsletter\Domain\Dto\DeviceDataResult;
use Psmb\Newsletter\Domain\Dto\OperatingSystemDataResult;
use Psmb\Newsletter\Domain\Model\Newsletter;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ControllerContext;
use TYPO3\Flow\Persistence\Generic\PersistenceManager;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\Neos\Service\Controller\AbstractServiceController;
use Psmb\Newsletter\Domain\Repository\NewsletterRepository;
use Psmb\Newsletter\Exception\StatisticsNotAvailableException;

/**
 * Class Reporting
 * @package Psmb\Newsletter\Service
 */
class Reporting extends AbstractServiceController
{
    /**
     * @Flow\Inject
     * @var \TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface
     */
    protected $contextFactory;

    /**
     * @Flow\Inject
     * @var NewsletterRepository
     */
    protected $newsletterRepository;

    /**
     * @var PersistenceManager
     * @Flow\Inject
     */
    protected $persistenceManager;

    /**
     * Get a Information stream based on requested node and view
     * @param $node NodeInterface
     * @param $arguments array
     * @return DataResult
     */
    public function getNodeStatistics($node = NULL, $arguments = array())
    {
        $liveNode = $this->getLiveNode($node);
        $newsletter = $this->newsletterRepository->findOneByNode($liveNode->getNodeData());

        switch($arguments['view']) {
            case 'ColumnView':
                return (new ColumnDataResult($newsletter))->getData();
                break;
            case 'TableView':
                switch ($arguments['type']) {
                    case 'device':
                        return (new DeviceDataResult($newsletter))->getData();
                        break;
                    case 'osFamilies':
                        return (new OperatingSystemDataResult($newsletter))->getData();
                        break;
                }
                break;
        }
    }

    /**
     * Get LiveNode for the given node in the live workspace (this is where analytics are collected)
     * @param NodeInterface $node
     * @return NodeInterface
     * @throws StatisticsNotAvailableException If the node was not yet published and no live workspace URI can be resolved
     */
    protected function getLiveNode(NodeInterface $node)
    {
        $contextProperties = $node->getContext()->getProperties();
        $contextProperties['workspaceName'] = 'live';
        $liveContext = $this->contextFactory->create($contextProperties);
        $liveNode = $liveContext->getNodeByIdentifier($node->getIdentifier());
        if ($liveNode === NULL) {
            throw new StatisticsNotAvailableException('Newsletter Statistics are only available on a published node', 1445812693);
        }
        return $liveNode;
    }
}