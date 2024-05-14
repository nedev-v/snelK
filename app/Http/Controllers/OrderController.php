<?php

namespace App\Http\Controllers;

use App\Modules\Orders\Services\OrderService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderController extends Controller
{
    protected $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function find($id){
        $order = $this->service->find($id);
        return response()->json($order);
    }

    public function add(Request $request){
        $order = $this->service->add($request->all());
        if($this->service->hasErrors()){
            $errors = $this->service->getErrors();
            $errors = $this->presentErrors($errors);
            return response()->json($errors, ResponseAlias::HTTP_BAD_REQUEST);
        }
        return response()->json($order, ResponseAlias::HTTP_CREATED);
    }

    public function allForUser(Request $request){
        $orders = $this->service->allForUser($request->query('user_id'));
        return response()->json($orders);
    }

    private function presentErrors($errors){
        return [
            "success" => false,
            "errors" => $errors
        ];
    }
}
