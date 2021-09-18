<?php
namespace Tests\Feature;
use App\Endpoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ManageEndpointsTest extends TestCase
{
    use RefreshDatabase;
    public function testUserCanCreateEndpoints()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();
        $this->get('/endpoints/create')
            ->assertOk();
        $endpoint = [
            'name' => 'name',
            'cors_origin' => 'https:
            'subject' => 'The subject of the mail',
            'monthly_limit' => 1000,
            'client_limit' => 2,
            'time_unit' => 'hour'
        ];
        $response = $this->post('/endpoints', $endpoint);
        $endpoint['user_id'] = $user->id;
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
            'client_limit' => -1,
            'time_unit' => 'not a time unit'
        ];
        $response = $this->post('/endpoints', $endpoint);
        $response->assertSessionHasErrors([
            'name', 'cors_origin'
        ]);
        $this->assertDatabaseMissing('endpoints', $endpoint);
    }
    public function testUserCanViewAEndpoint()
    {
        $this->withoutExceptionHandling();
        $endpoint = factory(Endpoint::class)->create();
        $this->actingAs($endpoint->user)
            ->get('/endpoints/' . $endpoint->id)
            ->assertOk()
            ->assertSee($endpoint->name)
            ->assertSee($endpoint->cors_origin);
    }
    public function testGuestsCannotManageEndpoints()
    {
        $this->get('/endpoints')
            ->assertRedirect('/login');
        $this->get('/endpoints/create')
            ->assertRedirect('/login');
        $this->get('/endpoints/1')
            ->assertRedirect('/login');
        $this->delete('/endpoints/1')
            ->assertRedirect('/login');
        $this->patch('/endpoints/1')
            ->assertRedirect('/login');
        $this->post('/endpoints')
            ->assertRedirect('/login');
    }
    public function testUsersCannotViewOthersEndpoints()
    {
        $this->signIn();
        $ownEndpoint = factory(Endpoint::class)->create();
        $endpoint = factory(Endpoint::class)->create();
        $this->get('/endpoints/' . $endpoint->id)
            ->assertStatus(403)
            ->assertDontSee($endpoint->name);
        $this->actingAs($ownEndpoint->user)
            ->get('/endpoints')
            ->assertSee($ownEndpoint->name)
            ->assertDontSee($endpoint->name);
    }
    public function testUserCanDeleteHisEndpoints()
    {
        $this->withoutExceptionHandling();
        $endpoint = factory(Endpoint::class)->create();
        $this->actingAs($endpoint->user)
            ->delete('/endpoints/' . $endpoint->id)
            ->assertRedirect('/endpoints');
        $this->assertDatabaseMissing('endpoints', [
            'id' => $endpoint->id
        ]);
    }
    public function testUserCannotDeleteOthersEndpoints()
    {
        $this->signIn();
        $endpoint = factory(Endpoint::class)->create();
        $this
            ->delete('/endpoints/' . $endpoint->id)
            ->assertStatus(403);
        $this->assertDatabaseHas('endpoints', [
            'id' => $endpoint->id
        ]);
    }
    public function testUserCanUpdateHisEndpoints()
    {
        $endpoint = factory(Endpoint::class)->create();
        $changedEndpoint = [
            'name' => 'name',
            'cors_origin' => 'https:
            'subject' => 'The subject of the mail',
            'monthly_limit' => 1000,
            'client_limit' => 2,
            'time_unit' => 'hour'
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
            'client_limit' => -1,
            'time_unit' => 'not a time unit'
        ];
        $path = '/endpoints/' . $endpoint->id;
        $this->actingAs($endpoint->user)
            ->patch($path, $changedEndpoint)
            ->assertSessionHasErrors([
                'name', 'cors_origin'
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
