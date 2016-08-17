<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as APIBaseController;

use App\Model\Rental;
use App\Model\Client;
use App\Model\Car;

use Illuminate\Http\Request;
use Validator;

class RentalController extends APIBaseController
{
    public function model()
    {
        return Rental::class;
    }

    public function store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, $this->getCreateRules());
        if ($validator->fails()) {
            return $this->response($validator->messages(), 400);
        }
        /**
         * Validasi Client
         */
        $records = $this->model
            ->where('client_id', '=', $input['client_id'])
            // Validasi range tanggal
            ->where(function($query) use ($input) {
                $query
                ->where(function($query2) use ($input) {
                    $query2
                    ->where('date_from', '>=', $input['date_from'])
                    ->where('date_from', '<=', $input['date_to']);
                })
                ->orWhere(function($query2) use ($input) {
                    $query2
                    ->where('date_from', '<=', $input['date_to'])
                    ->where('date_to', '>=', $input['date_to']);
                });
            })
            ->get();
        if ($records->count() > 0) {
            return $this->response([
                'Client' => [sprintf('Client have a booking at %s to %s for car %s', 
                    $records[0]->date_from->format('d-m-Y'), 
                    $records[0]->date_to->format('d-m-Y'), 
                    $records[0]->car->plate
                )]
            ], 400);
        }
        /**
         * Validasi Car
         */
        $records = $this->model
            ->where('car_id', '=', $input['car_id'])
            // Validasi range tanggal
            ->where(function($query) use ($input) {
                $query
                ->where(function($query2) use ($input) {
                    $query2
                    ->where('date_from', '>=', $input['date_from'])
                    ->where('date_from', '<=', $input['date_to']);
                })
                ->orWhere(function($query2) use ($input) {
                    $query2
                    ->where('date_from', '<=', $input['date_to'])
                    ->where('date_to', '>=', $input['date_to']);
                });
            })
            ->get();
        if ($records->count() > 0) {
            return $this->response([
                'Car' => [sprintf('Car has been booked at %s to %s by %s', 
                    $records[0]->date_from->format('d-m-Y'),
                    $records[0]->date_to->format('d-m-Y'),
                    $records[0]->client->name
                )]
            ], 400);
        }
        /**
         * Send data
         */
        $record = $this->model->create($input);
        return $this->response(['id' => $record->id]);
    }

    public function update($id, Request $request){
        $input = $request->all();
        try {
            $record = $this->model->findOrFail($id);
        } catch (Exception $e) {
            return $this->response(['id' => $id, 'message' => 'Record not found'], 400);
        }
        $validator = Validator::make($input, $this->getUpdateRules($id));
        if ($validator->fails()) {
            return $this->response($validator->messages(), 400);
        }
        /**
         * Validasi Client
         */
        $records = $this->model
            ->where('client_id', '=', $input['client_id'])
            ->where('id', '!=', $id)
            // Validasi range tanggal
            ->where(function($query) use ($input) {
                $query
                ->where(function($query2) use ($input) {
                    $query2
                    ->where('date_from', '>=', $input['date_from'])
                    ->where('date_from', '<=', $input['date_to']);
                })
                ->orWhere(function($query2) use ($input) {
                    $query2
                    ->where('date_from', '<=', $input['date_to'])
                    ->where('date_to', '>=', $input['date_to']);
                });
            })
            ->get();
        if ($records->count() > 0) {
            return $this->response([
                'Client' => ['Client have a booking'], 
                // 'Rental' => $records
            ], 400);
        }
        /**
         * Validasi Car
         */
        $records = $this->model
            ->where('car_id', '=', $input['car_id'])
            ->where('id', '!=', $id)
            // Validasi range tanggal
            ->where(function($query) use ($input) {
                $query
                ->where(function($query2) use ($input) {
                    $query2
                    ->where('date_from', '>=', $input['date_from'])
                    ->where('date_from', '<=', $input['date_to']);
                })
                ->orWhere(function($query2) use ($input) {
                    $query2
                    ->where('date_from', '<=', $input['date_to'])
                    ->where('date_to', '>=', $input['date_to']);
                });
            })
            ->get();
        if ($records->count() > 0) {
            return $this->response([
                'Car' => ['Selected car has been booked'], 
                // 'Rental' => $records
            ], 400);
        }
        /**
         * Send data
         */
        $record->update($request->input());
        $record->save();
        return $this->response(['id' => $record->id]);
    }

}
