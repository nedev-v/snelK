<?php

namespace App\Modules\OrderDetails\Services;
use App\Models\OrderDetail;
use App\Modules\Core\Services\Service;

class OrderDetailService extends Service
{
    protected $rules = [
        'order_id' => 'required|exists:orders,id',
        'product_id' => 'required|exists:products,id',
        'cup_size' => 'required|integer',
        'is_decaf' => 'required|boolean',
        'milk_flavour' => 'required|string|max:100',
        'syrup_flavour' => 'nullable|string|max:100',
    ];
    public function __construct(OrderDetail $model)
    {
        parent::__construct($model);
    }

    public function allOfOrder($order_id)
    {
        return $this->model->where('order_id', '=', $order_id)->get();
    }

    public function add($data){
        $this->validate($data, $this->rules);
        if($this->hasErrors()){
            return;
        }
        $orderDetail = $this->model->create($data);
        return $orderDetail;
    }
}
