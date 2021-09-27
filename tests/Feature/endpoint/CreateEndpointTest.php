<?php
namespace Tests\Feature;
use App\Credential;
use App\Endpoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ManageEndpointsTest extends TestCase
{
    use RefreshDatabase;
    public function testUserCanCreateEndpoints()
    {
        $this->withoutExceptionHandling();
        $credential = factory(Credential::class)->create();
        $this->actingAs($credential->user)
            ->get('/endpoints/create')
            ->assertOk();
        $endpoint = [
            'name' => 'name',
            'cors_origin' => 'https:
            'subject' => 'The subject of the mail',
            'monthly_limit' => 1000,
            'client_limit' => 2,
            'time_unit' => 'hour',
            'credential_id' => $credential->id
        ];
        $response = $this->actingAs($credential->user)
            ->post('/endpoints', $endpoint);
        $endpoint['user_id'] = $credential->user->id;
        $this->assertDatabaseHas('endpoints', $endpoint);
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
            'time_unit' => 'not a time unit'
        ];
        $response = $this->post('/endpoints', $endpoint);
        $response->assertSessionHasErrors([
            'name', 'cors_origin', 'subject', 'monthly_limit', 'client_limit', 'time_unit', 'credential_id'
        ]);
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
}
