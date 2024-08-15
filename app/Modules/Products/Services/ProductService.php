<?php

namespace App\Modules\Products\Services;

use App\Models\Product;
use App\Models\ProductTranslation;
use App\Modules\Core\Services\Service;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductService extends Service
{
    protected $rules = [
        "price" => 'required|numeric',
        "has_milk" => 'required',
        "translations" => 'required|array',
        'translations.*.language' => 'required|string|max:3',
        'translations.*.title' => 'required|string|max:50',
        'translations.*.short_description' => 'required|string|max:50',
        'translations.*.description' => 'required|string',
    ];
    protected $modelLang;

    public function __construct(Product $model, ProductTranslation $modelLang)
    {
        parent::__construct($model);
        $this->modelLang = $modelLang;
    }


    public function all($lang = null, $price = 0, $orderBy = 'asc', $hasMilk = null, $perPage = 15)
    {
        $data = $this->model->where('price', '>', $price);

        if ($lang) {
            $data->join('products_language', 'products.id', '=', 'products_language.product_id')
                ->where('products_language.language', $lang)
                ->orderBy('products_language.title', $orderBy);
        } else {
            $data->with('translations');
        }

        if(!is_null($hasMilk)){
            $hasMilk = filter_var($hasMilk,FILTER_VALIDATE_BOOLEAN);
            $data->where('has_milk', $hasMilk);
        }
        return $data->paginate($perPage);
    }

    public function find($id, $lang = null)
    {
        $query = $this->model->where('products.id', $id);

        if ($lang) {
            $query->join('products_language', 'products.id', '=', 'products_language.product_id')
                ->where('products_language.language', $lang)
                ->select('products.*', 'products_language.title', 'products_language.short_description', 'products_language.description');
        } else {
            $query->with('translations');
        }
        return $query->first();
    }

    public function add($data, $img_name)
    {
        $this->validate($data, $this->rules);
        if($this->hasErrors()){
            return $this->getErrors();
        }

        $product = $this->model->create([
            "price" => $data['price'],
            "has_milk" => filter_var($data['has_milk'], FILTER_VALIDATE_BOOLEAN),
            "image_path" => $img_name,
        ]);

        foreach ($data['translations'] as $translation) {
            $productTranslation = $this->modelLang->create([
                "language" => $translation['language'],
                "title" => $translation['title'],
                "short_description" => $translation['short_description'],
                "description" => $translation['description'],
                "product_id" => $product->id,
            ]);
        }
        return $product;
    }

    public function delete($id){
        try {
            return $this->model->findOrFail($id)->delete();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    public function update($id, $data, $img_name = "")
    {
        $this->validate($data, $this->rules);
        if($this->hasErrors()){
            return $this->getErrors();
        }
        $product = $this->model->find($id);
        if($img_name){
            $product->update([
                "price" => $data['price'],
                "has_milk" => filter_var($data['has_milk'], FILTER_VALIDATE_BOOLEAN),
                "image_path" => $img_name,
            ]);
        }else{
            $product->update([
                "price" => $data['price'],
                "has_milk" => filter_var($data['has_milk'], FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        foreach ($data['translations'] as $translation) {
            $productTranslation = $this->modelLang->updateOrCreate(
                ['product_id' => $product->id, 'language' => $translation['language']],
                [
                    "title" => $translation['title'],
                    "short_description" => $translation['short_description'],
                    "description" => $translation['description'],
                ]
            );
        }
        return $product;
    }
}
