<?php
namespace Tests\Feature;
use App\Endpoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class DeleteEndpointTest extends TestCase
{
    use RefreshDatabase;
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
}
