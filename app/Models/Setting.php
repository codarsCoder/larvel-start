<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $fillable = [
        'site_name',
        'backend_url',
        'frontend_url',
        'footer_description',
        'logo',
        'dark_logo',
        'logo_footer',
        'logo_mail',
        'favicon',
        'meta_keywords',
        'meta_description',
    ];

}
