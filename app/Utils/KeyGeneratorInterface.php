<?php namespace App\Utils;

interface KeyGeneratorInterface {

    /**
     * Create a new key.
     *
     * @param string $nbBytes The number of bytes
     * @return string
     */
    public function create($nbBytes);
}