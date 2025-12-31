<?php 

namespace Enums;

enum AvailableAlgorithm:string {
    case BOOLEAN="Boolean";
    case NGRAM="Ngram";
    case EDITDISTANCE="EditDistance";
    case INVERTED="InvertedIndex";
    case PERMUTERM="PermutermIndex";
    case POSITIONAL="PositionalIndex";
    CASE TFIDF="TFIDFRanking";
    /**
     * Summary of AveragePrecision: Used With "Positional mixed algorithms"
     */
    CASE MAV="AveragePrecision";
    /**
     * Summary of F1_MEASURE: Used With "Boolean mixed algorithms"
     */
    CASE F1_MEASURE="F1Measure";

    public static function all(){
        $all=[];
        foreach (self::cases() as  $case) {
            $all[]=$case->value;
        }
        return $all;
    }
    public function getSeparator(){
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
        };
    }
}