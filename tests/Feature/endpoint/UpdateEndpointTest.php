<?php
namespace Tests\Feature;
use App\Credential;
use App\Endpoint;
use App\Receiver;
use Facades\Tests\Setup\EndpointFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class UpdateEndpointTest extends TestCase
{
    use RefreshDatabase;
    public function testUserCanUpdateHisEndpoints()
    {
        $user = $this->signIn();
        $endpoint = EndpointFactory::withUser($user)->withReceivers(2)->create();
        $changedReceivers = factory(Receiver::class, 2)->create([
            'user_id' => $user
        ])->map(fn($receiver) => $receiver->id);
        $changedEndpoint = [
            'name' => 'name',
            'cors_origin' => 'https:
            'subject' => 'The subject of the mail',
            'monthly_limit' => 1000,
            'client_limit' => 2,
            'time_unit' => 'hour',
            'credential_id' => factory(Credential::class)->create([
                'user_id' => $user
            ])->id,
            'receivers' => $changedReceivers
        ];
        $path = '/endpoints/' . $endpoint->id;
        $this->patch($path, $changedEndpoint)
            ->assertRedirect($path);
        unset($changedEndpoint['receivers']);
        $this->assertDatabaseHas('endpoints', $changedEndpoint);
        foreach ($changedReceivers as $receiverId) {
            $this->assertDatabaseHas('endpoint_receiver', [
                'receiver_id' => $receiverId,
                'endpoint_id' => Endpoint::where(['name' => $changedEndpoint['name']])->first()->id
            ]);
        }
    }
    public function testUpdateEndpointsIsValidated()
    {
        $endpoint = factory(Endpoint::class)->create();
        $changedEndpoint = [
            'name' => '',
            'cors_origin' => 'indalid.url',
            'subject' => '',
            'monthly_limit' => -1,
            'time_unit' => 'not a time unit'
        ];
        $path = '/endpoints/' . $endpoint->id;
        $this->actingAs($endpoint->user)
            ->patch($path, $changedEndpoint)
            ->assertSessionHasErrors([
                'name', 'cors_origin', 'subject', 'monthly_limit', 'client_limit', 'time_unit', 'credential_id'
            ]);
        $this->assertDatabaseHas('endpoints', [
            'name' => $endpoint->name,
            'cors_origin' => $endpoint->cors_origin
        ]);
        $this->assertDatabaseMissing('endpoints', $changedEndpoint);
    }
    public function testUserCannotUpdateOthersEndpoints()
    {
        $this->signIn();
        $endpoint = factory(Endpoint::class)->create();
        $changedEndpoint = [
            'name' => 'changed'
        ];
        $this
            ->patch('/endpoints/' . $endpoint->id, $changedEndpoint)
            ->assertStatus(403);
        $this->assertDatabaseHas('endpoints', $endpoint->toArray());
        $this->assertDatabaseMissing('endpoints', $changedEndpoint);
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
