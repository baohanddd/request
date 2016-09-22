<?php
namespace baohan\Request;

use baohan\Collection\Collection;

class Request
{
    /**
     * @var Collection
     */
    private $data;

    /**
     * @var Collection
     */
    private $result;

    public function __construct(array $data)
    {
        $this->data = new Collection($data);
        $this->result = new Collection();
    }

    /**
     * @param $key
     * @return Input
     */
    public function param($key)
    {
        return new Input($this->data, $this->result, $key);
    }

    /**
     * @return Collection
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return Query
     */
    public function getQuery()
    {
        return new Query($this->result);
    }

    /**
     * @return Saver
     */
    public function getSaver()
    {
        return new Saver($this->result);
    }
}
