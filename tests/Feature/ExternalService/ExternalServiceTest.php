<?php

namespace tests\Feature\ExternalService;

use Google\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class ExternalServiceTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->user = $this->authUser();
    }

    public function test_user_connects_to_google_and_get_the_url(): void
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setScopes')->once();
            $mock->shouldReceive('setAccessType')->once();
            $mock->shouldReceive('createAuthUrl')->andReturn('http://localhost');
        });

        $res = $this->getJson(route('services.google.connect'))->assertOk()->json();

        $this->assertNotNull($res['url']);
    }

    public function test_callback_will_give_token_and_stores_the_token(): void
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            // The below is commented because it is added in service provider
            // $mock->shouldReceive('setClientId')->once();
            // $mock->shouldReceive('setClientSecret')->once();
            // $mock->shouldReceive('setRedirectUri')->once();
            $mock->shouldReceive('fetchAccessTokenWithAuthCode')
                ->andReturn(['access_token' => 'fake-token']);
        });

        $this->postJson(route('services.google.callback'), ['code' => 'dummyCode'])
            ->assertCreated()->json();

        $this->assertDatabaseHas('external_services', [
            'user_id' => $this->user->id, 
            'name' => 'Google', 
            // 'token' => json_encode($res['token'])
        ]);
    }

    public function test_data_of_a_week_can_be_stored_on_google_drive(): void
    {
        $this->createTask(['created_at' => now()->subDays(2)]);
        $this->createTask(['created_at' => now()->subDays(3)]);
        $this->createTask(['created_at' => now()->subDays(4)]);
        $this->createTask(['created_at' => now()->subDays(5)]);

        $this->createTask(['created_at' => now()->subDays(10)]);

        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setAccessToken');
            $mock->shouldReceive('getLogger->info'); // for info check in vendor files
            $mock->shouldReceive('shouldDefer');
            $mock->shouldReceive('execute');
        });
        $service = $this->createExternalService();

        $this->postJson(route('services.google.upload', $service->id))
            ->assertCreated()->json();
    }
}
