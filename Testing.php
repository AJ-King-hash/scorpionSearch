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

// //1- EDIT DISTANCE (DONE!)
// $edit_distance = SearchRunner::create(function (TreeBuilder $treeBuilder) {
//     $treeBuilder->setSearchQuery("cating -> cutting"); //Search Query
//     $treeBuilder->setPhrases([]); //Phrases to Search From
//     $treeBuilder->setBasicAlgorithm(AvailableAlgorithm::EDITDISTANCE); //Basic Algorithm
//     $treeBuilder->setMixedAlgorithms([]); //Mixed Algorithms
//     return $treeBuilder;
// })->search();

// print_r($edit_distance);


// //2- Normal BOOLEAN (DONE!)
// $boolean_search_results = SearchRunner::create(function (TreeBuilder $treeBuilder) {
//     $treeBuilder->setSearchQuery("ai & Elon"); //Search Query
//     $treeBuilder->setPhrases([
//         "AI Will be the most efficient tools in the world programming world",
//         "MarkZuckemberg refers that the AI will be the greatest fear of the Programmers",
//         "Two Or More Languages Are Supporting AI Developing With High Performance Is Better than One",
//         "MarkZuckemberg will be Is Attend in  To Learn the More About world Elon Musk Robots To Make their Own Robots Using AI",
//         "I Highly Recommend To Keep Studying in the world  Programming Languages I do not think we need to fear from AI ,Otherwise, It Will Helps Us A lot"
//     ]); //Phrases to Search From
//     $treeBuilder->setBasicAlgorithm(AvailableAlgorithm::BOOLEAN); //Basic Algorithm
//     $treeBuilder->setMixedAlgorithms([]); //Mixed Algorithms
//     return $treeBuilder;
// })->search();

// print_r($boolean_search_results);


// //3- MIXED BOOLEAN + F1_MEASURE  (DONE!)
// $query_results = SearchRunner::create(function (TreeBuilder $treeBuilder) {
//     $treeBuilder->setSearchQuery("ai Elon"); //Search Query
//     $treeBuilder->setPhrases([
//         "AI Will be the most efficient tools in the world programming world",
//         "MarkZuckemberg refers that the AI will be the greatest fear of the Programmers",
//         "Two Or More Languages Are Supporting AI Developing With High Performance Is Better than One",
//         "MarkZuckemberg will be Is Attend in  To Learn the More About world Elon Musk Robots To Make their Own Robots Using AI",
//         "I Highly Recommend To Keep Studying in the world  Programming Languages I do not think we need to fear from AI ,Otherwise, It Will Helps Us A lot"
//     ]); //Phrases to Search From
//     $treeBuilder->setBasicAlgorithm(AvailableAlgorithm::BOOLEAN); //Basic Algorithm
//     $treeBuilder->setMixedAlgorithms([AvailableAlgorithm::F1_MEASURE]); //Mixed Algorithms
//     return $treeBuilder;
// })->search();

// print_r($query_results);


// //4- NORMAL PERMUTERM INDEX (DONE!)
// $query_results = SearchRunner::create(function (TreeBuilder $treeBuilder) {
//     $treeBuilder->setSearchQuery("in the worl"); //Search Query
//     $treeBuilder->setPhrases([
//         "AI Will be the most efficient tools in the world programming world",
//         "MarkZuckemberg refers that the AI will be the greatest fear of the Programmers",
//         "Two Or More Languages Are Supporting AI Developing With High Performance Is Better than One",
//         "MarkZuckemberg will be Is Attend in  To Learn the More About world Elon Musk Robots To Make their Own Robots Using AI",
//         "I Highly Recommend To Keep Studying in the world  Programming Languages I do not think we need to fear from AI ,Otherwise, It Will Helps Us A lot"
//     ]); //Phrases to Search From
//     $treeBuilder->setBasicAlgorithm(AvailableAlgorithm::PERMUTERM); //Basic Algorithm
//     $treeBuilder->setMixedAlgorithms([]); //Mixed Algorithms
//     return $treeBuilder;
// })->search();
// print_r($query_results);

// // 5- MIXED PERMUTERM INDEX + F1_MEASURE (DONE!)
// $query_results = SearchRunner::create(function (TreeBuilder $treeBuilder) {
//     $treeBuilder->setSearchQuery("in the worl"); //Search Query
//     $treeBuilder->setPhrases([
//         "AI Will be the most efficient tools in the world programming world",
//         "MarkZuckemberg refers that the AI will be the greatest fear of the Programmers",
//         "Two Or More Languages Are Supporting AI Developing With High Performance Is Better than One",
//         "MarkZuckemberg will be Is Attend in  To Learn the More About world Elon Musk Robots To Make their Own Robots Using AI",
//         "I Highly Recommend To Keep Studying in the world  Programming Languages I do not think we need to fear from AI ,Otherwise, It Will Helps Us A lot"
//     ]); //Phrases to Search From
//     $treeBuilder->setBasicAlgorithm(AvailableAlgorithm::PERMUTERM); //Basic Algorithm
//     $treeBuilder->setMixedAlgorithms([]); //Mixed Algorithms
//     return $treeBuilder;
// })->search();

