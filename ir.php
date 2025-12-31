<?php

// Basic Snowball Stemmer approximation in PHP (simple English stemmer for demonstration)
class SimpleStemmer {
    private $rules = [
        '/(ed|ing|es|s)$/' => '', // Basic plural and tense removal
        '/ational$/' => 'ate',
        '/tional$/' => 'tion',
        // Add more rules as needed for better accuracy
    ];

    public function stem($word) {
        $word = strtolower($word);
        foreach ($this->rules as $pattern => $replacement) {
            if (preg_match($pattern, $word)) {
                return preg_replace($pattern, $replacement, $word);
            }
        }
        return $word;
    }
}

// Main OOP class for the Information Retrieval System
class InformationRetrievalSystem {
    private $stemmer;
    private $documents = [];
    private $filteredDocuments = [];
    private $filteredDocuments1 = [];
    private $rootWordDocs = [];
    private $filtering = [];
    private $query = [];
    private $filtering2 = [];
    private $final = [];
    private $unaries = [];
    private $final_results = [];
    private $inverted_k = [];
    private $inverted_val = [];
    private $last_vals = [];
    private $last_last = [];
    private $tmp_grams = [];
    private $permuterm_index_dicts = [];
    private $ngram_index_dicts = [];
    private $last_ngrams = [];
    private $permus = [];
    private $operators = [];
    private $partial_on = false;
    private $the_match_res = "";

    private $testing_values = [
        [
            ["d0" => "corona has the most danger virus",
             "d1" => "corona need a helathy mask to put in",
             "d2" => "in the most country we need to be careful",
             "d3" => "the cities is more danger because of the corona"],
             ["corona has the most danger virus", "in the most country we need to be careful"],
             [0, 1]
        ],
        [
            ["d0" => "fuzzy systems is the perfect science to analysis the feelings",
             "d1" => "we can use K-means to classify the non-terminal calssification in fuzzy systems",
             "d2" => "in the most country we need to be careful which data we analysis fuzzy"],
             ["fuzzy systems is the perfect science to analysis the feelings", "we can use K-means to classify the non-terminal calssification in fuzzy systems"],
             [0, 1]
        ]
    ];

    public function __construct() {
        $this->stemmer = new SimpleStemmer();
        $this->operators = [
            "and" => function($x, $y) { return $x && $y; },
            "or" => function($x, $y) { return $x || $y; },
            "xor" => function($x, $y) { return $x xor $y; },
            "and not" => function($x, $y) { return $x && !$y; },
            "or not" => function($x, $y) { return $x || !$y; }
        ];
    }

    public function averagePrecision($filteredDocuments1, $final_results, $valid_keys, $relevants) {
        $filteredDocuments1_mod = [];
        foreach ($filteredDocuments1 as $k => $i) {
            $filteredDocuments1_mod[(int)substr($k, 1) + 1] = $i;
        }
        $final_results_modified = [];
        foreach ($valid_keys as $i) {
            $final_results_modified[] = $final_results[$i];
        }
        $docs_with_keys = [];
        foreach ($final_results_modified as $i) {
            foreach ($filteredDocuments1_mod as $k => $i2) {
                if ($i === $i2) {
                    $docs_with_keys[$k] = $i;
                }
            }
        }
        $counter = 0;
        $averaging = [];
        foreach ($filteredDocuments1_mod as $k => $i) {
            if (isset($docs_with_keys[$k]) && $docs_with_keys[$k] === $i) {
                $counter++;
                $averaging[] = $counter / $k;
            }
        }
        echo "averaging is: " . json_encode($averaging) . PHP_EOL;
        return array_sum($averaging) / $relevants;
    }

