<?php


namespace App\Repositories;


use App\Exceptions\ArgumentNotSetException;
use App\Repositories\interfaces\TokenRepositoryInterface;
use App\Token;
use Illuminate\Support\Str;

class TokenRepository implements TokenRepositoryInterface
{
    private Token $token;

    /**
     * @inheritDoc
     * @throws ArgumentNotSetException
     */
    public function create(string $uuid, string $action): void
    {
        if (!$this->_chkValidArguments($uuid, $action))
            throw new ArgumentNotSetException('Please, enter all valid arguments');
        $this->_create($uuid, $action);
    }

    /**
     * @inheritDoc
     */
    public function use(string $token, string $action): bool
    {
        // TODO: Implement use() method.
    }

    private function _generateToken(): string
    {
        return Str::random(32);
    }

    /**
     * @param string $uuid
     * @return void
     */
    private function _setUuid(string $uuid): void
    {
        $this->token->uuid = $uuid;
        $this->token->save();
    }

    /**
     * @param string $action
     * @return void
     */
    private function _setAction(string $action): void
    {
        $this->token->action = $action;
        $this->token->save();
    }

    /**
     * @return void
     */
    private function _setToken(): void
    {
        $this->token->token = $this->_generateToken();
        $this->token->save();
    }

    /**
     * @param string $uuid
     * @param string $action
     * @throws ArgumentNotSetException
     */
    private function _chkValidArguments(string $uuid, string $action): bool
    {
        return (empty($uuid) || empty($action));
    }

    /**
     * @param string $uuid
     * @param string $action
     */
    private function _create(string $uuid, string $action): void
    {
        $this->token = Token::create();
        $this->_setUuid($uuid);
        $this->_setAction($action);
        $this->_setToken();
    }
}