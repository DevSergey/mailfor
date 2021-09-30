<?php
namespace Tests\Feature;
use App\Credential;
use App\Endpoint;
use App\Receiver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ManageEndpointsTest extends TestCase
{
    use RefreshDatabase;
    public function testUserCanCreateEndpoints()
    {
        $credential = factory(Credential::class)->create();
        $receivers = factory(Receiver::class, 3)->create([
            'user_id' => $credential->user
        ]);
        $this->actingAs($credential->user)
            ->get('/endpoints/create')
            ->assertOk();
        $receiverIds = $receivers->map(fn($receiver) => $receiver->id);
        $endpoint = [
            'name' => 'name',
            'cors_origin' => 'https:
            'subject' => 'The subject of the mail',
            'monthly_limit' => 1000,
            'client_limit' => 2,
            'time_unit' => 'hour',
            'credential_id' => $credential->id,
            'receivers' => $receiverIds
        ];
        $response = $this->actingAs($credential->user)
            ->post('/endpoints', $endpoint);
        $endpoint['user_id'] = $credential->user->id;
        unset($endpoint['receivers']);
        $this->assertDatabaseHas('endpoints', $endpoint);
        foreach ($receiverIds as $receiverId) {
            $this->assertDatabaseHas('endpoint_receiver', [
                'receiver_id' => $receiverId,
                'endpoint_id' => Endpoint::where(['name' => $endpoint['name']])->first()->id
            ]);
        }
        $response->assertRedirect('/endpoints/' . Endpoint::where($endpoint)->first()->id);
        $this->get('/endpoints')
            ->assertSee($endpoint['name'])
            ->assertSee($endpoint['cors_origin']);
    }
    public function testCreateEndpointIsValidated()
    {
        $this->signIn();
        $endpoint = [
            'name' => '',
            'cors_origin' => 'indalid.url',
            'subject' => '',
            'monthly_limit' => -1,
            'time_unit' => 'not a time unit',
            'receivers' => [999]
        ];
        $response = $this->post('/endpoints', $endpoint);
        $response->assertSessionHasErrors([
            'name', 'cors_origin', 'subject', 'monthly_limit', 'client_limit', 'time_unit', 'credential_id', 'receivers'
        ]);
        unset($endpoint['receivers']);
        $this->assertDatabaseMissing('endpoints', $endpoint);
    }
    public function testUsersCannotUseOthersCredentials()
    {
        $this->signIn();
        $credential = factory(Credential::class)->create();
        $endpoint = [
            'name' => 'name',
            'cors_origin' => 'https:
            'subject' => 'The subject of the mail',
            'monthly_limit' => 1000,
            'client_limit' => 2,
            'time_unit' => 'hour',
            'credential_id' => $credential->id
        ];
        $this->post('/endpoints', $endpoint)
            ->assertSessionHasErrors(['credential_id']);
        $this->assertDatabaseMissing('endpoints', $endpoint);
    }
    public function testUsersCannotUseOthersReceivers()
    {
        $this->signIn();
        $receivers = factory(Receiver::class, 3)->create()->map(fn($receiver) => $receiver->id);
        $endpoint = [
            'receivers' => $receivers
        ];
        $this->post('/endpoints', $endpoint)
            ->assertSessionHasErrors('receivers');
    }
}
