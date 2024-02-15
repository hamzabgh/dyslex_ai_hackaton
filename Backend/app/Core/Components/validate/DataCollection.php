<?php

namespace App\Components\Validate;

use Illuminate\Http\Request;
use App\Components\Validate\DataValidator;

class DataCollection
{
    const POST = 'post';
    const GET = 'get';

    private $array = [];
    private $has_error = false;
    private $errors = [];

    private $request;

    public function __construct($array = [], $method = DataCollection::POST)
    {
        $this->request = Request::capture();

        foreach ($array as $item) {
            $value = addslashes($method == DataCollection::POST ? $this->request->input($item[0]) : $this->request->query($item[0]));
            $this->add($item[0], $value, $item[1]);
        }
    }

    public function add($name, $value, $filter = null)
    {
        $v = new DataValidator($name, $value, $filter);

        if (!$v->is_valid()) {
            $this->has_error = true;
            array_push($this->errors, $name);
        }

        $this->array[$name] = $v->get_value();
    }

    public function delete($name)
    {
        unset($this->array[$name]);
    }

    public function get(): array
    {
        if ($this->has_error) {
            return $this->errors;
        }

        return $this->array;
    }

    public function getValues(): array
    {
        if ($this->has_error) {
            return $this->errors;
        }

        return array_values($this->array);
    }

    public function getItem($name)
    {
        return $this->array[$name];
    }

    public function is_ok(): bool
    {
        return !$this->has_error;
    }

    public function get_error_status(): array
    {
        $status = [
            'result' => 0,
            'message' => 'Veuillez vérifier que les données envoyées sont valides !',
        ];

        return $status;
    }
}
