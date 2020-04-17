<?php


namespace App\Repositories\interfaces;


use App\AccountType;

interface AccountTypeRepositoryInterface
{
    /**
     * @param string $title
     * @param string $description
     */
    public function create(string $title, string $description): void;

    /**
     * @param int $id
     * @param array $data
     * @return void
     */
    public function updateById(int $id, array $data): void;

    /**
     * @param string $title
     * @return AccountType
     */
    public function findByTitle(string $title): AccountType;

    /**
     * @param int $id
     * @return AccountType
     */
    public function findById(int $id): AccountType;

    /**
     * @return mixed
     */
    public function all();
}