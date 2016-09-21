<?php
namespace baohan\Request;

class Request
{
    /**
     * @var array
     */
    private $data;

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
        return new Input($this->data, $this->result, $key);
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getQuery()
    {
        return new Query($this->result);
    }

    public function getSaver()
    {
        return new Saver($this->result);
    }
}
