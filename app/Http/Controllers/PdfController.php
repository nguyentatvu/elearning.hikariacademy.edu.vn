<?php
namespace App\Http\Controllers;
use App\Quiz;
use App\User;
use Auth;
use DB;
use \App;
use PDF;
use File;
class PdfController extends Controller
{
    public function __construct()
    {
    }
    public function index($slug)
    {

        $bodethi = DB::table('examseries')->where('slug',$slug)->first();
        $examseries_data = DB::table('examseries_data')
            ->where('examseries_id', $bodethi->id)
            ->get();
        //dd($examseries_data);

        $path = public_path("pdf/").$bodethi->id;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            mkdir($path.'/audio', 0777, true);
        }

        $index = 1;    
        foreach ($examseries_data as $key_examseries => $examseries) {
            $quiz = Quiz::getRecordWithID($examseries->quiz_id);
            $any_resume_exam     = FALSE;
            $current_state       = null;
            $current_question_id = null;
            $data['time_hours']         = '';
            if (!$any_resume_exam) {
                $prepared_records = (object) $quiz->prepareQuestions($quiz->getQuestions());
            }
            if ($current_state) {
                $temp = [];
                foreach ($current_state as $key => $val) {
                    $temp[(int) $key] = $val;
                }
                $current_state = $temp;
            }
            $data['quiz']                = $quiz;
            $data['active_class']        = 'exams';
            $data['title']               = change_furigana_title($quiz->title);
            $data['right_bar']           = true;
            $data['block_navigation']    = true;
            $final_questions             = $prepared_records->questions;
            $final_subjects              = $prepared_records->subjects;
            $data['questions']           = $final_questions;
            $data['subjects']            = $final_subjects;

            if($quiz->type == 1) {
                foreach ($final_questions as $key_question =>$value_question){
                    $namefile = (int)$key_question + 1;
                    if (!empty($value_question->question_file)) {
                        copy(base_path().$value_question->question_file,$path.'/audio/'.$namefile.'.mp3');
                    }
                }
            } 

            $view_name = 'admin.pdf.pdf';
            $data['data'] = $data;
            $view = 1;
            if ($view == 1) {
                $pdf = PDF::loadView($view_name , $data);
                $fileName = $path.'/dethi' . $index.'.pdf' ;
                //$pdf->save($fileName);
                //return $pdf->stream();
                // return $pdf->download('test.pdf');
            }

            $index++;

        }
  
       /* $filename = 'de_thi'.'_'.date("d-m-y").".zip";
        $zip = new \ZipArchive ();
        if ($zip->open ($filename ,\ZipArchive::OVERWRITE) !== true) {
            //The OVERWRITE parameter will overwrite the file of the archive. The file must already exist.
            if($zip->open ($filename ,\ZipArchive::CREATE) !== true){
                // A new file is generated if the file does not exist. Opening the file with CREATE appends the content to the zip
                Exit ( 'Unable to open file, or file creation failed');
            }
        }*/
        $zipname = to_slug($bodethi->title) . '.zip';
        $path_file_zip = public_path("pdf/").'/'.$zipname;
       $this-> Zip($path, $path_file_zip);

       // http headers for zip downloads
      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: public");
      header("Content-Description: File Transfer");
      header("Content-type: application/octet-stream");
      header("Content-Disposition: attachment; filename=\"".$zipname."\"");
      header("Content-Transfer-Encoding: binary");
      header("Content-Length: ".filesize($path_file_zip));
      ob_end_flush();
      @readfile($path_file_zip);

     echo 'Download file thành công'; exit;
        //return view($view_name, $data);
    }
    public function getDethi($id_dethi)
    {
        echo $id_dethi;
    }
    function Zip($source, $destination){
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }
        $zip = new \ZipArchive();
        /*if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }*/
        if ($zip->open ($destination ,\ZipArchive::OVERWRITE) !== true) {
            //The OVERWRITE parameter will overwrite the file of the archive. The file must already exist.
            if($zip->open ($destination ,\ZipArchive::CREATE) !== true){
                // A new file is generated if the file does not exist. Opening the file with CREATE appends the content to the zip
                Exit ( 'Unable to open file, or file creation failed');
            }
        }
        $source = str_replace('\\', '/', realpath($source));
        if (is_dir($source) === true){
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);
            foreach ($files as $file){
                $file = str_replace('\\', '/', $file);
                if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                    continue;
                $file = realpath($file);
               // dd($file);
                if (is_dir($file) === true){
                   // dd(1);
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                }else if (is_file($file) === true){
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                    //$zip->addFile($file);
                }
            }
        }else if (is_file($source) === true){
            $zip->addFromString(basename($source), file_get_contents($source));
        }
        return $zip->close();
        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename='.basename($destination));
    }
}
