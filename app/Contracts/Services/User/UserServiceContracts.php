<?php

namespace App\Contracts\Services\User;

use Illuminate\Http\Request;

interface UserServiceContracts {
    public function sendEmailProcess($template, $data, $to, $subject);

    public function sendEmail(Request $request);

    public function sendCodeInEmail($user);

    public function signIn($request);

    public function createAccount($request);

    public function verifyPin($request);

    public function updateProfile($request);
}
