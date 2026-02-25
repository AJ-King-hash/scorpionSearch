<?php
namespace Algorithms;

use Algorithms\Interfaces\Chainer;
use Schemas\Phrases;
use Wamania\Snowball\StemmerFactory;
include(__DIR__ . "/../vendor/autoload.php");

use Algorithms\Interfaces\Algorithm;

class PositionalIndex extends StemmerFactory implements Algorithm, Chainer
{
    public array $positional_query = [];
    public array $available_mixed_algorithms = [
        ["AveragePrecision"]
    ];
    public array $matched_query_with_phrases = [];
    public array $last_numbers = [];
    public array $final_numbers = [];
    public array $final_results = [];
    public array $final_valid_keys = [];
    private Chainer $nextChain;
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
        $this->nextChain = $chainer;
    }
    public function runAlgorithm(Phrases $phrases_modifier, array $mixed_algorithm = [])
    {
        $split = explode(" ", $phrases_modifier->modified_query);
        foreach ($split as $k_split => $value_split) {
            $this->positional_query[] = $this->create("english")->stem(strtolower($value_split));
        }
        [$this->last_numbers, $this->final_numbers, $this->matched_query_with_phrases] = $phrases_modifier->matchDOCS($this->positional_query);
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
        $this->final_results = [];
        foreach ($intersected_values as $k) {
            $this->final_results[] = $phrases_modifier->phrasesWithUniqueNumber[$k];
        }
        $post_list = [];
        $pos_index = [];
        foreach ($this->final_results as $i) {
            $index = array_search($i, $this->final_results);
            $post_list[$index] = explode(" ", $i);
        }
        foreach ($this->positional_query as $val) {
            $inner = [];
            foreach ($post_list as $k => $v) {
                $positions = [];
                foreach ($v as $vv_idx => $vv) {
                    if ($vv === $val) {
                        $positions[] = $vv_idx;
                    }
                }
                $inner[$k] = $positions;
            }
            $pos_index[$val] = $inner;
        }
        echo "post_index=" . json_encode($pos_index) . PHP_EOL;
        $vals = [];
        $checker_array = [];
        foreach ($pos_index as $v) {
            foreach (array_keys($v) as $k2) {
                $checker_array[] = $k2;
            }
        }
        $checker_array = array_unique($checker_array);
        foreach ($checker_array as $k2) {
            $vv = [];
            foreach ($pos_index as $k => $v) {
                foreach ($v[$k2] as $v_one) {
                    $vv[] = [$k => $v_one];
                }
            }
            $vals[$k2] = $vv;
        }
        $valid_keys = [];
        foreach ($vals as $key => $word_list) {
            $words_with_indices = [];
            foreach ($word_list as $d) {
                $word = key($d);
                $index = $d[$word];
                $words_with_indices[] = [$word, $index];
            }
            $query_idx = 0;
            $last_index = -1;
            foreach ($words_with_indices as $wi) {
                list($word, $idx) = $wi;
                if ($query_idx < count($this->positional_query) && $word === $this->positional_query[$query_idx] && ($idx === $last_index + 1 || $last_index === -1)) {
                    $query_idx++;
                    $last_index = $idx;
                }
            }
            if ($query_idx === count($this->positional_query)) {
                $valid_keys[] = $key;
            }
        }
        $this->final_valid_keys=$valid_keys;
        if (isset($this->nextChain)) {

            $phrases_modifier->temp_valid_keys = $this->final_valid_keys;
            $phrases_modifier->temp_final_results=$this->final_results;
            return $this->nextChain->runAlgorithm($phrases_modifier);
        }
        if (!empty($this->final_valid_keys)) {
               return $this->final_results;
            } else {
                return [];
            }
    }

}