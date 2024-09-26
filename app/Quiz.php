<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\QuestionBank;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Quiz extends Model
{
    protected $table = "quizzes";

    public static function getRecordWithSlug($slug)
    {
        return Quiz::where('slug', '=', $slug)->first();
    }
    public static function getRecordWithID($id)
    {
        return Quiz::where('id', '=', $id)->first();
    }

    /**
     * Returns the category name for the selected quiz
     * @return [type] [description]
     */
    public function category()
    {
        return $this->belongsTo('App\QuizCategory');
    }

    /**
     * This method returns the set of questions available for the sent quiz id
     * @return [type] [description]
     */
    public function getQuestions()
    {
        return DB::table('questionbank_quizzes')
            ->join('subjects', 'questionbank_quizzes.subject_id', '=', 'subjects.id')
            ->where('questionbank_quizzes.quize_id', '=', $this->id)
            ->orderBy('subjects.subject_order')
            ->orderBy('questionbank_quizzes.id')
            ->get();
    }
    /*public function getQuestions()
    {
         return DB::table('questionbank_quizzes')
                     ->where('quize_id','=',$this->id)
                     ->orderBy('subject_id')
                     ->inRandomOrder()
                     ->get();
    }*/

    /*
     * This method save the quiz questions for resume exam
     * @return [type] [description]
     */
    public function saveQuizQuestions($questions, $subjects = '')
    {

        $record                        = new QuizQuestions();
        $record->quiz_id               = $this->id;
        $record->student_id            = Auth::user()->id;
        $record->questions_data        = json_encode($questions);
        $record->save();
    }


    /**
     * This Method get all section questions
     * @param  [type] $section_data [description]
     * @return [type]               [description]
     */
    public function getSectionQuestions($section_data)
    {
        $all_questions  = array();

        foreach ($section_data as $sec_data) {

            $sec_questions   = $sec_data->questions;

            foreach ($sec_questions as $key => $value) {

                $question  = QuestionBank::where('id', $value)->first();
                $all_questions[]   = $question;
            }
        }

        return $all_questions;
    }


    public function prepareQuestions($questions, $type = '')
    {
        $final_questions = array();
        $final_subjects = array();

        foreach ($questions as $r) {
            // echo $r->questionbank_id . '_____<br/>';
            $temp_question = array();
            $temp_subject = array();
            // $temp_question       = QuestionBank::find($r->questionbank_id);
            // $temp_question  = QuestionBank::where('id',$r->questionbank_id)->first();
            // $temp_question  = DB::table()('id',$r->questionbank_id)->first();
            $temp_question = DB::table('questionbank')
                ->join('topics as topics_child', 'topics_child.id', '=', 'questionbank.topic_id')
                ->leftjoin('topics as topics_parent', 'topics_child.parent_id', '=', 'topics_parent.id')

                ->join('subjects', 'subjects.id', '=', 'questionbank.subject_id')

                ->select('questionbank.*', 'topics_child.topic_name', 'topics_child.description as topics_child_description', 'topics_parent.description as topics_parent_description', 'subjects.subject_title', 'topics_parent.parent_id as topics_parent_status', 'topics_child.parent_id as topics_child_status')
                ->where('questionbank.id', $r->questionbank_id)
                ->first();

            $temp_subject        = Subject::find($r->subject_id);
            // var_dump($temp_subject);


            array_push($final_questions, $temp_question);
            if (!$this->compareSubjects($final_subjects, $temp_subject))
                array_push($final_subjects, $temp_subject);

            // $final_subjects->unique();
        }

        if ($type == '') {
            // $this->saveQuizQuestions($final_questions);
        }

        return  array(
            'questions' => $final_questions,
            'subjects' => $final_subjects
        );
    }

    /**
     * Compares the subjects and returns the is unique or not
     * @param  [type] $final_subjects [array]
     * @param  [type] $temp_subject   [array]
     * @return [type]                 [boolen]
     */
    public function compareSubjects($final_subjects, $temp_subject)
    {
        $flag = 0;
        foreach ($final_subjects as $sub) {
            if ($sub->id == $temp_subject->id) {
                $flag = 1;
                break;
            }
        }
        return $flag;
    }

    public function language()
    {
        $language = '';
        $language = \App\ExamLanguage::where('id', '=', 2)->first();
        return $language;
    }
}
