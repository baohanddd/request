<?php
namespace baohan\Request\Saver;


use baohan\Collection\Collection;

class Builder
{
    /**
     * @var Collection
     */
    private $params;

    /**
     * @var Collection
     */
    private $ret;

    /**
     * @var string
     */
    private $key;

    public function __construct(Collection &$params, Collection &$result, $key)
    {
        $this->params = $params;
        $this->key    = $key;
        $this->ret    = $result;

        if(!$this->null()) {
            $this->ret[$this->key] = $this->params[$this->key];
        }
    }

    public function push()
    {

    }

    public function pull()
    {

    }

    /**
     * @return bool
     */
    private function null()
    {
        return !isset($this->params[$this->key]) or !$this->params[$this->key];
    }
}