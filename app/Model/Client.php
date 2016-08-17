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

    public function rentals() {
        return $this->hasMany('App\Model\Rental', 'client_id', 'id');
    }
}
