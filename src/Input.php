<?php
namespace baohan\Request;

class Input
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

    /**
     * @param array $enum
     * @return $this
     * @throws Exception
     */
    public function chkIn(array $enum)
    {
        if($this->null()) return $this;
        $val = $this->params[$this->key];
        if(isset($this->ret[$this->key])) $val = $this->ret[$this->key];
        if(!in_array($val, $enum))
            throw new Exception(sprintf('The %s is not in [%s]', $val, implode(', ', $enum)), 4101);
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function chkJson()
    {
        if($this->null()) return $this;
        json_decode($this->params[$this->key]);
        if(json_last_error() != JSON_ERROR_NONE)
            throw new Exception(sprintf('The `%s` is invalid JSON string', $this->key), 4102);
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function chkRequire()
    {
        if(!isset($this->params[$this->key])) {
            throw new Exception(sprintf('The %s must supplied', $this->key), 4103);
        }
        return $this;
    }

    /**
     * @return $this
     * @throws MongoId
     */
    public function chkMongoId()
    {
        $key = $this->key;
        $check = function($id) use ($key) {
            if(!preg_match('/^[0-9a-fA-F]{24}$/', $id))
                throw new Exception(sprintf('The "%s" is invalid formation of mongo id', $key), 4104);
        };

        if($this->null()) return $this;
        if(isset($this->ret[$this->key])) {
            if(is_array($this->ret[$this->key])) {
                foreach($this->ret[$this->key] as $id) {
                    $check($id);
                }
            } else {
                $check($this->ret[$this->key]);
            }
        } else {
            $check($this->params[$this->key]);
        }
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function chkTimestamp()
    {
        if($this->null()) return $this;
        $val = $this->params[$this->key];
        $len = strlen((string) $val);
        if($len != 10 || !is_numeric($val))
            throw new Exception(sprintf('The `%s` is invalid timestamp', $this->key), 4105);

        return $this;
    }

    /**
     * @param $key
     * @return $this
     * @throws Exception
     */
    public function chkRelated($key)
    {
        if(!isset($this->params[$key])) {
            throw new Exception(sprintf('The %s must supplied', $key), 4103);
        }
        return $this;
    }

    /**
     * @param $one
     * @param $another
     * @return $this
     * @throws Exception
     */
    public function chkAlternative($one, $another)
    {
        if(!isset($this->params[$one]) && !isset($this->params[$another])) {
            throw new Exception(sprintf('one of %s and %s must supplied', $one, $another), 4103);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function pk()
    {
        if($this->null()) return $this;
        $this->ret[$this->key] = isset($this->ret[$this->key]) ?
            new \MongoId($this->ret[$this->key]) : new \MongoId($this->params[$this->key]);
        return $this;
    }

    /**
     * @return $this
     */
    public function mongoDate()
    {
        $this->ret[$this->key] = new \MongoDate();
        return $this;
    }

    /**
     * @return $this
     */
    public function double()
    {
        if($this->null()) return $this;
        $this->ret[$this->key] = (double) $this->params[$this->key];
        return $this;
    }

    /**
     * @return $this
     */
    public function int()
    {
        if($this->null()) return $this;
        if(isset($this->ret[$this->key])) $this->ret[$this->key] = (int) $this->ret[$this->key];
        else                              $this->ret[$this->key] = (int) $this->params[$this->key];

        return $this;
    }

    /**
     * @return $this
     */
    public function json()
    {
        if($this->null()) return $this;
        if(isset($this->ret[$this->key])) $this->ret[$this->key] = json_decode($this->ret[$this->key], true);
        else                              $this->ret[$this->key] = json_decode($this->params[$this->key],   true);
        return $this;
    }

    /**
     * @return $this
     */
    public function bool()
    {
        if($this->null()) return $this;
        if(isset($this->ret[$this->key])) $this->ret[$this->key] = (bool) $this->ret[$this->key];
        else                              $this->ret[$this->key] = (bool) $this->params[$this->key];

        return $this;
    }

    /**
     * @return $this
     */
    public function defaults($val)
    {
        if($this->null()) {
            $this->ret[$this->key] = $val;
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function hash()
    {
        if($this->null()) return $this;
        $this->ret[$this->key] = md5($this->params[$this->key]);
        return $this;
    }

    /**
     * Rename original key to new one
     *
     * @param string $key
     * @return $this
     */
    public function assign($key)
    {
        if($this->null()) return $this;
        if(isset($this->ret[$this->key])) {
            $this->ret[$key] = $this->ret[$this->key];
            unset($this->ret[$this->key]);
        }
        else {
            $this->ret[$key] = $this->params[$this->key];
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function trim()
    {
        if($this->null()) return $this;
        $this->ret[$this->key] = trim($this->params[$this->key]);
        return $this;
    }

    /**
     * @return $this
     */
    public function str2arr()
    {
        if($this->null()) return $this;
        $this->ret[$this->key] = array_map('trim', explode(',', $this->params[$this->key]));
        return $this;
    }

    /**
     * @param int $int
     * @return $this
     */
    public function plus($int)
    {
        if(isset($this->ret[$this->key]))  $this->ret[$this->key] += $int;
        else if(!$this->null())            $this->ret[$this->key]  = $this->params[$this->key] + $int;
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
     * @return mixed|null
     */
    public function val()
    {
        if($this->null()) return null;
        return $this->params[$this->key];
    }

    /**
     * @return $this
     */
    public function del()
    {
        if(isset($this->ret[$this->key])) unset($this->ret[$this->key]);
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