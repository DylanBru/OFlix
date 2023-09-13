<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;

class CalculTest extends TestCase
{

    private const TEST_CASES = [
        [
            "numbers" => [20, 10],
            "result" => 30
        ],
        [
            "numbers" => [1000, 5023],
            "result" => 6023
        ],
        [
            "numbers" => [5, 899],
            "result" => 904
        ],
        [
            "numbers" => [3, 5],
            "result" => 8
        ]
    ];

    public function testAddition(){

        foreach (self::TEST_CASES as $test) {
            // ici j'ai un scénario de test pour mon addition
            $result = $this->addition($test["numbers"][0], $test["numbers"][1]);
            
            // J'affirme que le resultat prévu dans mes tests case est égal au résultat de ma fonction addition
            $this->assertEquals($test["result"],$result);
        }

    }


    private function addition(int $num1, int $num2) : int
    {
        // on return le résultat de l'addition du param 1 avec le param 2
        return $num1 + $num2;

    }
}
