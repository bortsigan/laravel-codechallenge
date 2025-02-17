<?php

namespace App\Services;

use App\Models\User;
use App\Models\VoucherCode;
use Illuminate\Contracts\Auth\Authenticatable;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class VoucherCodeService 
{
    private VoucherCode $voucherCode;

    public function __construct(VoucherCode $voucherCode)
    {
        $this->voucherCode = $voucherCode;
    }

    public function generateUniqueCode(Authenticatable $user): ?VoucherCode
    {
        if (!($user instanceof User)) {
            abort(401, 'Unauthorized');
        }

        $count = $user->vouchers()->count();

        if ($count >= 5) {
            return null;
        }

        try {
            DB::beginTransaction();

            $voucherCode = $user->vouchers()->create([
                'code' => $this->voucherCode->generateUniqueCode()
            ]);

            DB::commit();

            return $voucherCode;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function getUserVouchers(Authenticatable $user): ?Collection
    {
        if (!($user instanceof User)) {
            abort(401, 'Unauthorized');
        }

        return $this->voucherCode
                    ->select(['id', 'code', 'created_at'])
                    ->where('user_id',$user->id)
                    ->get();
    }

    public function deleteUserVoucher(Authenticatable $user, VoucherCode $voucherCode): bool   
    {
        if (!($user instanceof User)) {
            abort(401, 'Unauthorized');
        }
        
        try {
            DB::beginTransaction();

            $this->voucherCode
                        ->where('user_id', $user->id)
                        ->where('id',$voucherCode->id)
                        ->delete();

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }


}


# END OF PHP FILE