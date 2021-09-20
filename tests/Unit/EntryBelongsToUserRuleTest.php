<?php
namespace Tests\Unit;
use App\Credential;
use App\Rules\EntryBelongsToUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class EntryBelongsToUserRuleTest extends TestCase
{
    use RefreshDatabase;
    public function testValidEntryPassesRule()
    {
        $user = $this->signIn();
        $credential = factory(Credential::class)->create([
            'user_id' => $user
        ]);
        $rule = new EntryBelongsToUser('credentials');
        $this->assertTrue($rule->passes('credential_id', $credential->id));
    }
    public function testInvalidEntryFailsRule()
    {
        $this->signIn();
        $credential = factory(Credential::class)->create();
        $rule = new EntryBelongsToUser('credentials');
        $this->assertFalse($rule->passes('credential_id', $credential->id));
    }
}
