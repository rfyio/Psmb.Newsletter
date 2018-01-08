<?php
namespace Psmb\Newsletter\Service\DataSource;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Neos\Service\DataSource\AbstractDataSource;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * Class AnalyticsDataSource
 * @package Psmb\Newsletter\Service\DataSource
 */
class AnalyticsDataSource extends AbstractDataSource
{
    /**
     * @Flow\Inject
     * @var \Psmb\Newsletter\Service\Reporting
     */
    protected $reportingService;

    /**
     * @var string
     */
    static protected $identifier = 'psmb-newsletter-analytics';

    /**
     * Get data
     *
     * {@inheritdoc}
     */
    public function getData(NodeInterface $node = NULL, array $arguments)
    {
        $statsData = $this->reportingService->getNodeStatistics($node, $arguments);
        $data = array(
            'data' => $statsData
        );

        return $data;
    }

}
