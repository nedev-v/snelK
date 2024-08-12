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

    public function allByLanguage($language = null, $perPage = 15)
    {
        return $this->model->with(['translations' => function($query) use ($language) {
            $query->where('language', $language);
        }])->paginate($perPage);
    }

    public function findByLanguage($id, $language = null)
    {
        Log::info("Language is $language");
        return $this->model->where('id', $id)
            ->with(['translations' => function($query) use ($language) {
                $query->where('language', $language);
            }])
            ->first();
    }

    public function all($perPage = 15)
    {
        return $this->model->with('translations')->paginate($perPage);
    }

    public function find($id)
    {
        return $this->model->where('id', $id)
            ->with('translations')
            ->first();
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
                "language" => $translation['language'], // Corrected access
                "title" => $translation['title'],       // Corrected access
                "short_description" => $translation['short_description'], // Corrected access
                "description" => $translation['description'], // Corrected access
                "product_id" => $product->id,
            ]);
        }
        return $product;
    }

    public function delete($id){
        try {
            $deleted = $this->model->findOrFail($id)->delete();

            if ($deleted) {
                return response()->json(['message' => 'Product deleted successfully.']);
            } else {
                return response()->json(['error' => 'Product could not be deleted.'], 500);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found.'], 404);
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
