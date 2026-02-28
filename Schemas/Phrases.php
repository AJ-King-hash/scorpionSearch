<?php 

namespace Schemas;

use Enums\AvailableAlgorithm;
use Enums\PhraseSeparatorType;
use Wamania\Snowball\Stemmer\Stemmer;
use Wamania\Snowball\StemmerFactory;
error_reporting(E_ALL && ~E_WARNING);
class Phrases{
    public PhraseSeparatorType $separator;
    public array $phrasesWithUniqueNumber=[];
    public array $phrasesAsArrayOfWords=[];
    public array $stemmedToRootPhrasesWords=[];
    public array $filteringRootWords=[];
    public array $inverted_keys=[];
    public array $permuterm_index_dicts=[];
    public array $last_vals=[];
    public array $ngram_index_dicts=[];
    public Stemmer $stemmer;
    public array $modified_phrases=[];
    public string $modified_query="";
    public array $operators=[];
    public array $temp_final_results=[];
    public array $temp_valid_keys=[];
    public function __construct(public string $query,public array $phrases,public string $algorithm){

        $this->separator=AvailableAlgorithm::from($algorithm)->getSeparator();
        $this->stemmer=StemmerFactory::create('english');
        $this->operators=[
            "&" => function($x, $y) { return $x && $y; },
            "|" => function($x, $y) { return $x || $y; },
            "^" => function($x, $y) { return $x xor $y; },
            "&!" => function($x, $y) { return $x && !$y; },
            "|!" => function($x, $y) { return $x || !$y; }
        ];
    }
    public function modify(){
        
        $this->modifyQuery();
        $this->modifyPhrases();
            
        // print_r([$modified_query,$modified_phrases]);
        return $this;
    }
    public function modifyQuery(){
        $modified_query="";
        switch($this->separator){
            case PhraseSeparatorType::BOOLEAN:
                // $modified_query=str_replace(" "," AND ",$this->query);
                $modified_query=$this->query;
                
                break;
            case PhraseSeparatorType::SPACES:
                $modified_query=$this->query;
                break;
        }
        $this->modified_query=$modified_query;
        // return $modified_query;
    }
     public function explodeString($text) {
        $result = [];
        for ($i = 0; $i < strlen($text); $i++) {
            $result[] = substr($text, $i, 2);
        }
        return array_filter($result, function($val) { return strlen($val) === 2; });
    }
     public function arrayToInt($arr) {
        return (int)implode("", $arr);
    }
    public function modifyPhrases(){
        foreach ($this->phrases as $k=>$phrase) {
            $index=$k;
            $this->phrasesWithUniqueNumber["d$index"]=$phrase;
            $this->phrasesAsArrayOfWords["d$index"]=array_unique(explode(" ",$phrase));
            // echo "\n d$index='$phrase' \n\n";
        }  
        // print_r($this->phrasesAsArrayOfWords);
        foreach ($this->phrasesAsArrayOfWords as $key => $phrase) {
            $stemmed=[];
            foreach ($phrase as $p) {
                $stemmed[]=$this->stemmer->stem($p);
            }      
                $this->stemmedToRootPhrasesWords[$key]=$stemmed;
            
         }
        // print_r($this->stemmedToRootPhrasesWords);
        // add all the words of the docs in one array:
        $words=[];
        foreach ($this->stemmedToRootPhrasesWords as $root) {
            $words=array_merge($root,$words);
        }       
        $unique_words=array_unique($words);
        foreach ($unique_words as $u_word) {
            $old_rooting=[];
            foreach ($this->stemmedToRootPhrasesWords as $k => $rooting) {
                $old_rooting[$k]=(int) in_array($u_word, $rooting);
            }
            $this->filteringRootWords[$u_word]=$old_rooting;
        }    
        // print_r($this->filteringRootWords);
        // echo "\n\n";
        foreach ($this->filteringRootWords as $k => $fi) {
            // echo "$k ---".json_encode($fi).PHP_EOL;
            $this->inverted_keys[]=$k;
        }
        sort($this->inverted_keys);
            foreach ($this->inverted_keys as $k1) {
            $popped = [];
            $ngrams = [];
            $arr = [];
            $modified_arr = [];
            $ngrams[] = $k1[0] . "$";
            $exploded = $this->explodeString($k1);
            foreach ($exploded as $val) {
                if (strlen($val) !== 1) $ngrams[] = $val;
            }
            $ngrams[] = "^" . $k1[strlen($k1) - 1];
            $this->ngram_index_dicts[$k1] = $ngrams;

            $arr = str_split($k1);
            $lenn = count($arr) + 1;
            for ($i = 0; $i < $lenn; $i++) {
                if ($i === 0) {
                    $modified_arr[] = implode("", $arr) . "\\b";
                } elseif ($i === $lenn - 1) {
                    $modified_arr[] = "\\b" . implode("", $popped);
                } else {
                    $temp = implode("", $arr) . "$" . implode("", $popped);
                    $parts = explode("$", $temp);
                    foreach ($parts as $ik => $itemp) {
                        if ("^$itemp" !== "^" && "$itemp$" !== "$") {
                            if ($ik === 0) {
                                $modified_arr[] = "\\b$itemp";
                            } else {
                                $modified_arr[] = "$itemp\\b";
                            }
                        }
                    }
                }
                if (count($arr) > 0) {
                    $d = array_shift($arr);
                    $popped[] = $d;
                }
            }
            $this->permuterm_index_dicts[$k1] = $modified_arr;
            foreach ($this->filteringRootWords as $k => $i) {
                if ($k === $k1) {
                    $this->last_vals[$k] = array_filter($i, function($i2) { return $i2 === 1; });
                }
            }
        }
        // echo "\n" . PHP_EOL;
        
    }
    public function matchDOCS(array $algorithm_query){
        $matched_docs_with_query=[];
        $last_numbers=[];
        $final_numbers=[];
        // echo "You have ordered:" . PHP_EOL;
        foreach ($algorithm_query as $index) {
            // echo $index . PHP_EOL;
        }
        // echo "\n" . PHP_EOL;

        foreach ($this->filteringRootWords as $k => $i) {
            if (in_array($k, $algorithm_query)) {
                $matched_docs_with_query[$k] = $i;
            }
        }
        // echo "\n" . PHP_EOL;
        // print_r($matched_docs_with_query);
        foreach ($matched_docs_with_query as $k => $i) {
            $last_numbers[$k] = array_filter($i, function ($i2) {
                return $i2 === 1; });
            $final_numbers[$k] = $this->arrayToInt(array_values($i));
        }
        // echo "\n" . PHP_EOL;
        // print_r($final_numbers);
        // print_r($last_numbers);

        return [$last_numbers,$final_numbers,$matched_docs_with_query];
    }   
    
}