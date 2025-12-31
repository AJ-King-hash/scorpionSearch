<?php

namespace Treeval;

class SearchRunner{
   public TreeBuilder $treeBuilder;
   public TreeBuilder $runnerResult;
   public function __construct(callable $runner){
      $this->treeBuilder=new TreeBuilder();
      $this->runnerResult=$runner($this->treeBuilder);
        
   }
   public static function create(callable $runner){
      $treeValRunner=new SearchRunner($runner);
      return $treeValRunner;

   }
   public function search(){
      return $this->treeBuilder->execute();
   }
}
