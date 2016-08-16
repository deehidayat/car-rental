<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public $table = 'clients';

    protected $fillable = [
        'name', 'gender'
    ];

    public $rules = [
        'name' => 'required',
        'gender' => 'required|in:male,female'
    ];

    public $timestamps = false;
}
