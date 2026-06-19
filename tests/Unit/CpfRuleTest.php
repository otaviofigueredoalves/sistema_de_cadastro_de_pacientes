<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Rules\CpfRule;

class CpfRuleTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_valid_cpf_passes()
    {
        $validator = Validator::make(['cpf' => '60950633100'], ['cpf' => new CpfRule()]);
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_cpf_fails()
    {
        $validator1 = Validator::make(['cpf' => '11111111111'], ['cpf' => new CpfRule()]);
        $this->assertFalse($validator1->passes());

        $validator2 = Validator::make(['cpf' => '12345678901'], ['cpf' => new CpfRule()]);
        $this->assertFalse($validator2->passes());
    }
}
