<?php

namespace Psmb\Newsletter\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\NodeData;

/**
 * Thumbnail configuration value object
 */
class ClicksOnArticleNewsletter
{
    /**
     * @var Newsletter
     */
    protected $newsletter;

    /**
     * ClicksOnArticleNewsletter constructor.
     * @param Newsletter $newsletter
     */
    public function __construct(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * @return array
     * @throws \Neos\ContentRepository\Exception\NodeException
     */
    public function getData()
    {
        $data = [];
        $total = 0;
        /** @var Link $link */
        foreach ($this->newsletter->getLinks() as $link) {
            if ($link->getNode() instanceof NodeData && $link->getNode()->hasProperty('title')) {
                $data[$link->getNode()->getProperty('title')] = $link->getViewsCount();
                $total = +$link->getViewsCount();
            }
        }

        $i = 0;
        foreach ($data as $key => $value) {
            $data[$i] = [
                'name' => $key,
                'y' => $value,
                'percent' => $total == 0 ? 0 : round(($value * 100 / $total)),
            ];
            $i++;
            unset($data[$key]);
        }
        return $data;
    }
}
