<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    public $table = 'cars';

    protected $fillable = [
        'brand', 'type', 'year', 'color', 'plate'
    ];

    public $rules = [
        'brand' => 'required',
        'type' => 'required',
        'year' => "required|digits:4",
        'color' => 'required',
        'plate' => 'required|unique:cars',
    ];

    public $timestamps = false;
}
