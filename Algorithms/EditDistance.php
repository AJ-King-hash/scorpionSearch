<?php
namespace Algorithms;

use Algorithms\Interfaces\Chainer;
use Schemas\Phrases;
include(__DIR__ . "/../vendor/autoload.php");

use Algorithms\Interfaces\Algorithm;

class EditDistance implements Algorithm, Chainer
{
    
    private Chainer $nextChain;
    public array $available_mixed_algorithms = [
       [""]
    ];
    public function testReflection()
    {
        echo "reflection Request Send!";
    }
    public function __construct($name)
    {
        echo $name;
    }
    public function setNextChain(Chainer $chainer)
    {

    }

    /**
     * *
     * @param Chainer[] $mixed_algorithm
     */
    public function runAlgorithm(Phrases $phrases_modifier,array $mixed_algorithm = [])
    {
        $query=$phrases_modifier->modified_query;
        $arr=explode(" -> ",$query);
        list($s1, $s2) = $arr;
        $m = strlen($s1);
        $n = strlen($s2);
        $dp = array_fill(0, $m + 1, array_fill(0, $n + 1, 0));
        for ($i = 0; $i <= $m; $i++) {
            $dp[$i][0] = $i;
        }
        for ($j = 0; $j <= $n; $j++) {
            $dp[0][$j] = $j;
        }
        for ($i = 1; $i <= $m; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                if ($s1[$i - 1] === $s2[$j - 1]) {
                    $dp[$i][$j] = $dp[$i - 1][$j - 1];
                } else {
                    $dp[$i][$j] = 1 + min($dp[$i - 1][$j], $dp[$i][$j - 1], $dp[$i - 1][$j - 1]);
                }
            }
        }
        echo "distance we need to change $s1 to $s2 is: " . $dp[$m][$n] . PHP_EOL;   
    }

}