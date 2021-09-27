<?php
namespace Tests\Feature;
use App\Credential;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ManageSMTPCredentialsTest extends TestCase
{
    use RefreshDatabase;
    public function testUserCanCreateCredentials()
    {
        $user = $this->signIn();
        $this->get('/credentials/create')
            ->assertOk();
        $credential = [
            'name' => 'name',
            'host' => 'smtp.mailgun.org',
            'port' => 587,
            'from_address' => 'hello@example.com',
            'from_name' => 'Example',
            'encryption' => 'tls',
            'username' => 'username',
            'password' => 'password'
        ];
        $response = $this->post('/credentials', $credential);
        $credential['user_id'] = $user->id;
        $this->assertDatabaseHas('credentials', [
            'name' => $credential['name']
        ]);
        $storedPassword = Credential::where([
            'name' => $credential['name']
        ])->first()->password;
        $this->assertEquals($credential['password'], $storedPassword);
        $response->assertRedirect('/credentials/' . Credential::where([
                'name' => $credential['name']
            ])->first()->id);
        $this->get('/credentials')
            ->assertSee($credential['name'])
            ->assertSee($credential['username']);
    }
    public function testCreateCredentialValidation()
    {
        $this->signIn();
        $credentials = [
            'name' => '',
            'host' => 'blablala',
            'port' => -1,
            'from_address' => '',
            'from_name' => '',
            'encryption' => '12345678901234fafdsfjkldsafjlkasjöfkl',
            'username' => '',
        ];
        $response = $this->post('/credentials', $credentials);
        $response->assertSessionHasErrors([
            'name', 'host', 'port', 'from_address', 'from_name', 'encryption', 'username', 'password'
        ]);
        $this->assertDatabaseMissing('credentials', $credentials);
    }
    public function testUserCanViewACredential()
    {
        $credential = factory(Credential::class)->create();
        $this->actingAs($credential->user)
            ->get('/credentials/' . $credential->id)
            ->assertOk()
            ->assertSee($credential->name)
            ->assertSee($credential->host);
    }
    public function testUsersCannotViewOthersCredentials()
    {
        $this->signIn();
        $ownCredential = factory(Credential::class)->create();
        $credential = factory(Credential::class)->create();
        $this->get('/credentials/' . $credential->id)
            ->assertStatus(403)
            ->assertDontSee($credential->name);
        $this->actingAs($ownCredential->user)
            ->get('/credentials')
            ->assertSee($ownCredential->name)
            ->assertDontSee($credential->name);
    }
    public function testGuestsCannotManageCredentials()
    {
        $this->get('/credentials')
            ->assertRedirect('/login');
        $this->get('/credentials/create')
            ->assertRedirect('/login');
        $this->get('/credentials/1')
            ->assertRedirect('/login');
        $this->delete('/credentials/1')
            ->assertRedirect('/login');
        $this->patch('/credentials/1')
            ->assertRedirect('/login');
        $this->post('/credentials')
            ->assertRedirect('/login');
    }
    public function testUserCanUpdateHisCredentials()
    {
        $credential = factory(Credential::class)->create();
        $changedCredential = [
            'name' => 'changedName',
            'host' => 'bla.host.com',
            'port' => 234,
            'from_address' => 'changed@address.com',
            'from_name' => 'Changed',
            'encryption' => 'changed',
            'username' => 'changed',
            'password' => 'changedPW'
        ];
        $path = '/credentials/' . $credential->id;
        $this->actingAs($credential->user)
            ->patch($path, $changedCredential)
            ->assertRedirect($path);
        $password = $changedCredential['password'];
        unset($changedCredential['password']);
        $this->assertDatabaseHas('credentials', $changedCredential);
        $decryptedPassword = Credential::where($changedCredential)->first()->password;
        $this->assertEquals($password, $decryptedPassword);
    }
    public function testPasswordIsDecryptedOnShowCredential()
    {
        $credential = factory(Credential::class)->create();
        $this->actingAs($credential->user)
            ->get('/credentials/' . $credential->id)
            ->assertSee($credential['password']);
    }
    public function testUpdateCredentialsIsValidated()
    {
        $credential = factory(Credential::class)->create();
        $changedCredential = [
            'name' => '',
            'host' => 'blablala',
            'port' => -1,
            'from_address' => '',
            'from_name' => '',
            'encryption' => '12345678901234fafdsfjkldsafjlkasjöfkl',
            'username' => '',
        ];
        $path = '/credentials/' . $credential->id;
        $this->actingAs($credential->user)
            ->patch($path, $changedCredential)
            ->assertSessionHasErrors([
                'name', 'host', 'port', 'from_address', 'from_name', 'encryption', 'username', 'password'
            ]);
        $this->assertDatabaseHas('credentials', [
            'id' => $credential->id,
            'name' => $credential->name
        ]);
    }
    public function testUserCannotUpdateOthersCredentials()
    {
        $this->signIn();
        $credential = factory(Credential::class)->create();
        $this
            ->patch('/credentials/' . $credential->id, [
                'name' => 'changed'
            ])
            ->assertStatus(403);
        $this->assertDatabaseHas('credentials', $credential->toArray());
    }
}
