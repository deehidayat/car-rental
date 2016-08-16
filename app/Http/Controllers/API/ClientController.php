<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as APIBaseController;

use App\Model\Client;

use Exception;

class ClientController extends APIBaseController
{
    public function model()
    {
        return Client::class;
    }

    public function histories($id) {
        try {
            $record = $this->model->findOrFail($id);
        } catch (Exception $e) {
            return $this->response(['id' => $id, 'message' => 'Record not found'], 400);
        }
        $result = $record->toArray();
        $result['histories'] = $record->rentals;
        return $this->response($result);
    }
}
