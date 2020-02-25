<?php
namespace Psmb\Newsletter\Service\DataSource;

use Neos\Flow\Annotations as Flow;
use Neos\Neos\Service\DataSource\AbstractDataSource;
use Neos\ContentRepository\Domain\Model\NodeInterface;

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
