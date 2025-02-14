<?php

namespace App\Services;

use App\Models\User;
use App\Models\VoucherCode;
use App\Mail\WelcomeEmail;

use Hash;
use Mail;
use DB;
use Exception;

class UserService 
{
    private User $user;
    private VoucherCode $voucherCode;

    public function __construct(User $user, VoucherCode $voucherCode)
    {
        $this->user = $user;
        $this->voucherCode = $voucherCode;
    }

    public function registerUser(Array $request): User|Exception
    {
        try {
            DB::beginTransaction();

            $user = $this->user->create([
                'username' => $request['username'],
                'first_name' => $request['first_name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);

            # Generate a voucher code for the new user 
            $voucher = $user->vouchers()->create([
                'code' => $this->voucherCode->generateUniqueCode()
            ]);

            # Send welcome email
            Mail::to($user->email)->send(new WelcomeEmail($user, $voucher));

            DB::commit();

            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}