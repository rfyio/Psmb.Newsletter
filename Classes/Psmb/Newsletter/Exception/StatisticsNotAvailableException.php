<?php
namespace Psmb\Newsletter\Exception;

use Neos\Flow\Annotations as Flow;
use Psmb\Newsletter\Exception;

/**
 * Analytics are not available (e.g. node is not yet published)
 */
class StatisticsNotAvailableException extends Exception
{

    /**
     * @var integer
     */
    protected $statusCode = 404;
}
