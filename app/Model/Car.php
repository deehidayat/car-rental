<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    public $table = 'cars';

    protected $fillable = [
        'brand', 'type', 'year', 'color', 'plate'
    ];

    protected $hidden = [];

    public $rules = [
        'brand' => 'required',
        'type' => 'required',
        'year' => 'required|integer|digits:4',
        'color' => 'required',
        'plate' => 'required|unique:cars',
    ];

    public $timestamps = false;

    public function rentals()
    {
        return $this->hasMany('App\Model\Rental', 'car_id', 'id');
    }

    public function getOnMonth($month, $year) {
        $startDate = mktime(0, 0, 0, $month, 1, $year);
        $endDate = strtotime("-1 days", strtotime("+1 months", $startDate));
        return $this
                ->rentals()
                ->where(function($query) use ($startDate, $endDate) {
                    $query
                    ->whereBetween('date_from', [date('Y-m-d', $startDate), date('Y-m-d', $endDate)])
                    ->orWhereBetween('date_to', [date('Y-m-d', $startDate), date('Y-m-d', $endDate)]);
                })
                ->get();
    }
}
