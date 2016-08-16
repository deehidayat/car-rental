<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as APIBaseController;

use App\Model\Car;

use Request;

class CarController extends APIBaseController
{
    public function model()
    {
        return Car::class;
    }

    protected function getCreateRules() {
        /**
         * Add dynamic year rule
         */
        $rules = $this->model->rules;
        $rules['year'] .= '|max:' . date('Y');
        return $rules;
    }
    
    protected function getUpdateRules($id) {
        $rules = $this->getCreateRules();
        $rules['plate'] = 'required|unique:cars,plate,' . $id;
        return $rules;
    }
}
