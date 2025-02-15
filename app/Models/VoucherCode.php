<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class VoucherCode extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'code'];

    /**
     * Voucher Code is a Random 5 Alphanumeric Character that are unique
     * 
     * @return string
     */
    public function generateUniqueCode(): String
    {
        # if code doesn't exist yet then create a new one
        do {
            $code = Str::random(5);
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
