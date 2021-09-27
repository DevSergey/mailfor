<?php
namespace Tests\Feature;
use App\Credential;
use App\Endpoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class SeeEndpointsTest extends TestCase
{
    use RefreshDatabase;
    public function testUsersDontSeeOthersCredentialsInForms()
    {
        $foreignCredential = factory(Credential::class)->create();
        $ownCredential = factory(Credential::class)->create();
        $this->actingAs($ownCredential->user)
            ->get('/endpoints/create')
            ->assertOk()
            ->assertDontSee($foreignCredential->name)
            ->assertSee($ownCredential->name);
        $user = $ownCredential->user;
        $ownEndpoint = factory(Endpoint::class)->create([
            'credential_id' => $ownCredential->id,
            'user_id' => $user
        ]);
        $this->actingAs($ownCredential->user)
            ->get('/endpoints/' . $ownEndpoint->id)
            ->assertOk()
            ->assertDontSee($foreignCredential->name)
            ->assertSee($ownCredential->name);
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
}
