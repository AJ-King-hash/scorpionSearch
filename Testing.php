<?php
/* 

############ ScorpSearch ##########
############ ScorpSearch ##########
############ ScorpSearch ##########

*/

use Algorithms\AlgorithmsChainer;
use Enums\AvailableAlgorithm;
use Treeval\TreeBuilder;
use Treeval\SearchRunner;

include(__DIR__ . "/vendor/autoload.php");


$testing_phrases=[
        "AI Will be the most efficient tools in the world programming world",
        "MarkZuckemberg refers that the AI will be the greatest fear of the Programmers",
        "Two Or More Languages Are Supporting AI Developing With High Performance Is Better than One",
        "MarkZuckemberg will be Is Attend in  To Learn the More About world Elon Musk Robots To Make their Own Robots Using AI",
        "I Highly Recommend To Keep Studying in the world  Programming Languages I do not think we need to fear from AI ,Otherwise, It Will Helps Us A lot"
];

// //1- EDIT DISTANCE (DONE!)
// $edit_distance = SearchRunner::create("cating -> cutting",[],AvailableAlgorithm::EDITDISTANCE,[])->search();

// print_r($edit_distance);


// //2- Normal BOOLEAN (DONE!)
// $boolean_search_results = SearchRunner::create("ai & Elon",$testing_phrases,AvailableAlgorithm::BOOLEAN,[])->search();

// print_r($boolean_search_results);


//3- MIXED BOOLEAN + F1_MEASURE  (DONE!)
// $query_results = SearchRunner::create("ai Elon",$testing_phrases, AvailableAlgorithm::BOOLEAN,[AvailableAlgorithm::F1_MEASURE])->search();

// print_r($query_results);


// //4- NORMAL PERMUTERM INDEX (DONE!)
// $query_results = SearchRunner::create("in the worl",$testing_phrases,AvailableAlgorithm::PERMUTERM,[])->search();
// print_r($query_results);


// // 5- MIXED PERMUTERM INDEX + F1_MEASURE (DONE!) 
// ```Recommended! ( Accuracy+Familiar Like Google Search Drive+ Documents Has Priority))```
// ```Recommended! ( Accuracy+Familiar Like Google Search Drive+ Documents Has Priority))```
// ```Recommended! ( Accuracy+Familiar Like Google Search Drive+ Documents Has Priority))```
// $query_results = SearchRunner::create("in the worl",$testing_phrases,AvailableAlgorithm::PERMUTERM,[AvailableAlgorithm::F1_MEASURE])->search();
// print_r($query_results);


// //6- Inverted INDEX (DONE!)
// $query_results = SearchRunner::create("Keep Studying Programming",$testing_phrases,AvailableAlgorithm::INVERTED,[])->search();
// print_r($query_results);


//7- Positional INDEX (DONE!)
// $query_results = SearchRunner::create("will be",$testing_phrases,AvailableAlgorithm::POSITIONAL,[])->search();
// print_r($query_results);

// //8- Positional INDEX Mixed + (Mean Average percision) (DONE!)
// $query_results = SearchRunner::create("will be",$testing_phrases,AvailableAlgorithm::POSITIONAL,[AvailableAlgorithm::MAV])->search();
// print_r($query_results);


// // 9- N-gram (DONE!)
// $query_results = SearchRunner::create("in the",$testing_phrases,AvailableAlgorithm::NGRAM,[])->search();
// print_r($query_results);

// 10- TF-IDF Ranking (DONE!) (Special One!)
// $another_phrases =[
//         "AI will be great AI is the most significint thing in the world and it will be great",
//         "the people who attend to use other people minds to make the world more brightness using AI it will be great and better for the future",
//         "the thing you should know the most is to make the world bigger than you think to make it more brightness using AI"
// ];
// $query_results = SearchRunner::create("the future using using people minds to make AI better",$another_phrases,AvailableAlgorithm::TFIDF,[])->search();

// print_r($query_results);



