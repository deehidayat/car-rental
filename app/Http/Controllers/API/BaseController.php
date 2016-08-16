<?php

namespace App\Http\Controllers\API;

use Validator;
use Response;
use Illuminate\Routing\Controller;

use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as Application;
use Exception;

class BaseController extends Controller
{
    protected $model;
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->middleware('guest');
        $this->makeModel();
    }

    private function makeModel()
    {
        $model = $this->app->make($this->model());
        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }
        return $this->model = $model;
    }

    protected function response($data, $code = 200) {
        return Response::json($data, $code, [], JSON_NUMERIC_CHECK);
    }

    protected function rules() {
        return $this->model->rules;
    }

    public function index(){
        return $this->response($this->model->all());
    }

    public function show($id){
        try {
            $record = $this->model->findOrFail($id);
        } catch (Exception $e) {
            return $this->response(['id' => $id, 'message' => 'Client not found'], 400);
        }
        return $this->response($record);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            return $this->response($validator->messages(), 400);
        }
        $record = $this->model->create($request->input());
        return $this->response(['id' => $record->id]);
    }

    public function update($id, Request $request){
        try {
            $record = $this->model->findOrFail($id);
        } catch (Exception $e) {
            return $this->response(['id' => $id, 'message' => 'Client not found'], 400);
        }
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            return $this->response($validator->messages(), 400);
        }
        $record->update($request->input());
        $record->save();
        return $this->response(['id' => $record->id]);
    }

    public function destroy($id){
        try {
            $record = $this->model->findOrFail($id);
        } catch (Exception $e) {
            return $this->response(['id' => $id, 'message' => 'Client not found'], 400);
        }
        $record->delete();
        return $this->response(['id' => $record->id]);
    }
}
