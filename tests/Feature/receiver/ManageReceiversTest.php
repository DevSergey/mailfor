<?php
namespace Tests\Feature;
use App\Receiver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ManageReceiversTest extends TestCase
{
    use RefreshDatabase;
    public function testUserCanCreateReceivers()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();
        $this->get('/receivers/create')
            ->assertOk();
        $receiver = [
            'name' => 'name',
            'email' => 'test@mail.com'
        ];
        $response = $this->post('/receivers', $receiver);
        $receiver['user_id'] = $user->id;
        $this->assertDatabaseHas('receivers', $receiver);
        $response->assertRedirect('/receivers/' . Receiver::where($receiver)->first()->id);
        $this->get('/receivers')
            ->assertSee($receiver['name'])
            ->assertSee($receiver['email']);
    }
    public function testCreateReceiverIsValidated()
    {
        $this->signIn();
        $receivers = [
            'name' => '',
            'email' => 'inv$lid.mail'
        ];
        $response = $this->post('/receivers', $receivers);
        $response->assertSessionHasErrors([
            'name', 'email'
        ]);
        $this->assertDatabaseMissing('receivers', $receivers);
    }
    public function testUserCanViewAReceiver()
    {
        $this->withoutExceptionHandling();
        $receiver = factory(Receiver::class)->create();
        $this->actingAs($receiver->user)
            ->get('/receivers/' . $receiver->id)
            ->assertOk()
            ->assertSee($receiver->name)
            ->assertSee($receiver->email);
    }
    public function testGuestsCannotManageReceivers()
    {
        $this->get('/receivers')
            ->assertRedirect('/login');
        $this->get('/receivers/create')
            ->assertRedirect('/login');
        $this->get('/receivers/1')
            ->assertRedirect('/login');
        $this->delete('/receivers/1')
            ->assertRedirect('/login');
        $this->patch('/receivers/1')
            ->assertRedirect('/login');
        $this->post('/receivers')
            ->assertRedirect('/login');
    }
    public function testUsersCannotViewOthersReceivers()
    {
        $this->signIn();
        $ownReceiver = factory(Receiver::class)->create();
        $receiver = factory(Receiver::class)->create();
        $this->get('/receivers/' . $receiver->id)
            ->assertStatus(403)
            ->assertDontSee($receiver->name);
        $this->actingAs($ownReceiver->user)
            ->get('/receivers')
            ->assertSee($ownReceiver->name)
            ->assertDontSee($receiver->name);
    }
    public function testUserCanDeleteHisReceivers()
    {
        $this->withoutExceptionHandling();
        $receiver = factory(Receiver::class)->create();
        $this->actingAs($receiver->user)
            ->delete('/receivers/' . $receiver->id)
            ->assertRedirect('/receivers');
        $this->assertDatabaseMissing('receivers', [
            'id' => $receiver->id
        ]);
    }
    public function testUserCannotDeleteOthersReceivers()
    {
        $this->signIn();
        $receiver = factory(Receiver::class)->create();
        $this
            ->delete('/receivers/' . $receiver->id)
            ->assertStatus(403);
        $this->assertDatabaseHas('receivers', [
            'id' => $receiver->id
        ]);
    }
    public function testUserCanUpdateHisReceivers()
    {
        $this->withoutExceptionHandling();
        $receiver = factory(Receiver::class)->create();
        $changedReceiver = [
            'name' => 'name',
            'email' => 'valid@mail.ch'
        ];
        $path = '/receivers/' . $receiver->id;
        $this->actingAs($receiver->user)
            ->patch($path, $changedReceiver)
            ->assertRedirect($path);
        $this->assertDatabaseHas('receivers', $changedReceiver);
    }
    public function testUpdateReceiversIsValidated()
    {
        $receiver = factory(Receiver::class)->create();
        $changedReceiver = [
            'name' => '',
            'email' => '$nvali.ömeil',
        ];
        $path = '/receivers/' . $receiver->id;
        $this->actingAs($receiver->user)
            ->patch($path, $changedReceiver)
            ->assertSessionHasErrors([
                'name', 'email'
            ]);
        $this->assertDatabaseHas('receivers', [
            'name' => $receiver->name,
            'email' => $receiver->email
        ]);
        $this->assertDatabaseMissing('receivers', $changedReceiver);
    }
    public function testUserCannotUpdateOthersReceivers()
    {
        $this->signIn();
        $receiver = factory(Receiver::class)->create();
        $changedReceiver = [
            'name' => 'changed'
        ];
        $this
            ->patch('/receivers/' . $receiver->id, $changedReceiver)
            ->assertStatus(403);
        $this->assertDatabaseHas('receivers', $receiver->toArray());
        $this->assertDatabaseMissing('receivers', $changedReceiver);
    }
}
