<?php


namespace App\Repositories\interfaces;


interface AccountRepositoryInterface
{
    /**
     * @param Int $invite_id
     * @return mixed
     */
    public function createByInviteId(Int $invite_id);

    /**
     * @param Int $account_id
     * @return mixed
     */
    public function findById(Int $account_id);

    /**
     * @return mixed
     */
    public function all();
}