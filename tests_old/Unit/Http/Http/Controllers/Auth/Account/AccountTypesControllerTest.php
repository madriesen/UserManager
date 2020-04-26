<?php

namespace Tests\Unit\Http\Controllers\Auth\Account;

use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class AccountTypesControllerTest extends TestCase
{
    private string $token;

    public function setUp(): void
    {
        parent::setUp();
        Date::setTestNow(Date::create(2020, 4, 7, 10, 43)->toImmutable());
        $this->seed(\DatabaseSeeder::class);
        $response = $this->postJson(route('login'), ['email_address' => 'admin@test.be', 'password' => 'test1234']);
        $this->token = $response['data']['token'];

        $this->withHeaders($this->_headers());
    }

    /** @test */
    public function an_account_type_creation_without_arguments_fails()
    {
        $response = $this->postJson(route('accountType'));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['title' => 'Please, enter a valid title']]]);
    }

    /** @test */
    public function an_account_type_creation_without_a_title_fails()
    {
        $this->assertDatabaseMissing('account_types', ['title' => 'null', 'description' => $this->_validData()['description']]);
        $response = $this->postJson(route('accountType'), array_merge($this->_validData(), ['title' => null]));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['title' => 'Please, enter a valid title']]]);
        $this->assertDatabaseMissing('account_types', ['title' => 'null', 'description' => $this->_validData()['description']]);

    }

    /** @test */
    public function an_account_type_creation_without_a_description_fails()
    {
        $this->assertDatabaseMissing('account_types', ['description' => 'null', 'title' => $this->_validData()['title']]);
        $response = $this->postJson(route('accountType'), array_merge($this->_validData(), ['description' => null]));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['description' => 'Please, enter a valid description']]]);
        $this->assertDatabaseMissing('account_types', ['description' => 'null', 'title' => $this->_validData()['title']]);
    }

    /** @test */
    public function an_account_type_creation_with_a_name_and_a_description_passes()
    {
        $this->assertDatabaseMissing('account_types', $this->_validData());
        $response = $this->postJson(route('accountType'), $this->_validData());
        $response->assertJsonStructure(['success', 'data']);
        $response->assertJson(['success' => true, 'data' => []]);
        $this->assertDatabaseHas('account_types', $this->_validData());

    }

    /** @test */
    public function an_account_type_update_without_arguments_fails()
    {
        $response = $this->postJson(route('updateAccountType'));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['account_type_id' => 'Please, enter a valid account type']]]);
    }

    /** @test */
    public function an_account_type_update_with_a_non_existing_account_type_id_fails()
    {
        $non_existing_id = -1;
        $this->assertDatabaseMissing('account_types', ['id' => $non_existing_id]);
        $response = $this->postJson(route('updateAccountType'), array_merge($this->_validUpdateData(), ['account_type_id' => $non_existing_id]));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['account_type_id' => 'Please, enter an existing account type']]]);
        $this->assertDatabaseMissing('account_types', ['id' => $non_existing_id]);
    }

    /** @test */
    public function an_account_type_update_with_an_existing_account_type_id_passes()
    {
        $data = $this->_validUpdateData();
        $this->assertDatabaseMissing('account_types', ['id' => $data['account_type_id'], 'title' => $data['title'], 'description' => $data['description']]);
        $response = $this->postJson(route('updateAccountType'), $data);
        $response->assertJsonStructure(['success', 'data']);
        $response->assertJson(['success' => true, 'data' => []]);
        $this->assertDatabaseHas('account_types', ['id' => $data['account_type_id'], 'title' => $data['title'], 'description' => $data['description']]);

    }

    /**
     * @return string[]
     */
    private function _headers(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ];
    }

    /**
     * @return string[]
     */
    private function _validData(): array
    {
        return ['title' => 'Test_type', 'description' => 'This type is made for testing purposes'];
    }

    /**
     * @return string[]
     */
    private function _validUpdateData(): array
    {
        $this->postJson(route('accountType'), $this->_validData());
        $account_type_id = \AccountType::findByTitle($this->_validData()['title'])->id;

        return ['account_type_id' => $account_type_id, 'title' => 'Test_type_update', 'description' => 'This type is made for update testing purposes'];
    }
}
