<?php

namespace App\Http\Controllers;

use App\Modules\Products\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;

    }

    public function all(){
        $products = $this->service->all();
        return response()->json($products);
    }

    public function find($id){
        $product = $this->service->find($id);
        return response()->json($product);
    }

    public function add(Request $request){
        $product = $this->service->add($request->all());
        if($this->service->hasErrors()){
            $errors = $this->service->getErrors();
            $errors = $this->presentErrors($errors);
            return response()->json($errors, ResponseAlias::HTTP_BAD_REQUEST);
        }
        return response()->json($product, ResponseAlias::HTTP_CREATED);
    }

    public function update($id, Request $request){
        $product = $this->service->update($id, $request->all());
        if($this->service->hasErrors()){
            $errors = $this->service->getErrors();
            $errors = $this->presentErrors($errors);
            return response()->json($errors, ResponseAlias::HTTP_BAD_REQUEST);
        }
        return response()->json($product, ResponseAlias::HTTP_CREATED);
    }

    private function presentErrors($errors){
        return [
            "success" => false,
            "errors" => $errors
        ];
    }
}
