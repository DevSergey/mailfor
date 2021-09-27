<?php
namespace Tests\Feature;
use App\Credential;
use App\Endpoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class UpdateEndpointTest extends TestCase
{
    use RefreshDatabase;
    public function testUserCanUpdateHisEndpoints()
    {
        $user = $this->signIn();
        $endpoint = factory(Endpoint::class)->create([
            'user_id' => $user->id
        ]);
        $credential = factory(Credential::class)->create([
            'user_id' => $user->id
        ]);
        $changedEndpoint = [
            'name' => 'name',
            'cors_origin' => 'https:
            'subject' => 'The subject of the mail',
            'monthly_limit' => 1000,
            'client_limit' => 2,
            'time_unit' => 'hour',
            'credential_id' => $credential->id
        ];
        $path = '/endpoints/' . $endpoint->id;
        $this->actingAs($endpoint->user)
            ->patch($path, $changedEndpoint)
            ->assertRedirect($path);
        $this->assertDatabaseHas('endpoints', $changedEndpoint);
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
}
