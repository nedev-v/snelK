<?php
namespace App\Modules\Orders\Services;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Modules\Core\Services\Service;

class OrderService extends Service{
    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'total_price' => 'required|numeric',
        'pickup_time' => 'required|regex:/^\d{2}:\d{2}$/', //HH:MM,
        'orderDetails' => 'required|array',
        'orderDetails.*.product_id' =>'required|exists:products,id',
        'orderDetails.*.cup_size' =>'required|string',
        'orderDetails.*.milk_flavour' =>'required|string|max:100',
        'orderDetails.*.syrup_flavour' =>'required|string|max:100',
        'orderDetails.*.is_decaf' =>'required',
    ];
    protected $modelDetail;
    public function __construct(Order $model, OrderDetail $modelDetail)
    {
        parent::__construct($model);
        $this->modelDetail = $modelDetail;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function add($data)
    {
        $this->validate($data, $this->rules);
        if($this->hasErrors()){
            return $this->getErrors();
        }
        $order = $this->model->create([
            'user_id' => $data["user_id"],
            'total_price' => $data["total_price"],
            'pickup_time' => $data["pickup_time"],
        ]);
        var_dump($order);
        foreach ($data['orderDetails'] as $detail){
            $this->modelDetail->create([
                "product_id" => $detail["product_id"],
                "cup_size" => $detail["cup_size"],
                "milk_flavour" => $detail["milk_flavour"],
                "syrup_flavour" => $detail["syrup_flavour"],
                "is_decaf" => filter_var($detail["is_decaf"], FILTER_VALIDATE_BOOLEAN),
                "order_id" => $order->id
            ]);
        }
        return $order;
    }

    public function allForUser($user_id){
        return $this->model->where('user_id', '=', $user_id)->get();
    }


}
