<?php

namespace App\Modules\Users\Services;

use App\Models\Product;
use App\Models\User;
use App\Modules\Core\Services\Service;

class UserService extends Service{
    protected $rules = [
        "name" => "required",
        "email" => "required|email|unique:users",
        "password" => "required"
    ];

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function register($data){
        $this->validate($data, $this->rules);
        if($this->hasErrors()){
            return;
        }
        $user = $this->model->create($data);
        return $user;
    }

    public function login($data){
        $this->validate($data, [
            "email" => "required|email",
            "password" => "required"
        ]);
        if($this->hasErrors()){
            return;
        }
        $user = $this->model->create($data);
        return $user;
    }
}
