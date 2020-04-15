<?php


namespace App\Repositories;


use App\Http\Requests\Api\Profile\CreateProfileRequest;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;
use App\Profile;
use App\Repositories\interfaces\ProfileRepositoryInterface;
use Illuminate\Support\Facades\Date;

class ProfileRepository implements ProfileRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function createByAccountId(int $account_id, CreateProfileRequest $data): void
    {
        $account = \Account::findById($account_id);
        $profile = $account->profile()->create();
        if (!empty($data->first_name)) $profile->first_name = $data->first_name;
        if (!empty($data->name)) $profile->name = $data->name;
        if (!empty($data->birthday)) $profile->birthday = Date::create($data->birthday)->toImmutable();
        if (!empty($data->tel)) $profile->tel = $data->tel;
        if (!empty($data->profile_picture_url))
            $profile->profile_picture_url = $data->profile_picture_url;
        $profile->save();
    }

    /**
     * @inheritDoc
     */
    public function findById(int $id): Profile
    {
        return Profile::find($id);
    }

    /**
     * @inheritDoc
     */
    public function findByName(string $name)
    {
        return Profile::all()->where('name', $name);
    }

    /**
     * @inheritDoc
     */
    public function findByFirstName(string $first_name)
    {
        return Profile::all()->where('first_name', $first_name);
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        return Profile::all();
    }

    /**
     * @inheritDoc
     */
    public function updateById(UpdateProfileRequest $data): void
    {
        $profile = $this->findById($data->profile_id);
        if (!empty($data->first_name)) $profile->first_name = $data->first_name;
        if (!empty($data->name)) $profile->name = $data->name;
        if (!empty($data->birthday)) $profile->birthday = Date::create($data->birthday)->toImmutable();
        if (!empty($data->tel)) $profile->tel = $data->tel;
        if (!empty($data->profile_picture_url))
            $profile->profile_picture_url = $data->profile_picture_url;
        $profile->save();
    }

}