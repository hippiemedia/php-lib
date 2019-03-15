<?php declare(strict_types=1);

// php -S 0:8080 -t example
// xdg-open http://0:8080/whatever.php

use Hippiemedia\Infra\Negotiate\WilldurandNegotiator;
use Hippiemedia\Format;
use Hippiemedia\Resource;

require(__DIR__.'/../vendor/autoload.php');

$contentType = array_change_key_case(getallheaders())['content-type'] ?? 'text/html';

$negotiate = new WilldurandNegotiator(new \Negotiation\Negotiator, 'text/html', new Format\Html, new Format\Siren, new Format\Hal);
$format = $negotiate($contentType);

header('Content-Type: '.$format->accepts());

echo implode("\n", iterator_to_array($format(Resource::whatever()), false));
