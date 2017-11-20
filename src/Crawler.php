<?php

namespace Mikachou\EasyScraping;

use Symfony\Component\DomCrawler\Crawler as BaseCrawler;

/**
 * A crawler instance
 *
 * @author Michaël Schuh <michael.schuh.34@gmail.com>
 */
class Crawler extends BaseCrawler
{
    /**
     * Query a given selector on DOM
     * 
     * @param type $selector
     */
    public function query($selector)
    {
        return $this->interpretCssAssert($selector);
    }

    /**
     * Interpret an assert from a rule
     *
     * @param  mixed    $assert
     *
     * @return string|array|null
     */
    protected function interpretCssAssert($assert)
    {
        $multiple = true;

        // selectors between brackets are "multiple"
        if (is_array($assert)) {
            $selector = $assert;
        } elseif (preg_match('/^\s*\[(.+)\]\s*$/', $assert, $matches)) {
            $selector = $matches[1];
        } else {
            $multiple = strpos($assert, ',') !== false;
            $selector = $assert;
        }

        // explode the string using "comma" as delimiter
        $parts = is_array($selector) ? $selector : explode(',', $selector);

        $results = [];

        foreach ($parts as $part) {
            $attr = null;
            if ($hasAttr = preg_match('/^(.+)@(.+)$/', $part, $matches)) {
                $part = $matches[1];
                $attr = $matches[2];
            }

            if ($multiple) {
                $result = [];

                $this->filter($part)->each(function($node) use (&$result, $hasAttr, $attr){
                    $result[] = $hasAttr ? $node->attr($attr) : $node->text();
                });

                $results = array_merge($results, $result);
            } else {
                $node = $this->filter($part)->first();

                if (count($node) > 0) {
                    $results[] = $hasAttr ? $node->attr($attr) : $node->text();
                }
            }
        }

        if ($multiple) {
            return $results;
        }

        switch (count($results)) {
            case 0:
                return null;
            case 1:
                return $results[0];
            default:
                return $results;
        }
    }
}
