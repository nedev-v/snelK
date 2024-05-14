<?php

namespace App\Http\Controllers;

use App\Modules\OrderDetails\Services\OrderDetailService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderDetailController extends Controller
{
    protected $service;

    public function __construct(OrderDetailService $service)
    {
        $this->service = $service;
    }

    public function allOfOrder($order_id){
        $orderDetails = $this->service->allOfOrder($order_id);
        return response()->json($orderDetails);
    }

    public function add(Request $request){
        $orderDetail = $this->service->add($request->all());
        if($this->service->hasErrors()){
            $errors = $this->service->getErrors();
            $errors = $this->presentErrors($errors);
            return response()->json($errors, ResponseAlias::HTTP_BAD_REQUEST);
        }
        return response()->json($orderDetail, ResponseAlias::HTTP_CREATED);
    }
}
