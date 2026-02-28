<?php
namespace Algorithms;

use Algorithms\Interfaces\Algorithm;
use Algorithms\Interfaces\Chainer;
use Schemas\Phrases;
use Wamania\Snowball\StemmerFactory;

class F1Measure extends StemmerFactory implements Algorithm, Chainer{
    public Chainer $nextChain;
    public function __construct($name)
    {
        // echo "\n".$name."\n";
    }    
    public function setNextChain(Chainer $chainer){
        $this->nextChain=$chainer;
    }
   public function runAlgorithm(Phrases $phrases_modifier, array $mixed_algorithm = []){
                $newDocs = $phrases_modifier->phrasesWithUniqueNumber;
                $newDocs['d5'] = "ai is good for elon musk because he want to be more famous more than anyone";
                $newDocs['d6'] = "ai want to be the best in the techonogies trades";
                $all_count = count($newDocs);
                $ret_count = 3;
                $from_all = 0;
                $from_ret = 0;
                foreach ($phrases_modifier->temp_final_results as $i) {
                    if (in_array($i, $newDocs)) {
                        $from_all++;
                    }
                    if (in_array($i, ["MarkZuckemberg will be Is Attend in  To Learn the More About world Elon Musk Robots To Make their Own Robots Using AI", "ai is good for elon musk because he want to be more famous more than anyone", "ai want to be the best in the techonogies trades"])) {
                        $from_ret++;
                    }
                }
                $Precision = $from_all / $all_count;
                $Recall = $from_ret / $ret_count;
                $F1_Measure = (2 * $Precision * $Recall) / (($Precision + $Recall)==0?1:($Precision + $Recall));

                // echo "the F1-Measure is: " . ($F1_Measure * 100) . "%" . PHP_EOL;
                if(isset($this->nextChain)){
                    return $this->nextChain->runAlgorithm($phrases_modifier);
                }
                if (!empty($phrases_modifier->temp_final_results)) {
                    $documents=array_combine(array_keys($phrases_modifier->temp_final_results),$phrases_modifier->temp_final_results);
                return $documents;
            } else {
                return [];
            }       
        }
    
}