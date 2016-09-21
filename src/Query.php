<?php
namespace baohan\Request;


use baohan\Request\Query\Builder;

class Query
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

    public function getResult()
    {
        return $this->result;
    }
}