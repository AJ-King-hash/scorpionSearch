# ScorpSearch 🔍

A powerful PHP Composer package for implementing various search algorithms, including Edit Distance, Boolean Search, Permuterm Index, Inverted Index, Positional Index, N-gram, TF-IDF Ranking, and more. It supports mixing algorithms for enhanced results and allows easy extension with custom algorithms.

This package is designed for efficient text searching and ranking in PHP applications. It uses a builder pattern for flexibility and auto-wiring for detecting new algorithms.

## Installation 📦

Install the package via Composer:

```bash
composer require scorpion/scorpsearch:v1.0.0
```

Ensure you have PHP 8.0+ and Composer installed.

## Usage 🚀

To use ScorpSearch, include the autoloader and create a `SearchRunner` instance with a `TreeBuilder` callback. Set the search query, phrases to search from, basic algorithm, and optional mixed algorithms.

Below are examples of each algorithm, with simple explanations. All examples assume you've included the autoloader And Have The Current Documents Below From Some Storage (Database,Excel..etc):

```php
// For The Pure PHP Developer You Need To Import This (No Need To Import It For Laravel)
include(__DIR__ . "/vendor/autoload.php");


// These Are The Docs That Will Be Used In The Examples:
$testing_phrases=[
        "AI Will be the most efficient tools in the world programming world",
        "MarkZuckemberg refers that the AI will be the greatest fear of the Programmers",
        "Two Or More Languages Are Supporting AI Developing With High Performance Is Better than One",
        "MarkZuckemberg will be Is Attend in  To Learn the More About world Elon Musk Robots To Make their Own Robots Using AI",
        "I Highly Recommend To Keep Studying in the world  Programming Languages I do not think we need to fear from AI ,Otherwise, It Will Helps Us A lot"
];

```



### 1. Edit Distance 📏

This algorithm calculates the minimum number of operations (insertions, deletions, substitutions) needed to transform one string into another. It's useful for finding similar strings, like correcting typos.

```php
use Enums\AvailableAlgorithm;
use Treeval\SearchRunner;

$edit_distance = SearchRunner::create("cating -> cutting",[],AvailableAlgorithm::EDITDISTANCE,[])->search();

print_r($edit_distance);

```

**Simple Explanation**: It measures how "close" two words are by counting changes. For "cating -> cutting", it might return a distance of 2 (insert 't' and 'u').

### 2. Normal Boolean Search ⚖️

This performs a boolean search using operators like AND (&), OR, and exact phrases. It filters phrases that match the query logically.

```php

$boolean_search_results = SearchRunner::create("ai & Elon",$testing_phrases,AvailableAlgorithm::BOOLEAN,[])->search();

print_r($boolean_search_results);


```

**Simple Explanation**: It checks if phrases contain both "ai" AND "Elon" (using &). Results include only matching phrases, like the one mentioning Elon Musk and AI.

### 3. Mixed Boolean + F1 Measure 📊

This combines boolean search with F1 Measure for scoring results based on precision and recall. F1 helps rank how well matches balance completeness and accuracy.

```php
$query_results = SearchRunner::create("ai Elon",$testing_phrases, AvailableAlgorithm::BOOLEAN,[AvailableAlgorithm::F1_MEASURE])->search();

print_r($query_results);

```

**Simple Explanation**: First, it finds boolean matches for "ai" and "Elon". Then, F1 scores them (0-1 scale) based on how precisely and completely they match, ranking the best ones higher.

### 4. Normal Permuterm Index 🔄

This index allows wildcard searches (e.g., for prefixes/suffixes) by rotating terms. It's great for autocomplete or partial matches.

```php
$query_results = SearchRunner::create("in the worl",$testing_phrases,AvailableAlgorithm::PERMUTERM,[])->search();
print_r($query_results);

```

**Simple Explanation**: It rotates words (e.g., "world" becomes "world$", "orld$w", etc.) to find matches like "in the worl" to "in the world".

### 5. Mixed Permuterm Index + F1 Measure (RECOMMENDED!) 📈

This enhances Permuterm searches with F1 scoring for better ranking of partial matches.

