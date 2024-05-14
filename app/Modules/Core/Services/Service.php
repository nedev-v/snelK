<?php
namespace App\Modules\Core\Services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

abstract class Service
{
    protected $model;
    protected $fields;
    protected $errors;
    protected $rules;

    public function __construct($model)
    {
        $this->model = $model;
        $this->errors = new MessageBag();
    }
/*
    abstract public function all();
    abstract public function find($id);
    abstract public function add($data);
    abstract public function update($id, $data);*/

    public function validate($data, $ruleKey)
    {
        /*$rules = $this->rules[$ruleKey] ?? $this->rules;*/

        $this->errors = new MessageBag();
        $validator = Validator::make($data, $this->rules);
        if($validator->fails()){
            $this->errors = $validator->errors();
        }
    }

    public function getErrors(){
        return $this->errors;
    }

    public function hasErrors(){
        return $this->errors->isNotEmpty();
    }



}
