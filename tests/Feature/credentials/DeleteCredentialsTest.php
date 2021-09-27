<?php
namespace Tests\Feature;
use App\Credential;
use App\Endpoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class DeleteCredentialsTest extends TestCase
{
    use RefreshDatabase;
    public function testUserCanDeleteHisCredentials()
    {
        $credential = factory(Credential::class)->create();
        $this->actingAs($credential->user)
            ->delete('/credentials/' . $credential->id)
            ->assertRedirect('/credentials');
        $this->assertDatabaseMissing('credentials', [
            'id' => $credential->id
        ]);
    }
    public function testUserCannotDeleteOthersCredentials()
    {
        $this->signIn();
        $credential = factory(Credential::class)->create();
        $this
            ->delete('/credentials/' . $credential->id)
            ->assertStatus(403);
        $this->assertDatabaseHas('credentials', [
            'id' => $credential->id
        ]);
    }
    public function testCanDeleteUsedCredential()
    {
        $this->withoutExceptionHandling();
        $endpoint = factory(Endpoint::class)->create();
        $this->actingAs($endpoint->user)
            ->delete('/credentials/' . $endpoint->credential->id)
            ->assertRedirect('/credentials');
        $this->assertDatabaseMissing('credentials', [
            'id' => $endpoint->credential->id
        ]);
        $this->assertDatabaseMissing('endpoints', [
            'id' => $endpoint->id
        ]);
    }
}
