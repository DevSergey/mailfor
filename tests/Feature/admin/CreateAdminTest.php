<?php
namespace Tests\Feature;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
class CreateAdminTest extends TestCase
{
    use RefreshDatabase;
    public function testUserCanBeAdmin()
    {
        $user = $this->signIn(factory(User::class)->create([
            'admin' => true
        ]));
        $this->assertTrue($user->admin);
    }
    public function testNewUsersAreNotAdmins()
    {
        $this->withoutExceptionHandling();
        $user = [
            'name' => 'username',
            'email' => 'mail@mail.ch',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        $this->post('/register', $user)
            ->assertRedirect();
        $query = [
            'email' => $user['email']
        ];
        $this->assertDatabaseHas('users', $query);
        $this->assertTrue(User::where($query)->first()->admin === "0");
    }
    public function testAdminCanBeCreatedWithConsole()
    {
        $name = 'admin';
        $email = 'admin@test.example';
        $password = 'password';
        $user = [
            'name' => $name,
            'email' => $email,
        ];
        $this->artisan('admin:create')
            ->expectsQuestion('What is your username?', $name)
            ->expectsQuestion('What is your email address?', $email)
            ->expectsQuestion('What is your password?', $password)
            ->expectsQuestion('Repeat your password', $password)
            ->expectsOutput('Successfully created admin account')
            ->assertExitCode(0);
        $this->assertDatabaseHas('users', $user);
        $insertedUser = User::where($user)->first();
        $this->assertTrue(Hash::check($password, $insertedUser->password));
    }
    public function testCreateAdminInputIsValidated()
    {
        $name = '';
        $email = '';
        $password = 'password';
        $password_confirmation = 'bla';
        $this->artisan('admin:create')
            ->expectsQuestion('What is your username?', $name)
            ->expectsQuestion('What is your email address?', $email)
            ->expectsQuestion('What is your password?', $password)
            ->expectsQuestion('Repeat your password', $password_confirmation)
            ->expectsOutput('Invalid input parameters: name, email, password')
            ->assertExitCode(1);
    }
}
