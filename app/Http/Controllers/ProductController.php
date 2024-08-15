<?php

namespace App\Http\Controllers;

use App\Modules\Products\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductController extends Controller
{
    protected ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;

    }


    public function all(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $lang = $request->input('lang');
        $hasMilk = $request->input('has_milk');
        $price = (int) $request->input('price');
        $orderBy = $request->input('order_by');

        $products = $this->service->all($lang, $price, $orderBy, $hasMilk, $perPage);

        $products->getCollection()->transform(function ($product) {
            $product->image_path = asset("storage/images/{$product->image_path}");
            return $product;
        });

        return response()->json($products);
    }

    public function delete($id){
        $deleted = $this->service->delete($id);
        if ($deleted) {
            return response()->json(['message' => 'Product deleted successfully.']);
        } else {
            return response()->json(['error' => 'Product could not be deleted.'], 500);
        }
    }

    public function find(Request $request, $id)
    {
        $lang = $request->input('lang');

        $product = $this->service->find($id, $lang);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $product->image_path = asset("storage/images/{$product->image_path}");

        return response()->json($product);
    }

    protected function getFileName($file){
        $extension = $file->getClientOriginalExtension();
        $filename = basename($file->getClientOriginalName(), $extension);
        return time() . "-" . Str::slug($filename) . '.' . $extension;
    }

    public function add(Request $request){
        $img = $request->file("image_path");
        $img_name = $this->getFileName($img);
        $img->move(storage_path("app/public/images"), $img_name);

        $product = $this->service->add($request->all(), $img_name);
        if($this->service->hasErrors()){
            $errors = $this->service->getErrors();
            $errors = $this->presentErrors($errors);
            return response()->json($errors, ResponseAlias::HTTP_BAD_REQUEST);
        }

        return response()->json($product, ResponseAlias::HTTP_CREATED);
    }

    public function update($id, Request $request){
        Log::info('User logged in', ['data' => $request->all()]);

        if($request->file("image_path")) {
            $img = $request->file("image_path");
            $img_name = $this->getFileName($img);
            $img->move(storage_path("app/public/images"), $img_name);
            $product = $this->service->update($id, $request->all(), $img_name);
        }else{
            $product = $this->service->update($id, $request->all());
        }

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
            "errors" => [$errors]
        ];
    }
}