    public function editDistance($arr) {
        list($s1, $s2) = $arr;
        $m = strlen($s1);
        $n = strlen($s2);
        $dp = array_fill(0, $m + 1, array_fill(0, $n + 1, 0));
        for ($i = 0; $i <= $m; $i++) {
            $dp[$i][0] = $i;
        }
        for ($j = 0; $j <= $n; $j++) {
            $dp[0][$j] = $j;
        }
        for ($i = 1; $i <= $m; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                if ($s1[$i - 1] === $s2[$j - 1]) {
                    $dp[$i][$j] = $dp[$i - 1][$j - 1];
                } else {
                    $dp[$i][$j] = 1 + min($dp[$i - 1][$j], $dp[$i][$j - 1], $dp[$i - 1][$j - 1]);
                }
            }
        }
        echo "distance we need to change $s1 to $s2 is: " . $dp[$m][$n] . PHP_EOL;
    }

    public function explodeString($text) {
        $result = [];
        for ($i = 0; $i < strlen($text); $i++) {
            $result[] = substr($text, $i, 2);
        }
        return array_filter($result, function($val) { return strlen($val) === 2; });
    }

    public function submit1($docs) {
        $this->documents = $docs;
        $this->filteredDocuments1 = [];
        $this->filteredDocuments = [];
        foreach ($docs as $doc) {
            $index = array_search($doc, $this->documents);
            $this->filteredDocuments1["d$index"] = $doc;
            $this->filteredDocuments["d$index"] = array_unique(explode(" ", $doc));
            echo "\n d$index='$doc' \n" . PHP_EOL;
        }
        $this->rootWordDocs = [];
        foreach ($this->filteredDocuments as $k => $doc) {
            $stemmed = [];
            foreach ($doc as $d) {
                $stemmed[] = $this->stemmer->stem($d);
            }
            $this->rootWordDocs[$k] = $stemmed;
        }
        $words = [];
        foreach ($this->rootWordDocs as $root) {
            foreach ($root as $word) {
                $words[] = $word;
            }
        }
        $wordss = array_unique($words);
        $this->filtering = [];
        foreach ($wordss as $w) {
            $old_rooting = [];
            foreach ($this->rootWordDocs as $k => $rooting) {
                $old_rooting[$k] = (int)in_array($w, $rooting);
            }
            $this->filtering[$w] = $old_rooting;
        }
        echo "\n" . PHP_EOL;
        foreach ($this->filtering as $k => $i) {
            echo "$k --- " . json_encode($i) . PHP_EOL;
            $this->inverted_k[] = $k;
        }
        sort($this->inverted_k);
        foreach ($this->inverted_k as $k1) {
            $popped = [];
            $ngrams = [];
            $arr = [];
            $modified_arr = [];
            $ngrams[] = $k1[0] . "$";
            $exploded = $this->explodeString($k1);
            foreach ($exploded as $val) {
                if (strlen($val) !== 1) $ngrams[] = $val;
            }
            $ngrams[] = "^" . $k1[strlen($k1) - 1];
            $this->ngram_index_dicts[$k1] = $ngrams;

            $arr = str_split($k1);
            $lenn = count($arr) + 1;
            for ($i = 0; $i < $lenn; $i++) {
                if ($i === 0) {
                    $modified_arr[] = implode("", $arr) . "\\b";
                } elseif ($i === $lenn - 1) {
                    $modified_arr[] = "\\b" . implode("", $popped);
                } else {
                    $temp = implode("", $arr) . "$" . implode("", $popped);
                    $parts = explode("$", $temp);
                    foreach ($parts as $ik => $itemp) {
                        if ("^$itemp" !== "^" && "$itemp$" !== "$") {
                            if ($ik === 0) {
                                $modified_arr[] = "\\b$itemp";
                            } else {
                                $modified_arr[] = "$itemp\\b";
                            }
                        }
                    }
                }
                if (count($arr) > 0) {
                    $d = array_shift($arr);
                    $popped[] = $d;
                }
            }
            $this->permuterm_index_dicts[$k1] = $modified_arr;
            foreach ($this->filtering as $k => $i) {
                if ($k === $k1) {
                    $this->last_vals[$k] = array_filter($i, function($i2) { return $i2 === 1; });
                }
            }
        }
        echo "\n" . PHP_EOL;
    }

    public function arrayToInt($arr) {
        return (int)implode("", $arr);
    }

    public function submitPar($pattern, $string) {
        $this->partial_on = !$this->partial_on;
        if ($this->partial_on) {
            $this->the_match_res = preg_match("/$pattern/", $string) ? $string : "";
        } else {
            $this->the_match_res = "";
        }
    }

    public function submit($query_input, $selected_methods) {
        $this->query = [];
        $this->unaries = [];
        $operators = [
            "&" => function($x, $y) { return $x && $y; },
            "|" => function($x, $y) { return $x || $y; },
            "^" => function($x, $y) { return $x xor $y; },
            "&!" => function($x, $y) { return $x && !$y; },
            "|!" => function($x, $y) { return $x || !$y; }
        ];

        if ($this->partial_on) {
            $split = explode(" ", $this->the_match_res);
        } else {
            $split = explode(" ", $query_input);
        }

        foreach ($split as $index) {
            if (array_key_exists(strtolower($index), $operators)) {
                $this->unaries[] = strtolower($index);
            } else {
                if (in_array("Inverted Index", $selected_methods) && !in_array(strtolower($index), ["i", "the", "we", "is", "an", "and"])) {
                    $this->query[] = $this->stemmer->stem(strtolower($index));
                } elseif (in_array("Permuterm Index", $selected_methods)) {
                    foreach ($this->permuterm_index_dicts as $permu_k => $permu_v) {
                        foreach ($permu_v as $per_v) {
                            if (preg_match("/$per_v/", $index) && array_intersect(str_split($index), str_split($permu_k)) === str_split($index)) {
                                $breaking = false;
                                for ($idd = 0; $idd < strlen($index); $idd++) {
                                    if ($index[$idd] !== $permu_k[$idd]) {
                                        $breaking = true;
                                    }
                                }
                                if (!$breaking) {
                                    $this->query[] = $this->stemmer->stem(strtolower($permu_k));
                                    break 2;
                                }
                            }
                        }
                    }
                } elseif (in_array("N-gram", $selected_methods)) {
                    foreach ($this->ngram_index_dicts as $permu_k => $permu_v) {
                        foreach ($permu_v as $per_v) {
                            if (preg_match("/$per_v/", $index)) {
                                $breaking = false;
                                for ($idd = 0; $idd < strlen($index); $idd++) {
                                    if (isset($permu_k[$idd]) && $index[$idd] !== $permu_k[$idd]) {
                                        $breaking = true;
                                    }
                                }
                                if (!$breaking) {
                                    $this->tmp_grams[] = $this->stemmer->stem(strtolower($permu_k));
                                    $this->query[] = $this->stemmer->stem(strtolower($permu_k));
                                    break 2;
                                }
                            }
                        }
                    }
                    if (!empty($this->tmp_grams)) {
                        $this->last_ngrams[$index] = $this->tmp_grams;
                        $this->tmp_grams = [];
                    }
                } else {
                    $this->query[] = $this->stemmer->stem(strtolower($index));
                }
            }
        }

        echo "You have ordered:" . PHP_EOL;
        foreach ($this->query as $index) {
            echo $index . PHP_EOL;
        }
        echo "\n" . PHP_EOL;

        $this->filtering2 = [];
        foreach ($this->filtering as $k => $i) {
            if (in_array($k, $this->query)) {
                $this->filtering2[$k] = $i;
            }
        }
        echo "\n" . PHP_EOL;
        print_r($this->filtering2);
        foreach ($this->filtering2 as $k => $i) {
            $this->last_last[$k] = array_filter($i, function($i2) { return $i2 === 1; });
            $this->final[$k] = $this->arrayToInt(array_values($i));
        }
        echo "\n" . PHP_EOL;
        print_r($this->final);
        print_r($this->last_last);

        if (in_array("Boolean", $selected_methods)) {
            $keyys = array_keys($this->filtering2);
            $result = $this->filtering2[$keyys[0]];
            for ($i = 1; $i < count($keyys); $i++) {
                if (in_array("Permuterm Index", $selected_methods)) {
                    $result = array_map(function($x, $y) { return $x || $y; }, $result, $this->filtering2[$keyys[$i]]);
                } else {
                    $op = $this->unaries[$i - 1];
                    $result = array_map($operators[$op], $result, $this->filtering2[$keyys[$i]]);
                }
            }
            print_r($result);
            $final_result = array_filter($result, function($v) { return $v === 1; });
            $this->final_results = [];
            foreach (array_keys($final_result) as $k) {
                $this->final_results[] = $this->filteredDocuments1[$k];
            }
            // Evaluation F-Measure
            $answer = 'yes'; // Simulate user input for demo
            if ($answer === 'yes') {
                $newDocs = $this->filteredDocuments1;
                $newDocs['d5'] = "ai is good for elon musk because he want to be more famous more than anyone";
                $newDocs['d6'] = "ai want to be the best in the techonogies trades";
                $all_count = count($newDocs);
                $ret_count = 3;
                $from_all = 0;
                $from_ret = 0;
                foreach ($this->final_results as $i) {
                    if (in_array($i, $newDocs)) {
                        $from_all++;
                    }
                    if (in_array($i, ["MarkZuckemberg will be Is Attend in  To Learn the More About world Elon Musk Robots To Make their Own Robots Using AI", "ai is good for elon musk because he want to be more famous more than anyone", "ai want to be the best in the techonogies trades"])) {
                        $from_ret++;
                    }
                }
                $Precision = $from_all / $all_count;
                $Recall = $from_ret / $ret_count;
                $F1_Measure = (2 * $Precision * $Recall) / ($Precision + $Recall);
                echo $F1_Measure . PHP_EOL;
                echo "the F1-Measure is: " . ($F1_Measure * 100) . "%" . PHP_EOL;
            }

            if (!empty($this->final_results)) {
                echo "Matched Documents: " . implode("\n", array_map(function($x, $idx) { return "$idx=>($x)"; }, $this->final_results, array_keys($this->final_results))) . PHP_EOL;
            } else {
                echo "No MatchedDocuments" . PHP_EOL;
            }
        }

        if (in_array("Inverted Index", $selected_methods)) {
            $intersected_values = [];
            $values = array_values($this->last_last);
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
                $this->final_results[] = $this->filteredDocuments1[$k];
            }
            if (!empty($this->final_results)) {
                echo "Matched Documents: " . implode("\n", array_map(function($x, $idx) { return "$idx=>($x)"; }, $this->final_results, array_keys($this->final_results))) . PHP_EOL;
            } else {
                echo "No MatchedDocuments" . PHP_EOL;
            }
        }

        if (in_array("Positional Index", $selected_methods)) {
            $intersected_values = [];
            $values = array_values($this->last_last);
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
                $this->final_results[] = $this->filteredDocuments1[$k];
            }
            $post_list = [];
            $pos_index = [];
            foreach ($this->final_results as $i) {
                $index = array_search($i, $this->final_results);
                $post_list[$index] = explode(" ", $i);
            }
            foreach ($this->query as $val) {
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
                    if ($query_idx < count($this->query) && $word === $this->query[$query_idx] && ($idx === $last_index + 1 || $last_index === -1)) {
                        $query_idx++;
                        $last_index = $idx;
                    }
                }
                if ($query_idx === count($this->query)) {
                    $valid_keys[] = $key;
                }
            }
            // Evaluation Average Precision
            $answer = 'yes'; // Simulate
            if ($answer === 'yes') {
                $finalssss = [];
                $relevants = 5;
                $finnn = $this->averagePrecision($this->filteredDocuments1, $this->final_results, $valid_keys, $relevants);
                $finalssss[] = $finnn;
                foreach ($this->testing_values as $i) {
                    $finalssss[] = $this->averagePrecision($i[0], $i[1], $i[2], rand(2, 5));
                }
                $map = array_sum($finalssss) / count($finalssss);
                echo "the Mean Average Percision is: " . number_format($map * 100, 3) . "%" . PHP_EOL;
            }

            if (!empty($valid_keys)) {
                $matched = [];
                foreach ($valid_keys as $x) {
                    $matched[] = "$x=>(" . $this->final_results[$x] . ")";
                }
                echo "Matched Documents: " . implode("\n \n", $matched) . PHP_EOL;
            } else {
                echo "No MatchedDocuments" . PHP_EOL;
            }
        }

        if (in_array("N-gram", $selected_methods)) {
            if (!empty($this->last_ngrams)) {
                $datas = [];
                foreach ($this->last_ngrams as $k => $v) {
                    $datas[] = "($k)=>" . implode(" ", array_map(function($x) { return "($x)"; }, $v));
                }
                echo "Matched Words: " . implode(",\n", $datas) . PHP_EOL;
            } else {
                echo "No MatchedDocuments" . PHP_EOL;
            }
        }

        if (in_array("TF-IDF Ranking", $selected_methods)) {
            $the_query = "the future using using people minds to make AI better";
            $TF_IDF_docs = [
                0 => "AI will be great AI is the most significint thing in the world and it will be great",
                1 => "the people who attend to use other people minds to make the world more brightness using AI it will be great and better for the future",
                2 => "the thing you should know the most is to make the world bigger than you think to make it more brightness using AI"
            ];
            $words = [];
            $docs_repeated_words = ["chosen_one" => [], 0 => [], 1 => [], 2 => []];
            $for_weights = ["chosen_one" => [], 0 => [], 1 => [], 2 => []];

            $splitted_query = explode(" ", $the_query);
            foreach ($splitted_query as $i) {
                $words[] = $i;
            }
            foreach ($TF_IDF_docs as $i2) {
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
            foreach ($TF_IDF_docs as $k => $i2) {
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
                $words_full_count = count(explode(" ", $TF_IDF_docs[$k] ?? $the_query));
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
            $priorities[$max_index] = $TF_IDF_docs[$max_index];
            $priorities[2] = $TF_IDF_docs[2];
            $priorities[0] = $TF_IDF_docs[0];
            $shows = [];
            foreach ($priorities as $k => $i) {
                $shows[] = [$k, $i];
            }
            echo "Matched Documents: wanted query==>($the_query)" . implode("", array_map(function($x) { return "\n \n {$x[0]}=>({$x[1]}) \n \n"; }, $shows)) . PHP_EOL;
        }
    }
}

