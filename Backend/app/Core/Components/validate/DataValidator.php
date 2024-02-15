<?php

namespace App\Components\Validate;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class DataValidator
{
    private $name = '';
    private $value = '';
    private $is_valid = false;

    public function __construct($name, $value, $filter)
    {
        $this->name = $name;
        $this->value = $value;

        $this->sanitize();

        if (method_exists($this, $filter) && is_callable([$this, $filter])) {
            call_user_func([$this, $filter]);
        }
    }

    private function sanitize()
    {
        if (is_string($this->value)) {
            $this->value = trim($this->value);
            $this->value = strip_tags($this->value);
            $this->value = htmlentities($this->value, ENT_QUOTES, 'utf-8');
        }
    }

    public function is_valid(): bool
    {
        return $this->is_valid;
    }

    public function get_value()
    {
        return $this->value;
    }

    private function id()
    {
        $validator = Validator::make(['id' => $this->value], ['id' => 'integer|gt:0']);
        $this->is_valid = !$validator->fails();
    }

    private function uuid()
    {
        $validator = Validator::make(['uuid' => $this->value], ['uuid' => 'uuid']);
        $this->is_valid = !$validator->fails();
    }

    private function int()
    {
        $validator = Validator::make(['int' => $this->value], ['int' => 'integer']);
        $this->is_valid = !$validator->fails();
    }

    private function tinyint()
    {
        $validator = Validator::make(['tinyint' => $this->value], ['tinyint' => 'in:0,1']);
        $this->is_valid = !$validator->fails();
    }

    private function money()
    {
        $validator = Validator::make(['money' => $this->value], ['money' => 'numeric']);
        $this->is_valid = !$validator->fails();
    }

    private function date()
    {
        $validator = Validator::make(['date' => $this->value], ['date' => 'date_format:d/m/Y']);
        $this->is_valid = !$validator->fails();
    }

    private function time()
    {
        $validator = Validator::make(['time' => $this->value], ['time' => 'date_format:H:i']);
        $this->is_valid = !$validator->fails();
    }

    private function sha512()
    {
        $validator = Validator::make(['sha512' => $this->value], ['sha512' => 'regex:/^[a-f0-9]{128}$/']);
        $this->is_valid = !$validator->fails();
    }

    private function bool()
    {
        $validator = Validator::make(['bool' => $this->value], ['bool' => 'boolean']);
        $this->is_valid = !$validator->fails();
    }

    private function ip()
    {
        $validator = Validator::make(['ip' => $this->value], ['ip' => 'ip']);
        $this->is_valid = !$validator->fails();
    }

    private function email()
    {
        $validator = Validator::make(['email' => $this->value], ['email' => 'email']);
        $this->is_valid = !$validator->fails();
    }

    private function string1()
    {
        $validator = Validator::make(['string1' => $this->value], ['string1' => 'max:1']);
        $this->is_valid = !$validator->fails();
    }

    private function string2()
    {
        $validator = Validator::make(['string2' => $this->value], ['string2' => 'max:2']);
        $this->is_valid = !$validator->fails();
    }

    private function string5()
    {
        $validator = Validator::make(['string5' => $this->value], ['string5' => 'max:5']);
        $this->is_valid = !$validator->fails();
    }

    private function string10()
    {
        $validator = Validator::make(['string10' => $this->value], ['string10' => 'max:10']);
        $this->is_valid = !$validator->fails();
    }

    private function string20()
    {
        $validator = Validator::make(['string20' => $this->value], ['string20' => 'max:20']);
        $this->is_valid = !$validator->fails();
    }

    private function string50()
    {
        $validator = Validator::make(['string50' => $this->value], ['string50' => 'max:50']);
        $this->is_valid = !$validator->fails();
    }

    private function string150()
    {
        $validator = Validator::make(['string150' => $this->value], ['string150' => 'max:150']);
        $this->is_valid = !$validator->fails();
    }

    private function string250()
    {
        $validator = Validator::make(['string250' => $this->value], ['string250' => 'max:250']);
        $this->is_valid = !$validator->fails();
    }

    private function string500()
    {
        $validator = Validator::make(['string500' => $this->value], ['string500' => 'max:500']);
        $this->is_valid = !$validator->fails();
    }
}
