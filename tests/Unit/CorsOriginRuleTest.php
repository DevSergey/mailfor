<?php
namespace Tests\Unit;
use App\Rules\CorsOrigin;
use Tests\TestCase;
class CorsOriginRuleTest extends TestCase
{
    public function testValidUrlPassesCorsOriginRule()
    {
        $rule = new CorsOrigin();
        $this->assertTrue($rule->passes('cors_origin', 'https:
    }
    public function testInvalidUrlFailsCorsOriginRule()
    {
        $rule = new CorsOrigin();
        $this->assertFalse($rule->passes('cors_origin', 'no valid url'));
    }
    public function testWildcardPassesCorsOriginRule()
    {
        $rule = new CorsOrigin();
        $this->assertTrue($rule->passes('cors_origin', '*'));
    }
}
