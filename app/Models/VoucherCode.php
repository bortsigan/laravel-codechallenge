<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class VoucherCode extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'code'];

    public function generateUniqueCode()
    {
        # check if the code exist and if not? create the code
        do {
            $code = Str::random(5);
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
