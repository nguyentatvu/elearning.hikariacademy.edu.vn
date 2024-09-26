<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Logger;
use App\EmailConfig;
use App\ExamSeriesfree;

class everyMinute extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'minute:update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description minuteUpdate';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	//Re-write all by NTV@20211003
	public function handle()
	{
		if (!env('MAIL_SPAM')) return;

		$log = new Logger(env('MAIL_LOG_PATH'));

		// set account spam mail
		$emailConfig = new EmailConfig();
		$emailConfig->setSpamMailConfig();
		$log->putLog('Ready to get data send mail action handle');

		// Get list class test exam
		$examList =
			DB::select('select id, name, start_date, end_date from exam_free where CAST(start_date as DATE) = DATE_ADD(CURDATE(), INTERVAL 1 DAY) order by id asc');

		// Loop list class test exam, get users for test exam
		try {
			foreach ($examList as $exam) {
				// Get user list
				$users = DB::table('users')
					->select(['id', 'name', 'email', 'sendmail_free'])
					->where('is_register', '=', 1)
					->where('sendmail_free', '=', $exam->id)
					->orderBy('id', 'desc')
					->get();
				foreach ($users as $user) {
					$start_date = strtotime($exam->start_date);
					$end_date = strtotime($exam->end_date);
					$s_start_date = $this->getVietnameseDayName(date('w', $start_date)) . ' ' . date('d-m-Y H:i', $start_date);
					$s_end_date = $this->getVietnameseDayName(date('w', $end_date)) . ' ' . date('d-m-Y H:i', $end_date);

					// Send mail to user
					// sendEmail('thongbaothithu', array('name'=>$user->name, 'start_date'=>$s_start_date, 'end_date'=>$s_end_date, 'to_email' =>$user->email));
                    sendEmail('thongbaothithu', array('name'=>$user->name, 'start_date'=>$s_start_date, 'end_date'=>$s_end_date, 'to_email' =>'vu.nguyentat@gmail.com'));
					$log->putLog('Sent to ' . $user->email . ' OK');
					// Update user info is sent email
					DB::table('users')
						->where('id', $user->id)
						->update(['sendmail_free' => 0, 'last_send_status' => 1, 'sendmail_time' => date('Y-m-d H:i:s')]);
					usleep(6000000); // wait for 6 seconds
				}
			}
		} catch (\Exception $e) {
			DB::table('users')
				->where('id', $user->id)
				->update(['sendmail_free' => 0, 'last_send_status' => 0]);
			$log->putLog('An error occurred: ' . $e->getMessage());
		}
		$emailConfig->restoreMailConfig();
	}

	/**
	 * Send mail by user
	 * @param email email of user
	 * @param slug id of test exam
	 */
	public function sendMail($email, $slug)
	{
		if (!env('MAIL_SPAM')) return;

		$log = new Logger(env('MAIL_LOG_PATH'));
		$log->putLog('Ready to get data send mail action sendMail');

		// set account spam mail
		$emailConfig = new EmailConfig();
		$emailConfig->setSpamMailConfig();

		$user = DB::table('users')
			->select(['id', 'name', 'email', 'sendmail_free'])
			->where('sendmail_free', '=', (int)$slug)
			->where('email', '=', $email)
			->first();
		if ($user != null) {
			try {
				$exams_series_free = DB::table('exam_free')
					->where('id', '=', $user->sendmail_free)
					->first();
				$start_date = strtotime($exams_series_free->start_date);
				$end_date = strtotime($exams_series_free->end_date);
				$s_start_date = $this->getVietnameseDayName(date('w', $start_date)) . ' ' . date('d-m-Y H:i', $start_date);
				$s_end_date = $this->getVietnameseDayName(date('w', $end_date)) . ' ' . date('d-m-Y H:i', $end_date);

				// sendEmail('thongbaothithu', array('name'=>$data->name, 'start_date'=>$s_start_date, 'end_date'=>$s_end_date, 'to_email' =>$email));
                sendEmail('thongbaothithu', array('name'=>$user->name, 'start_date'=>$s_start_date, 'end_date'=>$s_end_date, 'to_email' =>'vu.nguyentat@gmail.com'));
				$log->putLog('Sent to ' . $user->email . ' OK');

				DB::table('users')
					->where('id', $user->id)
					->update(['sendmail_free' => 0, 'last_send_status' => 1, 'sendmail_time' => date('Y-m-d H:i:s')]);
			} catch (\Exception $e) {
				$result['status'] = 500;
				$result['message'] = $e->getMessage();
				return $result;

				DB::table('users')
					->where('id', $user->id)
					->update(['sendmail_free' => (int)$slug, 'last_send_status' => 0]);
				$log->putLog('An error occurred: ' . $e->getMessage());
			}
		}
		// Reset account spam mail
		$emailConfig->restoreMailConfig();
		$result['status'] = 200;
		$result['message'] = 'Gửi Email thành công';
		return $result;
	}

	public function sendMailAdmin($slug)
	{
		// Get email admin
		$email = env('ADMIN_EMAIL', '');
		$name = env('ADMIN_NAME', '');
		$log = new Logger(env('MAIL_LOG_PATH'));
		$log->putLog('Ready to get data send mail action sendMailAdmin');
		$record = ExamSeriesfree::select('name')->where('id', '=', (int)$slug)->first();

		try {
			sendEmail('sendmailadmin', array('name' => $name, 'slug' => $record->name, 'to_email' => $email));

			$log->putLog('Sent to ' . $email . ' OK');
		} catch (\Exception $e) {
			$result['status'] = 500;
			$result['message'] = $e->getMessage();
			return $result;
			$log->putLog('An error occurred: ' . $e->getMessage());
		}

		$result['status'] = 200;
		$result['message'] = 'Gửi Email thành công';
		return $result;
	}

	private function getVietnameseDayName($dayOfWeek)
	{

		switch ($dayOfWeek) {
			case 6:
				return 'T7';
			case 0:
				return 'CN';
			case 1:
				return 'T2';
			case 2:
				return 'T3';
			case 3:
				return 'T4';
			case 4:
				return 'T5';
			case 5:
				return 'T6';
			default:
				return '';
		}
	}

	//PHP rand() function does not generate random number each separated-called time so we need create own random number generation function
	private function microtimeRand($min, $max)
	{
		$microtimeInt = intval(microtime(true) * 100);
		$microtimeInt = $microtimeInt % ($max - $min);
		$microtimeInt += $min;

		return $microtimeInt;
	}

	//Overwrite mail settings
	private function setMailConfig(Host $host)
	{
		$existing = config('mail');
		$new = array_merge(
			$existing,
			[
				'host' => $host->host,
				'port' => $host->port,
				'from' => [
					'address' => $host->fromAddress,
					'name' => $host->fromName,
				],
				'encryption' => $host->encryption,
				'username' => $host->username,
				'password' => $host->password,
			]
		);

		config(['mail' => $new]);
	}
}
