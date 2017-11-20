<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Crawler.php';

use Mikachou\EasyScraping\Crawler;

describe('Crawler', function() {
    beforeAll(function(){
        $this->crawler = new Crawler();
        $this->crawler->addContent(file_get_contents(realpath(__DIR__ . '/../assets/Foobar.html')), 'text/html');
    });

    describe('::query', function() {
        it('passes if h1 equals "Foobar"', function() {
            expect($this->crawler->query('h1'))->toBe('Foobar');
        });

        it('passes if TOC is correctly retrieved as an array with right values, when passing string with brackets',
            function() {
            expect($this->crawler->query('[div#toc > ul > li > a > span.toctext]'))
                ->toBe(['History and etymology', 'Example use in code', 'Examples in language',
                    'See also', 'References', 'External links']);
        });

        it('passes if link[rel=canonical]@href has the right value', function() {
            expect($this->crawler->query('link[rel=canonical]@href'))->toBe('https://en.wikipedia.org/wiki/Foobar');
        });

        it('passes if footer is correctly retrieved as an array with right values, when passing an array', function() {
            expect($this->crawler->query(['ul#footer-places a']))
                ->toBe(['Privacy policy', 'About Wikipedia', 'Disclaimers', 'Contact Wikipedia',
                    'Developers', 'Cookie statement', 'Mobile view', 'Enable previews']);
        });
    });
});
