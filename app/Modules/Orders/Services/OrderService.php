<?php
namespace App\Modules\Orders\Services;
use App\Models\Order;
use App\Modules\Core\Services\Service;

class OrderService extends Service{
    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'total_price' => 'required|decimal:1,2',
        'pickup_time' => 'required|regex:/^\d{2}:\d{2}$/', //HH:MM
    ];
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function add($data)
    {
        $this->validate($data, $this->rules);
        if($this->hasErrors()){
            return;
        }
        $order = $this->model->create($data);
        return $order;
    }

    public function allForUser($user_id){
        return $this->model->where('user_id', '=', $user_id)->get();
    }


}
