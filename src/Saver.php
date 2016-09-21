<?php
namespace baohan\Request;

use baohan\Request\Saver\Builder;

class Saver
{

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $result = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function param($key)
    {
        return new Builder($this->data, $this->result, $key);
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param $model
     */
    public function assign($model)
    {
        foreach($this->result as $key => $val)
        {
            if(!property_exists($model, $key)) continue;
            $model->$key = $val;
        }
    }
}