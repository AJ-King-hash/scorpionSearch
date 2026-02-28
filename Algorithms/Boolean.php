<?php
namespace Algorithms;

use Algorithms\Interfaces\Chainer;
use ReflectionClass;
use Schemas\Phrases;
use Wamania\Snowball\StemmerFactory;

use Algorithms\Interfaces\Algorithm;

class Boolean extends StemmerFactory implements Algorithm, Chainer
{
    public Chainer $nextChain;
    public array $boolean_query=[];
    public array $available_mixed_algorithms = [
        ["F1Measure"]
    ];
    public array $unaries=[];
    public array $matched_query_with_phrases=[];
    public array $last_numbers=[];
    public array $final_numbers=[];
    public array $final_results=[];
    public function testReflection()
    {
        // echo "reflection Running!";
    }
    public function __construct($name)
    {
        // echo "\n".$name."\n";
    }
    public function setNextChain(Chainer $next_chainer)
    {
        $this->nextChain = $next_chainer;

    }
    /**
     * *
     * @param Chainer[] $mixed_algorithms
     */
    public function runAlgorithm(Phrases $phrases_modifier,array $mixed_algorithms = [])
    {
        
            $split=explode(" ",$phrases_modifier->modified_query);
            foreach ($split as $k_split => $value_split) {
        if(array_key_exists(strtolower($value_split),$phrases_modifier->operators)){
            $this->unaries[]=strtolower($value_split);
        }
        $this->boolean_query[]=$this->create("english")->stem($value_split);
        }
        // echo "You have ordered:" . PHP_EOL;
        foreach ($this->boolean_query as $index) {
            // echo $index . PHP_EOL;
        }
        // echo "\n" . PHP_EOL;
        foreach ($phrases_modifier->filteringRootWords as $key => $value) {
            if(in_array($key,$this->boolean_query)){
                $this->matched_query_with_phrases[$key]=$value;
            } 
        }
        // echo "\n" . PHP_EOL;
        // print_r($this->matched_query_with_phrases);
        foreach ($this->matched_query_with_phrases as $k => $i) {
            $this->last_numbers[$k] = array_filter($i, function($i2) { return $i2 === 1; });
            $this->final_numbers[$k] = $phrases_modifier->arrayToInt(array_values($i));
        }
        // echo "\n" . PHP_EOL;
        // print_r($this->last_numbers);
        // print_r($this->final_numbers);
         $keyys = array_keys($this->matched_query_with_phrases);
            $result = $this->matched_query_with_phrases[$keyys[0]];
            for ($i = 1; $i < count($keyys); $i++) {
                
                    $op = $this->unaries[$i - 1];
                    $result = array_map($phrases_modifier->operators[$op], $result, $this->matched_query_with_phrases[$keyys[$i]]);
            }
            // print_r($result);
            $final_result = array_filter($result, function($v) { return $v === 1; });
            $this->final_results = [];
            foreach (array_keys($final_result) as $k) {
                $this->final_results[] = $phrases_modifier->phrasesWithUniqueNumber[$k];
            }
        
            // print_r($mixed_algorithms);
        if(isset($this->nextChain)){

            $phrases_modifier->temp_final_results=$this->final_results;
            return $this->nextChain->runAlgorithm($phrases_modifier);
        }

        return $this->final_results;
    }

}