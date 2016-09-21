<?php
namespace baohan\Request\Saver;


class Builder
{
    /**
     * @var array
     */
    private $params;

    /**
     * @var array
     */
    private $ret;

    /**
     * @var string
     */
    private $key;

    public function __construct(array &$params, array &$result, $key)
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