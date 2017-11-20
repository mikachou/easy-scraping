<?php

namespace Mikachou\EasyScraping;

use Mikachou\EasyScraping\Crawler;

/**
 * A Client instance
 *
 * @author Michaël Schuh <michael.schuh.34@gmail.com>
 */
class Client extends \Goutte\Client
{
    /**
     * {@inheritDoc}
     */
    protected function createCrawlerFromContent($uri, $content, $type)
    {
        $crawler = new Crawler(null, $uri);
        $crawler->addContent($content, $type);

        return $crawler;
    }
}
