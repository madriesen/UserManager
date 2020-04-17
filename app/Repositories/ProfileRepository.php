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
        $this->_setProfileData($data, $profile);
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
        $this->_setProfileData($data, $profile);
    }

    /**
     * @param string $first_name
     * @param Profile $profile
     * @return void
     */
    private function _setFirstName(string $first_name, Profile $profile): void
    {
        $profile->first_name = $first_name;
        $profile->save();

    }

    /**
     * @param string $name
     * @param $profile
     * @return void
     */
    private function _setName(string $name, $profile): void
    {
        $profile->name = $name;
        $profile->save();

    }

    /**
     * @param Date $birthday
     * @param Profile $profile
     * @return void
     */
    private function _setBirthday(string $birthday, Profile $profile): void
    {
        $profile->birthday = Date::create($birthday)->toImmutable();
        $profile->save();
    }

    /**
     * @param string $tel
     * @param $profile
     * @return void
     */
    private function _setTel(string $tel, $profile): void
    {
        $profile->tel = $tel;
        $profile->save();

    }

    /**
     * @param string $profile_picture_url
     * @param Profile $profile
     * @return void
     */
    private function _setProfilePictureURL(string $profile_picture_url, Profile $profile): void
    {
        $profile->profile_picture_url = $profile_picture_url;
        $profile->save();
    }

    /**
     * @param $data
     * @param Profile $profile
     */
    private function _setProfileData($data, Profile $profile): void
    {
        if (!empty($data->first_name)) $this->_setFirstName($data->first_name, $profile);
        if (!empty($data->name)) $this->_setName($data->name, $profile);
        if (!empty($data->birthday)) $this->_setBirthday($data->birthday, $profile);
        if (!empty($data->tel)) $this->_setTel($data->tel, $profile);
        if (!empty($data->profile_picture_url))
            $this->_setProfilePictureURL($data->profile_picture_url, $profile);
    }

}