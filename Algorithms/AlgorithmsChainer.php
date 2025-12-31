<?php
namespace Algorithms;

use Algorithms\Interfaces\Chainer;
use Schemas\Phrases;
include(__DIR__."/../vendor/autoload.php");

use Algorithms\Interfaces\Algorithm;
use ReflectionClass;

define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);



/**
* @property Chainer[] $mixed_algorithms
*/
class AlgorithmsChainer
{
    public Chainer $starting_algorithm;
    public array $mixed_algorithms=[];
    public function __construct(public string $basic_algorithm,public array $mixed_algorithms2 = [])
    {

            $this->starting_algorithm=$this->getAlgorithm($basic_algorithm)->newInstanceArgs(["hi from basic ($basic_algorithm) \n"]);

            foreach($mixed_algorithms2 as $another_algorithm){
                
                $this->mixed_algorithms[]=$this->getAlgorithm($another_algorithm)->newInstanceArgs(["hi from ".($another_algorithm)]);
            }        

            
            //all the needed algoritms here!! ($merged)
            $merged=array_merge([$this->starting_algorithm],$this->mixed_algorithms);
            foreach ($merged as $key=>$chain_algorithm) {
                if(!isset($merged[$key+1])){
                    break;
                }
                // /**
                //  * @var ReflectionClass $chain_algorithm
                //  */
                // call_user_func_array([$chain_algorithm,"setNextChain"],[$merged[$key+1]]);
                $chain_algorithm->setNextChain($merged[$key+1]);
            }


           
        }

    public function scanAlgorithms()
    {
        return array_values(array_map(function ($path) {
            return explode(".",$path)[0];
        }, array_filter(scandir(ROOT), function ($path) {
            $ignore = [".", "..","AlgorithmsChainer.php"];
            return !in_array($path, $ignore);
        })));
    }
    public function getAlgorithm(string $algorithm){
        if(in_array($algorithm,$this->scanAlgorithms())){
          
        $checker=new ReflectionClass("Algorithms\\".$algorithm);
        return $checker;
        }else{
        return null;
        }
    }
    public function getOutput(string $query,array $phrases){
        
        echo "\n";
        $phrases_modifier=(new Phrases($query,$phrases,$this->basic_algorithm))->modify();
        return $this->starting_algorithm->runAlgorithm($phrases_modifier,$this->mixed_algorithms);
        
    } 

}
