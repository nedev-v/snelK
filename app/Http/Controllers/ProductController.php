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

    public function allByLanguage(Request $request, $language){
        $perPage = $request->input('per_page', 15);
        $page = $request->input('page', 1);

        $products = $this->service->allByLanguage($language, $perPage);

        foreach ($products as $product) {
            $product->image_path = asset("storage/images/$product->image_path");
        }

        return response()->json($products);
    }

    public function all(Request $request)
    {
        $perPage = $request->input('per_page', 15); // Number of items per page

        $products = $this->service->all($perPage);

        $products->getCollection()->transform(function ($product) {
            $product->image_path = asset("storage/images/{$product->image_path}");
            return $product;
        });

        return response()->json($products);
    }

    public function delete($id){
        $this->service->delete($id);
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

    public function findByLanguage($language, $id){
        $product = $this->service->findByLanguage($id, $language);
        if($product) {
            $product->image_path = asset("storage/images/{$product->image_path}");
            return response()->json($product);
        }else{
            return response()->json(['error' => 'Product is not found'], 404);
        }
    }

    public function find($id)
    {
        $product = $this->service->find($id);

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
