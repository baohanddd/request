<?php
namespace baohan\Request\Query;


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

    /**
     * @param bool $mongoDate
     * @return $this
     * @throws \Exception
     */
    public function range($mongoDate = false)
    {
        if($this->null()) return $this;
        $val = trim($this->params[$this->key]);
        $ops = array_map('trim', explode(' ', $val));
        if(count($ops) != 2) throw new \Exception(sprintf('Invalid operator: %s', $this->key), 4104);
        list($op, $v) = $ops;
        $v = intval($v);
        if($mongoDate) $v = new \MongoDate($v);
        switch($op)
        {
            case '>'  : $this->gt($v);   break;
            case '>=' : $this->gte($v);  break;
            case '<'  : $this->lt($v);   break;
            case '<=' : $this->lte($v);  break;
            case '==' : $this->eq($v);   break;
            case '!=' : $this->ne($v);   break;
            default:
                throw new \Exception(sprintf('Invalid operator: %s', $this->key), 4104);
        }

        return $this;
    }

    /**
     * @param array $items
     * @return $this
     */
    public function in(array $items = [])
    {
        if($items)              $this->ret[$this->key] = ['$in' => $items];
        else if(!$this->null()) $this->ret[$this->key] = ['$in' => $this->params[$this->key]];
        return $this;
    }

    /**
     * @param array $items
     * @return $this
     */
    public function nin(array $items = [])
    {
        if($items)              $this->ret[$this->key] = ['$nin' => $items];
        else if(!$this->null()) $this->ret[$this->key] = ['$nin' => $this->params[$this->key]];
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function eq($value)
    {
        $this->ret[$this->key] = $value;
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function ne($value = null)
    {
        if($value === null)      $this->ret[$this->key] = ['$ne' => null];
        else                     $this->ret[$this->key] = ['$ne' => $value];
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function gt($value = null)
    {
        if($value)               $this->ret[$this->key] = ['$gt' => $value];
        else if(!$this->null())  $this->ret[$this->key] = ['$gt' => $this->params[$this->key]];
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function gte($value = null)
    {
        if($value)               $this->ret[$this->key] = ['$gte' => $value];
        else if(!$this->null())  $this->ret[$this->key] = ['$gte' => $this->params[$this->key]];
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function lt($value = null)
    {
        if($value)              $this->ret[$this->key] = ['$lt' => $value];
        else if(!$this->null()) $this->ret[$this->key] = ['$lt' => $this->params[$this->key]];
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function lte($value = null)
    {
        if($value)              $this->ret[$this->key] = ['$lte' => $value];
        else if(!$this->null()) $this->ret[$this->key] = ['$lte' => $this->params[$this->key]];
        return $this;
    }

    /**
     * @return $this
     */
    public function exists()
    {
        $this->ret[$this->key] = ['$exists' => true];
        return $this;
    }

    /**
     * @return $this
     */
    public function regex()
    {
        if($this->null()) return $this;
        $val = $this->params[$this->key];
        if(isset($this->ret[$this->key])) $val = $this->ret[$this->key];
        $this->ret[$this->key] = ['$regex' => $val];
        return $this;
    }

    /**
     * @return bool
     */
    private function null()
    {
        return !isset($this->params[$this->key]) or !$this->params[$this->key];
    }
}