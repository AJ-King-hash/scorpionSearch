<?php 
namespace Algorithms;

use Algorithms\Interfaces\Chainer;
use Schemas\Phrases;
use Wamania\Snowball\StemmerFactory;
include(__DIR__."/../vendor/autoload.php");

use Algorithms\Interfaces\Algorithm;

class PermutermIndex extends StemmerFactory implements Algorithm,Chainer{
    public array $permuterm_query=[];
    public array $available_mixed_algorithms = [
        ["F1Measure"]
    ];
    public array $matched_query_with_phrases=[];
    public array $last_numbers=[];
    public array $final_numbers=[];
    public array $final_results=[];
    private Chainer $nextChain;
    public function testReflection(){
        echo "reflection Request Send!";
    }
    public function __construct($name){
        echo $name;
    }
    public function setNextChain(Chainer $chainer){
        $this->nextChain=$chainer;
    }
    public function runAlgorithm(Phrases $phrases_modifier,array $mixed_algorithm = []){
          $split=explode(" ",$phrases_modifier->modified_query);
            foreach ($split as $k_split => $value_split) {
        
        foreach ($phrases_modifier->permuterm_index_dicts as $permu_k => $permu_v) {
                        foreach ($permu_v as $per_v) {
                            if (preg_match("/$per_v/", $value_split) && array_intersect(str_split($value_split), str_split($permu_k)) === str_split($value_split)) {
                                $breaking = false;
                                for ($idd = 0; $idd < strlen($value_split); $idd++) {
                                    if ($value_split[$idd] !== $permu_k[$idd]) {
                                        $breaking = true;
                                    }
                                }
                                if (!$breaking) {
                                    $this->permuterm_query[] = $this->create("english")->stem(strtolower($permu_k));
                                    break 2;
                                }
                            }
                        }
                    }
        }

        echo "You have ordered:" . PHP_EOL;
        foreach ($this->permuterm_query as $index) {
            echo $index . PHP_EOL;
        }
        echo "\n" . PHP_EOL;
        foreach ($phrases_modifier->filteringRootWords as $key => $value) {
            if(in_array($key,$this->permuterm_query)){
                $this->matched_query_with_phrases[$key]=$value;
            } 
        }
        echo "\n" . PHP_EOL;
        // print_r($this->matched_query_with_phrases);
        foreach ($this->matched_query_with_phrases as $k => $i) {
            $this->last_numbers[$k] = array_filter($i, function($i2) { return $i2 === 1; });
            $this->final_numbers[$k] = $phrases_modifier->arrayToInt(array_values($i));
        }
        echo "\n" . PHP_EOL;
        // print_r($this->last_numbers);
        // print_r($this->final_numbers);
         $keyys = array_keys($this->matched_query_with_phrases);
            $result = $this->matched_query_with_phrases[$keyys[0]];
            for ($i = 0; $i < count($keyys); $i++) {
                // permuterm condition here!!!
                 $result = array_map(function($x, $y) { return ($x || $y)!=1?0:1; }, $result, $this->matched_query_with_phrases[$keyys[$i]]);
            }
            $final_result = array_filter($result, function($v) { return $v == 1; });
            foreach (array_keys($final_result) as $k) {
                $this->final_results[] = $phrases_modifier->phrasesWithUniqueNumber["d".$k];
            }
        
        if(isset($this->nextChain)){

            $phrases_modifier->temp_final_results=$this->final_results;
            return $this->nextChain->runAlgorithm($phrases_modifier);
        }

        if (!empty($this->final_results)) {
            $documents=array_combine(array_keys($this->final_results),$this->final_results);

                return $documents;
            } else {
                return [];
            }
    }

}