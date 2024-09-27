<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Filesystem\Filesystem;
use Exception;
use File;

class MaintenanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


 	//Remove Unused Content Files
    public function removeUnusedContentFiles()
    {

        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        try {

			//Get all content file paths from lmscontents
			$lmcontent_file_paths = DB::table('lmscontents')
					->select('file_path')
					->whereNotNull('file_path')
					->get()->pluck('file_path')->toArray();
			//Got file path like this: /public/uploads/lms/content/25902543-1/video.m3u8
			for ($i=0; $i < count($lmcontent_file_paths); $i++){
				$file_path = explode("/", $lmcontent_file_paths[$i])[5];
				$lmcontent_file_paths[$i]= $file_path;
			}
			//dd($lmcontent_file_paths[0]);

			//Get all sub directories from content directory
			$content_path = public_path() . '/uploads/lms/content/';
			$content_sub_directories = glob($content_path . '*' , GLOB_ONLYDIR);
			//Got sub directory like this: /home/hael/domains/elearning.hikariacademy.edu.vn/public_html/public/uploads/lms/content/1013764594-16123
			for ($i=0; $i < count($content_sub_directories); $i++){
				$sub_directory = $content_sub_directories[$i];
				$pos = strrpos($sub_directory, '/', -1);
				$content_sub_directories[$i]= substr($sub_directory, $pos+1);
			}
            //dd($content_sub_directories[0]);

			//Delete unused directories
			$remove_count=0;
			foreach($content_sub_directories as $directory_path){
				if (!in_array($directory_path, $lmcontent_file_paths)) {
					$remove_count++;
					$path = $content_path.$directory_path;				
					if (\File::exists($path)) {
						\File::deleteDirectory($path);
						echo $path."<br>";
					}
				}
			}			
			
			if ($remove_count==0){
				echo "Nothing to remove!";
				$response['status']  = 1;
				$response['message'] = "Không có thư mục content không sử dụng nào!";
			
			}else{
				$response['status']  = 1;
				$response['message'] = "Đã xóa $remove_count thư mục content không sử dụng thành công";
			}

        } catch (\Exception $e) {
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
          }

        return json_encode($response);
    }

 	//Remove 1/2 Content Files
    public function removeHalfContentFiles()
    {

        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        try {

			//Get all content file paths from lmscontents
			$lmcontent_file_paths = DB::table('lmscontents')
					->select('file_path')
					->whereNotNull('file_path')
					->orderBy('lmsseries_id', 'DESC')
					->orderBy('id', 'DESC')
					->get()->pluck('file_path')->toArray();
			//Got file path like this: /public/uploads/lms/content/25902543-1/video.m3u8
			for ($i=0; $i < count($lmcontent_file_paths); $i++){
				$file_path = explode("/", $lmcontent_file_paths[$i])[5];
				$lmcontent_file_paths[$i]= $file_path;
			}
			//dd($lmcontent_file_paths[0]);

			//Delete 1/2 directories
			$remove_count=0;
			for ($i=0; $i < count($lmcontent_file_paths)/2; $i++){
				$path = public_path() . '/uploads/lms/content/'. $lmcontent_file_paths[$i].'/';	
				if (\File::exists($path)) {
					$remove_count++;
					\File::deleteDirectory($path);
					echo $path."<br>";
				}
				
			}	
			
			if ($remove_count==0){
				echo "Nothing to remove!";
				$response['status']  = 1;
				$response['message'] = "Không có thư mục content nào!";
			
			}else{
				$response['status']  = 1;
				$response['message'] = "Đã xóa $remove_count thư mục content thành công";
			}

        } catch (\Exception $e) {
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
          }

        return json_encode($response);
    }

 	//Remove Spame Accounts
    public function removeSpamAccounts()
    {

        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

	/*
		DELETE FROM `users` WHERE country_code not in ("US", "SG", "JP", "HK", "VN", "0")
		DELETE FROM `users` WHERE country_code ="US" AND address <> "1";
	*/
		return;
    }

}
