<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Rules\CnsRule;

class CnsRuleTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_valid_cns_passes()
    {
        $validator1 = Validator::make(['cns' => '873691540517552'], ['cns' => new CnsRule()]);
        $this->assertTrue($validator1->passes()); // Provisório válido
        
        $validator2 = Validator::make(['cns' => '123456789012346'], ['cns' => new CnsRule()]);
        $this->assertFalse($validator2->passes()); // Não válido no alg.
    }

    public function test_invalid_cns_fails()
    {
        $validator1 = Validator::make(['cns' => '111111111111111'], ['cns' => new CnsRule()]);
        $this->assertFalse($validator1->passes());

        $validator2 = Validator::make(['cns' => '123456789012345'], ['cns' => new CnsRule()]);
        $this->assertFalse($validator2->passes());
    }
}
