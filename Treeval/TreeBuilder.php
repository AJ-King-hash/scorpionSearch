<?php 
namespace Treeval;

use Algorithms\AlgorithmsChainer;
use Enums\AvailableAlgorithm;
use Schemas\Phrases;

/**
 * 
 */
class TreeBuilder
{
    public string $basic_algorithm="";
    public array $mixed_algorithm = [];
    
    public array $phrases=[];
    public string $query="";

    public function __construct(){

    }
    public function setSearchQuery(string $query){
        $this->query=$query;
    }
    public function setPhrases(array $phrases){
        $this->phrases=$phrases;
    }
    public function setBasicAlgorithm(AvailableAlgorithm $algorithm)
    {
        $basic_algorithm=$algorithm->value;
        if(in_array($basic_algorithm,AvailableAlgorithm::all())){
            echo "\nAlgorithm {$basic_algorithm} Set Successfully! \n";
            $this->basic_algorithm = $basic_algorithm;
        }
        else{
            echo "Algorithm Not Available! \n";
        }
    }

    /**
     * @param AvailableAlgorithm[] $algorithms Array of strings, each one of Available Algorithms in The Enum Folder
     */
    public function setMixedAlgorithms(array $algorithms)
    {
        $mixed_algorithms = array_map(fn($alg)=>$alg->value,$algorithms);
        // if(empty($mixed_algorithms)) {
        //     echo "No Mixed Algorithms Set!";
        //     return;
        // }
        foreach ($mixed_algorithms as $algorithm) {
            if (!in_array($algorithm, AvailableAlgorithm::all())) {
                echo "Algorithm '{$algorithm}' Not Available! \n";
                return;
            }else {
                echo "Algorithm '{$algorithm}' Set Successfully! \n";
            }
        }
        $this->mixed_algorithm = $mixed_algorithms;
    }
    public function execute(){
    // testing the chainer
     return (new AlgorithmsChainer($this->basic_algorithm,$this->mixed_algorithm))->getOutput($this->query,$this->phrases);
    }
}