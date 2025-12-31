<?php

namespace Algorithms\Interfaces;

use Schemas\Phrases;

interface Chainer
{
    public function setNextChain(Chainer $chainer);
    public function runAlgorithm(Phrases $phrases_modifier,array $mixed_algorithm = []);
}