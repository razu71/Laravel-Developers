<?php

namespace App\Http\Controllers;

use App\Contracts\Services\User\UserServiceContracts;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;

class AuthController extends Controller {
    private $user_service;

    public function __construct(UserServiceContracts $user_service_contracts) {
        $this->user_service = $user_service_contracts;
    }

    protected function sendEmailProcess($template, $data, $to, $subject) {
        $this->user_service->sendEmailProcess($template, $data, $to, $subject);
    }

    /**
     * @param Request $request
     * send invitation link to create account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail(Request $request) {
        return $this->user_service->sendEmail($request);
    }

    /**
     * @param $user
     */
    protected function sendCodeInEmail($user) {
        $this->user_service->sendCodeInEmail($user);
    }

    /**
     * @param Request $request
     * sign in
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signIn(SignInRequest $request) {
        return $this->user_service->signIn($request);
    }

    /**
     * @param CreateAccountRequest $request
     * create account
     *
     * @return mixed
     */
    public function createAccount(CreateAccountRequest $request) {
        return $this->user_service->createAccount($request);
    }

    /**
     * @param Request $request
     * verify pin
     *
     * @return mixed
     */
    public function verifyPin(Request $request) {
        return $this->user_service->verifyPin($request);
    }

    /**
     * @param UpdateProfileRequest $request
     * update user profile
     *
     * @return mixed
     */
    public function updateProfile(UpdateProfileRequest $request) {
        return $this->user_service->updateProfile($request);
    }

}
