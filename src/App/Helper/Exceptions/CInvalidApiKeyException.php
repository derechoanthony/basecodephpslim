<?php

namespace App\Helper\Exceptions;

class CInvalidApiKeyException {
   
   public function __invoke($request, $response) {
        return $response
            ->withStatus(401)
            ->withHeader('Content-Type', 'text/html')
            ->write('Invalid API Key');
   }
}