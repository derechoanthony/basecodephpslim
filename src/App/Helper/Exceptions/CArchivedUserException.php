<?php

namespace App\Helper\Exceptions;

class CArchivedUserException {
   
   public function __invoke($request, $response) {
        return $response
            ->withStatus(401)
            ->withHeader('Content-Type', 'text/html')
            ->write('User does not exist');
   }
}