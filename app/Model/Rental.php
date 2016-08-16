<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    public $table = 'rentals';

    protected $dates = ['date_from', 'date_to'];

    protected $fillable = [
        'client_id', 'car_id', 'date_from', 'date_to'
    ];

    public $rules = [
        'client_id' => 'integer|required|exists:clients,id',
        'car_id' => 'integer|required|exists:cars,id',
        'date_from' => 'date|required',
        'date_to' => 'date|required'
    ];

    public $timestamps = false;

    protected $hidden = ['client_id', 'car_id'];

    // protected $with = ['client', 'car'];

    public function toArray() {
        $array = parent::toArray();
        $array['name'] = $this->client->name;
        $array['brand'] = $this->car->brand;
        $array['type'] = $this->car->type;
        $array['plate'] = $this->car->plate;
        return $array;
    }

    public function client() {
        return $this->belongsTo('App\Model\Client', 'client_id', 'id');
    }

    public function car() {
        return $this->belongsTo('App\Model\Car', 'car_id', 'id');
    }

}
