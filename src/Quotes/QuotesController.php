<?php

declare(strict_types=1);

namespace Refactoring\Quotes;

use Refactoring\Utils\Responsor;

class QuotesController
{
    public $transformerNS = '';

    const ERROR_CODE_NO_ERROR= 200;

    public function __construct()
    {
        $this->transformerNS = 'Refactoring\\Quotes\\Transformer\\';
    }

    public function get() {
        if(strpos(request()->getUri(), 'random') !== false) {
            $opts = array(
                'http'=>array(
                    'method'=>"GET",
                    'header'=>"Accept-language: en\r\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
                    'ignore_errors' => true,
                )
            );
            $content = file_get_contents('https://www.tronalddump.io/random/quote', false,stream_context_create($opts));
            $decoded = json_decode($content, true);

            $transformerClass = $this->transformerNS . 'RandomTransformer';

            $this->lastQuote = $decoded['value'];

            $trans = new $transformerClass;
            $transformedStuff = $trans->transform($decoded);

            return Responsor::createResponse($transformedStuff, self::ERROR_CODE_NO_ERROR);
        } else {
            if(\request()->query('amount') > 0) {
                $opts = array(
                    'http'=>array(
                        'method'=>"GET",
                        'header'=>"Accept-language: en\r\nAccept: application/json",
                        'ignore_errors' => true,
                    )
                );

                $results = [];
                for ($i = 0; $i < 5; $i++) {
                    $content = file_get_contents('https://www.tronalddump.io/search/quote?query=' . request()->query('search'), false,stream_context_create($opts));
                    $decoded = json_decode($content, true)['_embedded']['quotes'][0];
                    $transformerClass = $this->transformerNS . 'QuoteTransformer';

                    $this->lastQuote = $decoded['value'];

                    $trans = new $transformerClass;
                    $transformedStuff = $trans->transform($decoded);

                    $results[] = $transformedStuff;
                }


            } else {
                $opts = array(
                    'http'=>array(
                        'method'=>"GET",
                        'header'=>"Accept-language: en\r\nAccept: application/json",
                        'ignore_errors' => true,
                    )
                );

                $results = [];
                $content = file_get_contents('https://www.tronalddump.io/search/quote?query=' .  request()->query('search'), false,stream_context_create($opts));
                $decoded = json_decode($content, true)['_embedded']['quotes'][0];
                $transformerClass = $this->transformerNS . 'QuoteTransformer';

                $this->lastQuote = $decoded['value'];

                $trans = new $transformerClass;
                $transformedStuff = $trans->transform($decoded);

                $results[] = $transformedStuff;
                return $results[0];
            }

            return $results;
        }


         return [$this->lastQuote];
    }

    public function __() {
        dd($this->doQuote());
    }

    private function doQuote()
    {
        return $this->lastQuote;
    }

}