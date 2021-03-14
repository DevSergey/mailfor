<?php
namespace Tests\Feature;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ManageUsersTest extends TestCase
{
    use RefreshDatabase;
    public function testAdminsCanViewAllUsers()
    {
        $this->signInAdmin();
        $users = factory(User::class, 3)->create();
        $this->get('/users')
            ->assertOk()
            ->assertSee($users->first()->name)
            ->assertSee($users[1]->email)
            ->assertSee($users->last()->admin);
    }
    public function testNonAdminsCannotSeeAnyUsers()
    {
        $guest = $this->signIn();
        $this->get('/users')
            ->assertStatus(403)
            ->assertDontSee($guest->email);
    }
    public function testGuestsCannotManageUsers()
    {
        $this->get('/users')
            ->assertRedirect('/login');
        $this->delete('/users/1')
            ->assertRedirect('/login');
    }
    public function testAdminsCanDeleteUsers()
    {
        $this->signInAdmin();
        $user = factory(User::class)->create();
        $this->delete('/users/' . $user->id)
            ->assertRedirect('/users');
        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }
    public function testNonAdminsCannotDeleteUsers()
    {
        $this->signIn();
        $user = factory(User::class)->create();
        $this->delete('/users/' . $user->id)
            ->assertStatus(403);
        $this->assertDatabaseHas('users', [
            'id' => $user->id
        ]);
    }
}
