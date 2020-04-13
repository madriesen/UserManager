<?php


namespace App\Repositories\interfaces;


use App\Http\Requests\Api\Profile\CreateProfileRequest;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;
use App\Profile;
use Illuminate\Database\Eloquent\Collection;

interface ProfileRepositoryInterface
{
    /**
     * @param Int $account_id
     * @param CreateProfileRequest $data
     */
    public function createByAccountId(Int $account_id, CreateProfileRequest $data): void;

    /**
     * @param Int $id
     * @return Profile
     */
    public function findById(Int $id): Profile;

    /**
     * @param string $name
     * @return mixed
     */
    public function findByName(string $name);

    /**
     * @param string $first_name
     * @return Profile[]|Collection
     */
    public function findByFirstName(string $first_name);
    
    /**
     * @return mixed
     */
    public function all();

    /**
     * @param UpdateProfileRequest $data
     */
    public function updateById(UpdateProfileRequest $data): void;
}