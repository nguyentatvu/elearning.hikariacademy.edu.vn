<?php

namespace App;

class EmailConfig
{

    protected $old_email_config;

    //Overwrite current mail settings. Created by NTV@20211028
    public function setSpamMailConfig()
    {
        $existing = config('mail');
        $this->old_email_config = $existing;
        $new = array_merge(
            $existing,
            [
                'host' => env('SPAM_MAIL_HOST'),
                'port' => env('SPAM_MAIL_PORT'),
                'from' => [
                    'address' => env('SPAM_MAIL_FROM_ADDRESS'),
                    'name' => env('SPAM_MAIL_SENDER_NAME'),
                ],
                'encryption' => env('SPAM_MAIL_ENCRYPTION'),
                'username' => env('SPAM_MAIL_USERNAME'),
                'password' => env('SPAM_MAIL_PASSWORD'),
            ]
        );

        config(['mail' => $new]);
    }

    //Restore current mail settings. Created by NTV@20211028
    public function restoreMailConfig()
    {
        if (isset($this->old_email_config)) {
            config(['mail' => $this->old_email_config]);
        }
    }
}