```php

//Recommended: Accuracy & Familiar Like Google Search Drive & Documents Has Priority.
$query_results = SearchRunner::create("in",$testing_phrases,AvailableAlgorithm::PERMUTERM,[AvailableAlgorithm::F1_MEASURE])->search();
print_r($query_results);

```

**Simple Explanation**: Finds partial matches like in normal Permuterm, then uses F1 to score and rank them by relevance (precision + recall).

### 6. Inverted Index 📑

This creates a map of words to their locations in phrases, speeding up searches for specific terms.

```php
$query_results = SearchRunner::create("Keep Studying Programming",$testing_phrases,AvailableAlgorithm::INVERTED,[])->search();
print_r($query_results);

```

**Simple Explanation**: It lists where each word appears (e.g., "Programming" in phrase 5). Quick for finding phrases with all query words.

### 7. Positional Index 📍

Similar to Inverted Index but tracks exact positions of words, useful for phrase searches or proximity.

```php
$query_results = SearchRunner::create("will be",$testing_phrases,AvailableAlgorithm::POSITIONAL,[])->search();
print_r($query_results);

```

**Simple Explanation**: Finds where "will" and "be" appear next to each other (e.g., positions 2-3 in phrase 1).

### 8. Positional Index Mixed + Mean Average Precision (MAV) 📉

Combines Positional Index with MAV to average precision scores across results for ranking.

```php
$query_results = SearchRunner::create("will be",$testing_phrases,AvailableAlgorithm::POSITIONAL,[AvailableAlgorithm::MAV])->search();
print_r($query_results);


```

**Simple Explanation**: Gets positional matches, then uses MAV to calculate an overall precision score, helping rank the most relevant phrases.

### 9. N-gram 🔠

Breaks text into overlapping sequences (n-grams) for fuzzy matching, good for spell checking or similarity.

```php
$query_results = SearchRunner::create("in the",$testing_phrases,AvailableAlgorithm::NGRAM,[])->search();
print_r($query_results);

```

**Simple Explanation**: Splits into chunks (e.g., "in the" → "in ", "n t", " th", etc.) and finds similar chunks in phrases for approximate matches.

### 10. TF-IDF Ranking (SPECIAL ONE!) 📊

Ranks phrases by Term Frequency-Inverse Document Frequency, highlighting important unique terms,we Will Use Here Another Testing Documents For More Clear Example.

```php

$another_phrases =[
        "AI will be great AI is the most significint thing in the world and it will be great",
        "the people who attend to use other people minds to make the world more brightness using AI it will be great and better for the future",
        "the thing you should know the most is to make the world bigger than you think to make it more brightness using AI"
];
$query_results = SearchRunner::create("the future using using people minds to make AI better",$another_phrases,AvailableAlgorithm::TFIDF,[])->search();

print_r($query_results);


```

**Simple Explanation**: Scores phrases higher if query terms appear often in them but rarely overall (e.g., "future" and "minds" boost relevant phrases).

## Using in Laravel 11 Or Above 🛡️

ScorpSearch integrates seamlessly with Laravel 12, which was released on February 24, 2025, with zero breaking changes from Laravel 11 and updates to upstream dependencies. You can use it for in-memory searching and ranking of text data, such as searching through blog posts, product descriptions, or any array of phrases loaded from your database or other sources.

### Installation in Laravel

1. Require the package via Composer (same as general installation):

   ```bash
   composer require scorpion/scorpsearch
   ```

2. Laravel's autoloading will handle the namespaces automatically. No additional configuration is needed unless you want to publish a config file or bind it to the IoC container.

### Example: Integrating into a Laravel Controller

Here's an example of using ScorpSearch in a Laravel 12 controller for a simple search feature. Assume you have a model like `Post` with a `content` field, and you want to search through post contents using Boolean search.

Create a controller, e.g., `app/Http/Controllers/SearchController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Enums\AvailableAlgorithm;
use Treeval\TreeBuilder;
use Treeval\SearchRunner;
use App\Models\Post; // Assuming you have a Post model

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query', 'in the worl'); // Get query from request, default example

        // Load phrases from database (e.g., post contents)
        $phrases = Post::pluck('content')->toArray();

        $results = SearchRunner::create($query,$phrases,AvailableAlgorithm::PERMUTERM,[AvailableAlgorithm::F1_MEASURE])->search();

        return response()->json($results);
    }
}
```

