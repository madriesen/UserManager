<?php


namespace App\Repositories;


use App\AccountType;
use App\Repositories\interfaces\AccountTypeRepositoryInterface;

class AccountTypeRepository implements AccountTypeRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function create(string $title, string $description): void
    {
        $account_type_id = AccountType::create()->id;
        $this->_updateTitleById($account_type_id, $title);
        $this->_updateDescriptionById($account_type_id, $description);
    }

    /**
     * @inheritDoc
     */
    public function updateById(int $id, array $data): void
    {
        if (!empty($data['title'])) $this->_updateTitleById($id, $data['title']);
        if (!empty($data['description'])) $this->_updateDescriptionById($id, $data['description']);
    }

    /**
     * @inheritDoc
     */
    public function findByTitle(string $title): AccountType
    {
        return AccountType::all()->firstWhere('title', $title);
    }

    /**
     * @inheritDoc
     */
    public function findById(int $id): AccountType
    {
        return AccountType::find($id);
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        return AccountType::all();
    }


    /**
     * @param int $id
     * @param string $title
     */
    private function _updateTitleById(int $id, string $title): void
    {
        $account_type = $this->findById($id);
        $account_type->title = $title;
        $account_type->save();
    }

    /**
     * @param int $id
     * @param string $description
     */
    private function _updateDescriptionById(int $id, string $description): void
    {
        $account_type = $this->findById($id);
        $account_type->description = $description;
        $account_type->save();
    }
}