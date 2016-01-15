<?php namespace App\Services;

class Email {

    /**
     * Подтверждение емейла после регистрации
     *
     * @param        $email
     * @param        $code
     * @param string $name
     */
    public static function sendConfirmation($email, $code, $name = ''){
        \Mail::send('emails.confirm', compact('email', 'code') , function($message) use ($email, $name){
            $message->to($email, $name)->subject('Регистрация на сайте');
        });
    }

    public static function sendRecovery($email, $code){
        \Mail::send('emails.recovery', compact('email', 'code') , function($message) use ($email){
            $message->to($email)->subject('Восстановление пароля');
        });
    }
}
