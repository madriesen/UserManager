<?php


namespace App\Repositories\interfaces;


interface TokenRepositoryInterface
{
    /**
     * @param string $uuid
     * @param string $action
     */
    public function create(string $uuid, string $action): void;

    /**
     * @param string $token
     * @param string $action
     * @return bool
     */
    public function use(string $token, string $action): bool;
}