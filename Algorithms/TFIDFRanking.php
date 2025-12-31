<?php 
namespace Algorithms;

use Algorithms\Interfaces\Chainer;
use Schemas\Phrases;
include(__DIR__."/../vendor/autoload.php");

use Algorithms\Interfaces\Algorithm;

class TFIDFRanking implements Algorithm,Chainer{
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

        $words = [];
            $docs_repeated_words = ["chosen_one" => [], 0 => [], 1 => [], 2 => []];
            $for_weights = ["chosen_one" => [], 0 => [], 1 => [], 2 => []];

            $splitted_query = explode(" ", $phrases_modifier->modified_query);
            foreach ($splitted_query as $i) {
                $words[] = $i;
            }
            foreach ($phrases_modifier->phrases as $i2) {
                foreach (explode(" ", $i2) as $i3) {
                    $words[] = $i3;
                }
            }
            $words = array_unique($words);
            $repeatedWords = [];
            foreach ($words as $i) {
                $counter = 0;
                foreach ($splitted_query as $i2) {
                    if ($i === $i2) $counter++;
                }
                $repeatedWords[$i] = $counter;
            }
            $docs_repeated_words["chosen_one"] = $repeatedWords;
            $repeatedWords = [];
            foreach ($phrases_modifier->phrases as $k => $i2) {
                foreach ($words as $i) {
                    $counter = 0;
                    foreach (explode(" ", $i2) as $i3) {
                        if ($i === $i3) $counter++;
                    }
                    $repeatedWords[$i] = $counter;
                }
                $docs_repeated_words[$k] = $repeatedWords;
                $repeatedWords = [];
            }

            foreach ($docs_repeated_words as $k => $i) {
                $words_full_count = count(explode(" ", $TF_IDF_docs[$k] ?? $phrases_modifier->modified_query));
                $repeatedWords = [];
                foreach ($i as $k2 => $i2) {
                    if ($i2 !== 0) {
                        $par = 1 + log10($i2 / $words_full_count);
                        if ($par === 0.0) $par = 1;
                        $sum_y = 0;
                        foreach ($docs_repeated_words as $y) {
                            $sum_y += $y[$k2] ?? 0;
                        }
                        $data = $par * log10($words_full_count / $sum_y);
                    } else {
                        $data = 0;
                    }
                    $repeatedWords[$k2] = $data;
                }
                $for_weights[$k] = $repeatedWords;
            }

            $chosen_one = $for_weights["chosen_one"];
            $scores = [];
            foreach ($for_weights as $k => $i) {
                if ($k !== "chosen_one") {
                    $score_sub = [];
                    foreach ($i as $k2 => $i2) {
                        $score_sub[] = $i2 * ($chosen_one[$k2] ?? 0);
                    }
                    $scores[$k] = $score_sub;
                }
            }
            $full_scores = [];
            foreach ($scores as $k => $i) {
                $full_scores[$k] = abs(array_sum($i));
            }
            echo "scores with query=\n" . json_encode($full_scores) . PHP_EOL;
            $max_index = array_search(max($full_scores), $full_scores);
            $priorities = [];
            $priorities[$max_index] = $phrases_modifier->phrases[$max_index];
            $priorities[2] = $phrases_modifier->phrases[2];
            $priorities[0] = $phrases_modifier->phrases[0];
            $shows = [];
            foreach ($priorities as $k => $i) {
                $shows[] = [$k, $i];
            }
            return "Matched Documents: wanted query==>({$phrases_modifier->modified_query})" . implode("", array_map(function($x) { return "\n \n {$x[0]}=>({$x[1]}) \n \n"; }, $shows)) . PHP_EOL;
    }

}