// print_r($query_results);


// //6- Inverted INDEX (DONE!)
// $query_results = SearchRunner::create(function (TreeBuilder $treeBuilder) {
//     $treeBuilder->setSearchQuery("Keep Studying Programming"); //Search Query
//     $treeBuilder->setPhrases([
//         "AI Will be the most efficient tools in the world programming world",
//         "MarkZuckemberg refers that the AI will be the greatest fear of the Programmers",
//         "Two Or More Languages Are Supporting AI Developing With High Performance Is Better than One",
//         "MarkZuckemberg will be Is Attend in  To Learn the More About world Elon Musk Robots To Make their Own Robots Using AI",
//         "I Highly Recommend To Keep Studying in the world  Programming Languages I do not think we need to fear from AI ,Otherwise, It Will Helps Us A lot"
//     ]); //Phrases to Search From
//     $treeBuilder->setBasicAlgorithm(AvailableAlgorithm::INVERTED); //Basic Algorithm
//     $treeBuilder->setMixedAlgorithms([]);
//     return $treeBuilder;
// })->search();

// print_r($query_results);

// //7- Positional INDEX (DONE!)
// $query_results = SearchRunner::create(function (TreeBuilder $treeBuilder) {
//     $treeBuilder->setSearchQuery("will be"); //Search Query
//     $treeBuilder->setPhrases([
//         "AI Will be the most efficient tools in the world programming world",
//         "MarkZuckemberg refers that the AI will be the greatest fear of the Programmers",
//         "Two Or More Languages Are Supporting AI Developing With High Performance Is Better than One",
//         "MarkZuckemberg will be Is Attend in  To Learn the More About world Elon Musk Robots To Make their Own Robots Using AI",
//         "I Highly Recommend To Keep Studying in the world  Programming Languages I do not think we need to fear from AI ,Otherwise, It Will Helps Us A lot"
//     ]); //Phrases to Search From
//     $treeBuilder->setBasicAlgorithm(AvailableAlgorithm::POSITIONAL); //Basic Algorithm
//     $treeBuilder->setMixedAlgorithms([]);
//     return $treeBuilder;
// })->search();

// print_r($query_results);

// //8- Positional INDEX Mixed + (Mean Average percision) (DONE!)
// $query_results = SearchRunner::create(function (TreeBuilder $treeBuilder) {
//     $treeBuilder->setSearchQuery("will be"); //Search Query
//     $treeBuilder->setPhrases([
//         "AI Will be the most efficient tools in the world programming world",
//         "MarkZuckemberg refers that the AI will be the greatest fear of the Programmers",
//         "Two Or More Languages Are Supporting AI Developing With High Performance Is Better than One",
//         "MarkZuckemberg will be Is Attend in  To Learn the More About world Elon Musk Robots To Make their Own Robots Using AI",
//         "I Highly Recommend To Keep Studying in the world  Programming Languages I do not think we need to fear from AI ,Otherwise, It Will Helps Us A lot"
//     ]); //Phrases to Search From
//     $treeBuilder->setBasicAlgorithm(AvailableAlgorithm::POSITIONAL); //Basic Algorithm
//     $treeBuilder->setMixedAlgorithms([AvailableAlgorithm::MAV]);
//     return $treeBuilder;
// })->search();

// print_r($query_results);


// // 9- N-gram (DONE!)
// $query_results = SearchRunner::create(function (TreeBuilder $treeBuilder) {
//     $treeBuilder->setSearchQuery("in the"); //Search Query
//     $treeBuilder->setPhrases([
//         "AI Will be the most efficient tools in the world programming world",
//         "MarkZuckemberg refers that the AI will be the greatest fear of the Programmers",
//         "Two Or More Languages Are Supporting AI Developing With High Performance Is Better than One",
//         "MarkZuckemberg will be Is Attend in  To Learn the More About world Elon Musk Robots To Make their Own Robots Using AI",
//         "I Highly Recommend To Keep Studying in the world  Programming Languages I do not think we need to fear from AI ,Otherwise, It Will Helps Us A lot"
//     ]); //Phrases to Search From
//     $treeBuilder->setBasicAlgorithm(AvailableAlgorithm::NGRAM); //Basic Algorithm
//     $treeBuilder->setMixedAlgorithms([]);
//     return $treeBuilder;
// })->search();

// print_r($query_results);

// 10- TF-IDF Ranking (DONE!)
// $query_results = SearchRunner::create(function (TreeBuilder $treeBuilder) {
//     $treeBuilder->setSearchQuery("the future using using people minds to make AI better"); //Search Query
//     $treeBuilder->setPhrases([
//         "AI will be great AI is the most significint thing in the world and it will be great",
//         "the people who attend to use other people minds to make the world more brightness using AI it will be great and better for the future",
//         "the thing you should know the most is to make the world bigger than you think to make it more brightness using AI"
//     ]); //Phrases to Rank The Priority
//     $treeBuilder->setBasicAlgorithm(AvailableAlgorithm::TFIDF); //Basic Algorithm
//     $treeBuilder->setMixedAlgorithms([]);
//     return $treeBuilder;
// })->search();

// print_r($query_results);



