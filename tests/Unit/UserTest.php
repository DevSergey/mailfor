<?php
namespace Tests\Unit;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class UserTest extends TestCase
{
    use RefreshDatabase;
    public function testExample()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create([
            'admin' => false
        ]);
        $this->assertFalse($user->admin);
        $user = factory(User::class)->create([
            'admin' => true
        ]);
        $this->assertTrue($user->admin);
    }
}
