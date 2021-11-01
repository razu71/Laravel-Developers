<?php

namespace App\Services\User;

use App\Contracts\Repositories\User\UserRepoContracts;
use App\Contracts\Services\User\UserServiceContracts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserServices implements UserServiceContracts {

    private $user_repo;

    public function __construct(UserRepoContracts $user_repo_contracts) {
        $this->user_repo = $user_repo_contracts;
    }

    public function sendEmailProcess($template, $data, $to, $subject) {
        $mail_sender = env('MAIL_SENDER') ?? 'noreply@email.com';
        $mail_from_name = env('MAIL_FROM_NAME') ?? 'Lara-Auth';

        Mail::send($template, $data, function ($message) use ($to, $subject, $mail_sender, $mail_from_name) {
            $message->to($to)->subject($subject);
            $message->from($mail_sender, $mail_from_name);
        });
    }

    public function sendEmail(Request $request) {
        try {
            $to = $request->to;
            $subject = $request->subject;
            $data = ['link' => $request->link];
            $template = 'create-account';
            $this->sendEmailProcess($template, $data, $to, $subject);

            return sendSuccessResponse('', 'Mail sent successfully');
        } catch (\Exception $e) {
            return sendResponseError($e->getCode(), '', $e->getMessage());
        }
    }

    public function sendCodeInEmail($user) {
        $to = $user->email;
        $subject = 'Verify account';
        $code = random_number(6);
        $this->user_repo->update(['id' => $user->id], ['verification_pin' => $code]);
        $data = ['code' => $code];
        $template = 'verification-code';
        $this->sendEmailProcess($template, $data, $to, $subject);
    }

    public function signIn($request) {
        try {
            $user = $this->user_repo->getUser(['email' => $request->email]);

            if (!empty($user)) {
                if ($user->user_role == USER && $user->registered_at == NULL) {
                    $this->sendCodeInEmail($user);
                    return sendResponseError('', 'User not verified, check your email');
                }
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    $token = $user->createToken($user->email)->accessToken;
                    $data = ['users' => $user, 'access_token_type' => 'Bearer', 'access_token' => $token];
                    return sendSuccessResponse($data, 'Logged in successfully');
                } else {
                    return sendResponseError('', 'Password or username not matched');
                }
            } else {
                return sendResponseError('', 'User not found');
            }
        } catch (\Exception $e) {
            return sendResponseError($e->getCode(), $e->getMessage());
        }
    }

    public function makeData($request) {
        return [
            'user_name' => $request->user_name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'user_role' => USER,
        ];
    }

    public function createAccount($request) {
        try {
            DB::beginTransaction();
            $data = $this->makeData($request);
            $user = $this->user_repo->create($data);
            $this->sendCodeInEmail($user);
            DB::commit();
            return sendSuccessResponse($user, 'A code has been sent to your email');
        } catch (\Exception $e) {
            DB::rollBack();
            return sendResponseError($e->getCode(), $e->getMessage());
        }
    }

    public function verifyPin($request) {
        $user = $this->user_repo->getUser(['email' => $request->email, 'verification_pin' => $request->verification_pin]);
        if (!empty($user)) {
            $this->user_repo->update(['id', $user->id], ['verification_pin' => NULL, 'registered_at' => now()]);
            return sendSuccessResponse($user, 'Registered successfully, login now');
        } else {
            return sendResponseError('404', 'Verification code or email not matched');
        }
    }

    public function updateProfile($request) {
        try {
            $data = $this->makeData($request);
            $user = $this->user_repo->getUser(['email' => $request->email]);

            if ($request->has('avatar')) {
                $old_image = $user->getAttributes()['avatar'] ?? '';
                $data['avatar'] = fileUpload($request->avatar, AVATAR_PATH, $old_image, 256, 256);
            }
            $this->user_repo->update(['id' => $user->id], $data);
            return sendSuccessResponse($user, 'User profile updated successfully');
        } catch (\Exception $e) {
            return sendResponseError($e->getCode(), $e->getMessage());
        }
    }
}
