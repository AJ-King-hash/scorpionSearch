<?php

namespace Treeval;

use Enums\AvailableAlgorithm;

class SearchRunner{
   public TreeBuilder $treeBuilder;
   public TreeBuilder $runnerResult;
   public function __construct(callable $runner){
      $this->treeBuilder=new TreeBuilder();
      $this->runnerResult=$runner($this->treeBuilder);
        
   }
   public static function create(string $query,array $phrases,AvailableAlgorithm $algorithm=AvailableAlgorithm::BOOLEAN,$mixed_algorithms=[]){
      $treeValRunner=new SearchRunner(function (TreeBuilder $treeBuilder)use($query,$phrases,$algorithm,$mixed_algorithms) {
      $treeBuilder->setSearchQuery($query); //Search Query
      $treeBuilder->setPhrases($phrases); //Phrases to Search From
      $treeBuilder->setBasicAlgorithm($algorithm); //Basic Algorithm
      $treeBuilder->setMixedAlgorithms($mixed_algorithms); //Mixed Algorithms
      return $treeBuilder;
   });
      return $treeValRunner;

   }
   public function search(){
      return $this->treeBuilder->execute();
   }
}
