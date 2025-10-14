<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ValidateUrlFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $uriString = $_SERVER['REQUEST_URI'];
        // $uri = service('uri'); 
        // $rawUri = $uri->getPath();

                // Allow this exact URL
        // if ($rawUri === '/ITE311-RIVERA/../../login') {
        //     return null; // continue normally
        // }

        //  Block URLs with multiple slashes (like ////login)
        // if (preg_match('#/{2,}#', $rawUri) || strpos($rawUri, '..') !== false) {
        if (preg_match('#/{2,}#', $uriString)) {
            return service('response')
                ->setStatusCode(404)
                ->setBody(view('app/public/error_404.html'));
        }

        return null; //  Continue normally
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after response
    }
}
