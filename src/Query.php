<?php
namespace baohan\Request;


use baohan\Collection\Collection;
use baohan\Request\Query\Builder;

class Query
{
    /**
     * @var Collection
     */
    private $data;

    /**
     * @var Collection
     */
    private $result;

    public function __construct(Collection $data)
    {
        $this->data = $data;
        $this->result = new Collection();
    }

    /**
     * @param $key
     * @return Builder
     */
    public function param($key)
    {
        return new Builder($this->data, $this->result, $key);
    }

    /**
     * @return Collection
     */
    public function getResult()
    {
        return $this->result;
    }
}