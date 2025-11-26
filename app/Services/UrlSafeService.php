<?php

namespace App\Services;

class UrlSafeService
{
    /**
     * @function encode the (not URL safe) path
     * @param string $path
     * @return string
     */
    public function encode (string $path): string
    {
        return base64_encode($path);
    }

    /**
     * @function decode the (not URL safe) path
     * @param string $path
     * @return string
     */
    public function decode (string $path): string
    {
        return base64_decode($path);
    }
}