### Routing

Add a route in `routes/web.php` or `routes/api.php`:

```php
use App\Http\Controllers\SearchController;

Route::get('/search', [SearchController::class, 'search']);
```

### Explanation

- **Loading Phrases**: Fetch data from your Eloquent models (e.g., `Post::pluck('content')`) to use as the phrases array.
- **Search Execution**: Use the same `SearchRunner` pattern inside the controller method.
- **Response**: Return the results as JSON or render a view with the data.
- **Advanced Integration**: For larger applications, consider injecting `SearchRunner` via dependency injection or creating a service class for reusability.

This setup allows you to leverage ScorpSearch's algorithms for custom search logic without relying on full-text database searches, ideal for fuzzy or algorithmic matching.

## Adding Custom Algorithms 🛠️

You can extend the package by adding your own algorithm. Implement the `Algorithm` and `Chainer` interfaces in a new class under the `/Algorithms` folder. The package uses auto-wiring to detect it automatically.

1. First! Publish The Package Files:

   ```bash
   composer require Vendor:publish --tag= scorpion/scorpsearch
   ```

2. Extends Your Algorithm Class
Example implementation (`Algorithms/YourOwnAlgorithm.php`):

```php
<?php 
namespace Algorithms;

use Algorithms\Interfaces\Chainer;
use Algorithms\Interfaces\Algorithm;
use Schemas\Phrases;

class YourOwnAlgorithm implements Algorithm, Chainer {
    private Chainer $nextChain;
    
    public function testReflection() {
        echo "reflection Request Send!";
    }
    
    public function __construct($name) {
        echo $name;
    }
    
    public function setNextChain(Chainer $chainer) {
        $this->nextChain = $chainer;
    }
    
    public function runAlgorithm(Phrases $phrases_modifier, array $mixed_algorithm = []) {
        // Your algorithm logic here
    }
}
```

Then, add it to the `AvailableAlgorithm` enum (`Enums/AvailableAlgorithm.php`):

```php
<?php 

namespace Enums;

enum AvailableAlgorithm: string {
    case BOOLEAN = "Boolean";
    case NGRAM = "Ngram";
    case EDITDISTANCE = "EditDistance";
    case INVERTED = "InvertedIndex";
    case PERMUTERM = "PermutermIndex";
    case POSITIONAL = "PositionalIndex";
    case TFIDF = "TFIDFRanking";
    /**
     * Summary of AveragePrecision: Used With "Positional mixed algorithms"
     */
    case MAV = "AveragePrecision";
    /**
     * Summary of F1_MEASURE: Used With "Boolean mixed algorithms"
     */
    case F1_MEASURE = "F1Measure";
    
    case YOURALGORITHM = "YourOwnAlgorithm"; // Value must match the class name (without .php)
    
    public static function all() {
        $all = [];
        foreach (self::cases() as $case) {
            $all[] = $case->value;
        }
        return $all;
    }
    
    public function getSeparator() {
        return match($this) {
            self::BOOLEAN => PhraseSeparatorType::BOOLEAN,
            self::NGRAM => PhraseSeparatorType::SPACES,
            self::EDITDISTANCE => PhraseSeparatorType::SPACES,
            self::INVERTED => PhraseSeparatorType::SPACES,
            self::PERMUTERM => PhraseSeparatorType::SPACES,
            self::POSITIONAL => PhraseSeparatorType::SPACES,
            self::TFIDF => PhraseSeparatorType::SPACES,
            self::F1_MEASURE => PhraseSeparatorType::SPACES,
            self::MAV => PhraseSeparatorType::SPACES, 
            self::YOURALGORITHM => PhraseSeparatorType::SPACES, 
        };
    }
}
```

**Simple Explanation**: Create your class with the required methods. Add an enum case with the value matching your class name. Now you can use `AvailableAlgorithm::YOURALGORITHM` in searches. The `getSeparator()` defines how queries are split (e.g., by spaces).

## Contributing 🤝

Fork the repository on GitHub, make changes, and submit a pull request. Issues and feature requests are welcome!

## License 📄

This package is open-source under the MIT License. See LICENSE file for details.

Made with ❤️ by Ali Yazan Jahjah