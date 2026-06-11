<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model PhoneVerification ini menghubungkan ke tabel 'phone_verifications' di database Supabase untuk data kode verifikasi OTP.
class PhoneVerification extends Model
{
    protected $guarded = [];
}
