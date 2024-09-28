<?php

namespace App;

class ExamScore
{

    /*
  Check if the given score is beyond the jikunten
  level: 1~5
  kubun: 1: 言語知識（文字・語彙・文法）; 2: 読解; 3: 聴解
  score: score to check
  return: true if the given score is over the jikunten and else
  */
    public function checkKijunTen($level, $kubun, $score)
    {
        switch ($level) {
            case 1:
            case 2:
            case 3:
                switch ($kubun) {
                    case 1:
                    case 2:
                    case 3:
                        return ($score >= 19) ? true : false;
                        break;
                }
                break;
            case 4:
            case 5:
                switch ($kubun) {
                    case 1:
                        return ($score >= 38) ? true : false;
                        break;
                    case 2:
                        return ($score >= 19) ? true : false;
                        break;
                        break;
                }
        }
        return false;
    }

    /*
  Check if the given scores is beyond the jikunten in any kubun
  level: 1~5
  score_kubun1~3: score to check
  return: false if the given scores is under any jikunten and else
  */
    public function checkKijunTenAnyKubun($level, $score_kubun1, $score_kubun2, $score_kubun3)
    {
        switch ($level) {
            case 1:
            case 2:
            case 3:
                if (!$this->checkKijunTen($level, 1, $score_kubun1)) return false;
                if (!$this->checkKijunTen($level, 2, $score_kubun2)) return false;
                if (!$this->checkKijunTen($level, 3, $score_kubun3)) return false;
                return true;
                break;
            case 4:
            case 5:
                if (!$this->checkKijunTen($level, 1, $score_kubun1)) return false;
                if (!$this->checkKijunTen($level, 2, $score_kubun3)) return false;
                return true;
                break;
        }
        return false;
    }
    /*
  Check if the given total score is beyond the Passing score
  level: 1~5
  total_score: score to check
  return: true if the given total score is over the Passing score and else
  */
    public function checkPassingscore($level, $total_score)
    {
        switch ($level) {
            case 1:
                return ($total_score >= 100) ? true : false;
                break;
            case 2:
            case 4:
                return ($total_score >= 90) ? true : false;
                break;
            case 3:
                return ($total_score >= 95) ? true : false;
                break;
            case 5:
                return ($total_score >= 80) ? true : false;
                break;
        }
        return false;
    }

    /*
  Check if the Exam is passed or not
  level: 1~5
  score_kubun1~3: score to check
  return: true if the Exam passed
  */
    public function checkPassingExam($level, $score_kubun1, $score_kubun2, $score_kubun3)
    {
        $total_score = $score_kubun1 + $score_kubun2 + $score_kubun3;
        return $this->checkPassingscore($level, $total_score)  &&  $this->checkKijunTenAnyKubun($level, $score_kubun1, $score_kubun2, $score_kubun3);
    }
}
