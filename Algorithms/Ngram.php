<?php 
namespace Algorithms;

use Algorithms\Interfaces\Chainer;
use Schemas\Phrases;
use Wamania\Snowball\StemmerFactory;
include(__DIR__."/../vendor/autoload.php");

use Algorithms\Interfaces\Algorithm;

class Ngram extends StemmerFactory implements Algorithm,Chainer{
    public Chainer $nextChain;
    public array $ngram_query=[];
    public array $available_mixed_algorithms = [
        ["F1Measure"]
    ];
    public array $unaries=[];
    public array $matched_query_with_phrases=[];
    public array $last_numbers=[];
    public array $final_numbers=[];
    public array $final_results=[];
    public array $tmp_grams=[];
    public array $last_ngrams=[];
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
          foreach ($phrases_modifier->ngram_index_dicts as $permu_k => $permu_v) {
                            foreach ($permu_v as $per_v) {
                            if (preg_match("/$per_v/", $value_split) && $permu_k!=null) {
                                $breaking = false;
                                for ($idd = 0; $idd < strlen($value_split); $idd++) {
                                    if (isset($permu_k[$idd]) && $value_split[$idd] !== $permu_k[$idd]) {
                                        $breaking = true;
                                    }
                                }
                                if (!$breaking) {
                                           
                                    $this->tmp_grams[] = $this->create("english")->stem(strtolower($permu_k));
                                    $this->ngram_query[] = $this->create("english")->stem(strtolower($permu_k));
                                    break 2;
                                }
                            }
                        }
                    }
                    if (!empty($this->tmp_grams)) {
                        $this->last_ngrams[$value_split] = $this->tmp_grams;
                        $this->tmp_grams = [];
                    }
      
    }
        [$this->last_numbers, $this->final_numbers, $this->matched_query_with_phrases] = $phrases_modifier->matchDOCS($this->ngram_query);

            if (!empty($this->last_ngrams)) {
               
                $datas = [];
                foreach ($this->last_ngrams as $k => $v) {
                    $datas[] = "($k)=>" . implode(" ", array_map(function($x) { return "($x)"; }, $v));
                }
                return $datas;
            } else {
                return [];
            }
}

}