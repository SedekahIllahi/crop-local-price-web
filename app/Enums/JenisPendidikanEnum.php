<?php

/* 
 * ==========================================
 * THIS FILE IS GUIDE FOR LARAVEL ENUM USAGE
 * ==========================================
 */


namespace App\Enums;

enum JenisPendidikanEnum: string
{
    case TK = 'TK';
    case SD = 'SD';
    case SMP = 'SMP';
    case SMA = 'SMA';
    case D3 = 'D3';
    case S1 = 'S1';
    case S2 = 'S2';
    case S3 = 'S3';

    public function label(): string
    {
        return match($this) {
            JenisPendidikanEnum::TK => 'Taman Kanak-kanak',
            JenisPendidikanEnum::SD => 'Sekolah Dasar',
            JenisPendidikanEnum::SMP => 'SLTP Sederajat',
            JenisPendidikanEnum::SMA => 'SLTA Sederajat',
            JenisPendidikanEnum::D3 => 'Diploma 3',
            JenisPendidikanEnum::S1 => 'Strata 1',
            JenisPendidikanEnum::S2 => 'Strata 2',
            JenisPendidikanEnum::S3 => 'Strata 3',
        };
    }
}


/* 
 * ==========================================
 * HOW TO USING LARAVEL ENUM 
 * ==========================================
 */

/* 1. In Migration File */
// $table->enum('pendidikan', array_column(\App\Enums\JenisPendidikanEnum::cases(), 'value'))->nullable();

/* 2. In Model File */
// use App\Enums\JenisPendidikanEnum;
// protected $casts = [
//     'pendidikan' => JenisPendidikanEnum::class,
// ];

/* 3. In Form Select Option */
// @foreach(\App\Enums\JenisPendidikanEnum::cases() as $pendidikan)
//     <option value="{{ $pendidikan->value }}">{{ $pendidikan->label() }}</option>
// @endforeach

/* 4. In Usage */
// $userProfile->pendidikan = JenisPendidikanEnum::S1;
// $userProfile->save();

/* 5. In Display Data */
// echo $userProfile->pendidikan->label(); // Output: Strata 1

/* 6. GET Label by Value */
// $pendidikanLabel = JenisPendidikanEnum::tryFrom($value)->label(); // Output: Strata 1

/* 7. GET Label by Constant Varriable */
// $pendidikanLabel = JenisPendidikanEnum::S1->label(); // Output: Strata 1

/* 8. GET Value by Constant Variable */
// $pendidikanValue = JenisPendidikanEnum::S1->value; // Output: 'S1'
