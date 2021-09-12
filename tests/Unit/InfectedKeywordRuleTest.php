<?php
namespace Tests\Unit;
use App\Rules\InfectedKeyword;
use Tests\TestCase;
class InfectedKeywordRuleTest extends TestCase
{
    public function testValidKeywordsPassesRule()
    {
        $rule = new InfectedKeyword();
        $this->assertTrue($rule->passes('validation', 'required|numeric|between:0,20'));
    }
    public function testInvalidKeywordsFailsRule()
    {
        $rule = new InfectedKeyword();
        foreach ($rule->getForbiddenValidationTerms() as $keyword) {
            $this->assertFalse($rule->passes('validation', $keyword));
        }
    }
}
