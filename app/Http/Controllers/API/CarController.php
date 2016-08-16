<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as APIBaseController;

use App\Model\Car;

class CarController extends APIBaseController
{
    public function model()
    {
        return Car::class;
    }
}
