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

    public function free() {
        $input = Request::all();
        $date = isset($input['date']) ? strtotime($input['date']) : null;
        if (!isset($date)) {
            return $this->response(['message' => 'Date required'], 400);
        }
        /**
         * Validasi tanggal hari ini
         */
        $now = strtotime(date('Y-m-d', time()));
        if (date('U', $date) < date('U', $now)) {
            return $this->response(['message' => 'Date must be greater than or equal to ' . date('d-m-Y', $now)], 400);
        }
        $bookedCars = Car::select('cars.id', 'cars.brand', 'cars.type', 'cars.plate', 'rentals.date_from', 'rentals.date_to')
                    ->join('rentals', 'cars.id', '=', 'rentals.car_id')
                    ->where('date_from', '<=', date('Y-m-d', $date))
                    ->where('date_to', '>=', date('Y-m-d', $date))
                    ->groupBy('car_id')
                    ->get()->pluck('id');
        $freeCars = Car::select('id', 'brand', 'type', 'plate')
                    ->whereNotIn('id', $bookedCars)
                    ->get();
        return $this->response([
            'date' => date('d-m-Y', $date),
            'free_cars' => $freeCars
        ]);
    }
}