// Web-based interface simulation (since PHP is web-oriented)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $irs = new InformationRetrievalSystem();

    // Handle document submission
    if (isset($_POST['submit1'])) {
        $docs = explode("\n", $_POST['docs']);
        $irs->submit1(array_filter($docs));
    }

    // Handle query submission
    if (isset($_POST['submit'])) {
        $query_input = $_POST['query'];
        $selected_methods = $_POST['methods'] ?? [];
        $irs->submit($query_input, $selected_methods);
    }

    // Handle edit distance
    if (isset($_POST['edit_distance'])) {
        $arr = explode(",", $_POST['edit_input']);
        $irs->editDistance($arr);
    }

    // Handle partial (wildcard)
    if (isset($_POST['submit_par'])) {
        $pattern = $_POST['pattern'];
        $string = "in the world"; // As in original
        $irs->submitPar($pattern, $string);
    }
} else {
    // HTML form for input
    echo <<<HTML
    <html>
    <body>
        <h1>Information Retrieval System</h1>
        <form method="post">
            <h2>Add Documents</h2>
            <textarea name="docs" rows="10" cols="50">
AI Will be the most efficient tools in the world programming world
MarkZuckemberg refers that the AI will be the greatest fear of the Programmers
Two Or More Languages Are Supporting AI Developing With High Performance Is Better than One
MarkZuckemberg will be Is Attend in  To Learn the More About world Elon Musk Robots To Make their Own Robots Using AI
I Highly Recommend To Keep Studying in the world  Programming Languages I do not think we need to fear from AI,Otherwise,It Will Helps Us A lot
            </textarea><br>
            <input type="submit" name="submit1" value="Set Documents">
        </form>
        
        <form method="post">
            <h2>Select Query</h2>
            <input type="text" name="query" size="50" placeholder="Enter query"><br>
            <label>Methods:</label><br>
            <input type="checkbox" name="methods[]" value="Boolean"> Boolean<br>
            <input type="checkbox" name="methods[]" value="Inverted Index"> Inverted Index<br>
            <input type="checkbox" name="methods[]" value="Positional Index"> Positional Index<br>
            <input type="checkbox" name="methods[]" value="Permuterm Index"> Permuterm Index<br>
            <input type="checkbox" name="methods[]" value="N-gram"> N-gram<br>
            <input type="checkbox" name="methods[]" value="TF-IDF Ranking"> TF-IDF Ranking<br>
            <input type="submit" name="submit" value="Submit Query">
        </form>
        
        <form method="post">
            <h2>Edit Distance</h2>
            <input type="text" name="edit_input" placeholder="word1,word2"><br>
            <input type="submit" name="edit_distance" value="Calculate">
        </form>
        
        <form method="post">
            <h2>Wildcard Pattern</h2>
            <input type="text" name="pattern" placeholder="Pattern"><br>
            <input type="submit" name="submit_par" value="Toggle Wildcard">
        </form>
    </body>
    </html>
HTML;
}

?>