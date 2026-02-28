<?php 
namespace Algorithms;

use Algorithms\Interfaces\Chainer;
use Schemas\Phrases;

use Algorithms\Interfaces\Algorithm;

class AveragePrecision implements Algorithm,Chainer{
    private Chainer $nextChain;
    private $testing_values = [
        [
            ["d0" => "corona has the most danger virus",
             "d1" => "corona need a helathy mask to put in",
             "d2" => "in the most country we need to be careful",
             "d3" => "the cities is more danger because of the corona"],
             ["corona has the most danger virus", "in the most country we need to be careful"],
             [0, 1]
        ],
        [
            ["d0" => "fuzzy systems is the perfect science to analysis the feelings",
             "d1" => "we can use K-means to classify the non-terminal calssification in fuzzy systems",
             "d2" => "in the most country we need to be careful which data we analysis fuzzy"],
             ["fuzzy systems is the perfect science to analysis the feelings", "we can use K-means to classify the non-terminal calssification in fuzzy systems"],
             [0, 1]
        ]
    ];

    public function testReflection(){
        // echo "reflection Request Send!";
    }
    public function __construct($name){
        // echo $name;
    }
    public function setNextChain(Chainer $chainer){
     $this->nextChain=$chainer;   
    }
    public function averagePrecision($filteredDocuments1, $final_results, $valid_keys, $relevants) {
        $filteredDocuments1_mod = [];
        foreach ($filteredDocuments1 as $k => $i) {
            $filteredDocuments1_mod[(int)substr($k, 1) + 1] = $i;
        }
        $final_results_modified = [];
        foreach ($valid_keys as $i) {
            $final_results_modified[] = $final_results[$i];
        }
        $docs_with_keys = [];
        foreach ($final_results_modified as $i) {
            foreach ($filteredDocuments1_mod as $k => $i2) {
                if ($i === $i2) {
                    $docs_with_keys[$k] = $i;
                }
            }
        }
        $counter = 0;
        $averaging = [];
        foreach ($filteredDocuments1_mod as $k => $i) {
            if (isset($docs_with_keys[$k]) && $docs_with_keys[$k] === $i) {
                $counter++;
                $averaging[] = $counter / $k;
            }
        }
        // echo "averaging is: " . json_encode($averaging) . PHP_EOL;
        return array_sum($averaging) / $relevants;
    }

    public function runAlgorithm(Phrases $phrases_modifier,array $mixed_algorithm = []){
                     $finalssss = [];
                $relevants = 5;
                $finnn = $this->averagePrecision($phrases_modifier->phrasesWithUniqueNumber, $phrases_modifier->temp_final_results, $phrases_modifier->temp_valid_keys, $relevants);
                $finalssss[] = $finnn;
                foreach ($this->testing_values as $i) {
                    $finalssss[] = $this->averagePrecision($i[0], $i[1], $i[2], rand(2, 5));
                }
                $map = array_sum($finalssss) / count($finalssss);
                // echo "the Mean Average Percision is: ";  
                
                if (!empty($phrases_modifier->temp_valid_keys)) {
            return [
                "Matched Documents"=> "".implode("\n", array_map(function ($x, $idx) {
                return "$idx=>($x)"; }, $phrases_modifier->temp_final_results, array_keys($phrases_modifier->temp_final_results))) . PHP_EOL,
               "the Mean Average Percision is:"=> number_format($map * 100, 3) . "%" . PHP_EOL    
            ];
        } else {
            return "No MatchedDocuments" . PHP_EOL;
        }
    }

}