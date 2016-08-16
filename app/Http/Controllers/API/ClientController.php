<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as APIBaseController;

use App\Model\Client;

class ClientController extends APIBaseController
{
    public function model()
    {
        return Client::class;
    }
}
