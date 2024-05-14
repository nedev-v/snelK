<?php

namespace App\Modules\Products\Services;

use App\Models\Product;
use App\Modules\Core\Services\Service;

class ProductService extends Service
{
    protected $rules = [
        "price" => 'required|decimal:1,2'
    ];

    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function all()
    {
        return $this->model->with('translations')->get();
    }

    public function find($id)
    {
        return $this->model->with('translations')->find($id);
    }

    public function add($data)
    {
        $this->validate($data, $this->rules);
        if($this->hasErrors()){
            return;
        }
        $product = $this->model->create($data);
        return $product;
    }

    public function update($id, $data)
    {
        $this->validate($data, $this->rules);
        if($this->hasErrors()){
            return;
        }
        $product = $this->model->find($id)->update($data);
        return $product;
    }
}
