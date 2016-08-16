<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as APIBaseController;

use App\Model\Car;
use App\Model\Rental;

use Request;
use Exception;

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

    public function histories($id) {
        $input = Request::all();
        if (isset($input['month'])) {
            $date = explode('-', $input['month']);
        }
        try {
            $record = $this->model->findOrFail($id);
        } catch (Exception $e) {
            return $this->response(['id' => $id, 'message' => 'Record not found'], 400);
        }
        $result = $record->toArray();
        $result['histories'] = [];
        if (isset($date)) {
            $rentals = $record->getOnMonth((int) $date[0], (int) $date[1]);
        } else {
            $rentals = $record->rentals;
        }
        foreach ($rentals as $key => $rental) {
            $result['histories'][] = [
                'rent-by'=> $rental->client->name,
                'date-from'=> $rental->date_from->format('Y-m-d'),
                'date-to'=> $rental->date_to->format('Y-m-d')
            ];
        }
        return $this->response($result);
    }
}
