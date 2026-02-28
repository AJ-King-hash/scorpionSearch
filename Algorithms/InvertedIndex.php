<?php
namespace Algorithms;

use Algorithms\Interfaces\Chainer;
use Schemas\Phrases;
use Wamania\Snowball\StemmerFactory;

use Algorithms\Interfaces\Algorithm;

class InvertedIndex extends StemmerFactory implements Algorithm, Chainer
{
    public array $inverted_query = [];
    public array $available_mixed_algorithms = [
        ["F1Measure"]
    ];
    public array $matched_query_with_phrases = [];
    public array $last_numbers = [];
    public array $final_numbers = [];
    public array $final_results = [];
    private Chainer $nextChain;
    public function testReflection()
    {
        // echo "reflection Request Send!";
    }
    public function __construct($name)
    {
        // echo $name;
    }
    public function setNextChain(Chainer $chainer)
    {
        $this->nextChain = $chainer;
    }
    public function runAlgorithm(Phrases $phrases_modifier, array $mixed_algorithm = [])
    {
        $split = explode(" ", $phrases_modifier->modified_query);
        foreach ($split as $k_split => $value_split) {
            $this->inverted_query[] = $this->create("english")->stem(strtolower($value_split));
        }
 
        [$this->last_numbers,$this->final_numbers,$this->matched_query_with_phrases]=$phrases_modifier->matchDOCS($this->inverted_query);
 
        $intersected_values = [];
        $values = array_values($this->last_numbers);
        for ($i = 0; $i < count($values) - 1; $i++) {
            $tt1 = array_keys($values[$i]);
            $tt2 = array_keys($values[$i + 1]);
            if (!empty($intersected_values)) {
                $v1 = array_intersect($tt1, $tt2, $intersected_values);
            } else {
                $v1 = array_intersect($tt1, $tt2);
            }
            $intersected_values = array_values($v1);
        }
        $intersected_values = array_unique($intersected_values);
        // print_r($intersected_values);        
        $this->final_results = [];
        foreach ($intersected_values as $k) {
            $this->final_results[] = $phrases_modifier->phrasesWithUniqueNumber[$k];
        }

        if (isset($this->nextChain)) {

            $phrases_modifier->temp_final_results = $this->final_results;
            return $this->nextChain->runAlgorithm($phrases_modifier);
        }
        if (!empty($this->final_results)) {
               return $this->final_results;
            } else {
                return [];
            }
    }

}