<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

namespace PetrGrishin\ArrayMap;


use PetrGrishin\ArrayMap\Exception\ArrayMapException;
use PetrGrishin\ArrayObject\ArrayObject;
use PetrGrishin\ArrayObject\BaseArrayObject;

class ArrayMap extends BaseArrayObject {

    /**
     * @return string
     */
    public static function className() {
        return get_called_class();
    }

    /**
     * @param array|ArrayObject|null $data
     * @return static
     */
    public static function create($data = null) {
        return new static($data);
    }

    /**
     * @param array|ArrayObject|null $data
     */
    public function __construct($data = null) {
        $this->setArray($data ?: array());
    }

    public function copy() {
        return clone $this;
    }

    /**
     * Applies the callback to the elements of the given arrays
     *
     * Example map using keys:
     * $array = ArrayMap::create($array)
     *     ->map(function ($value, $key) {
     *         return array($key => $value);
     *     })
     *     ->getArray();
     *
     * @param $callback
     * @return $this
     * @throws Exception\ArrayMapException
     */
    public function map($callback) {
        if (!is_callable($callback)) {
            throw new ArrayMapException('Argument is not callable');
        }
        $array = array();
        foreach ($this->getArray() as $key => $item) {
            $result = call_user_func($callback, $item, $key);
            $array = is_array($result) ? array_replace_recursive($array, $result) : array_merge_recursive($array, (array)$result);
        }
        $this->setArray($array);
        return $this;
    }

    /**
     * Merge array
     *
     * Example:
     * $array = ArrayMap::create($array)
     *     ->mergeWith(array(
     *         1 => 1,
     *         2 => array(
     *             1 => 1,
     *         ),
     *     ))
     *     ->getArray();
     *
     * @param array $data
     * @param bool $recursive
     * @return $this
     */
    public function mergeWith(array $data, $recursive = true) {
        if ($recursive) {
            $this->setArray(array_merge_recursive($this->getArray(), $data));
        } else {
            $this->setArray(array_merge($this->getArray(), $data));
        }
        return $this;
    }

    /**
     * Replace array
     *
     * Example:
     * $array = ArrayMap::create($array)
     *     ->replaceWith(array(
     *         1 => 1,
     *         2 => array(
     *             1 => 1,
     *         ),
     *     ))
     *     ->getArray();
     *
     * @param array $data
     * @param bool $recursive
     * @return $this
     */
    public function replaceWith(array $data, $recursive = true) {
        if ($recursive) {
            $this->setArray(array_replace_recursive($this->getArray(), $data));
        } else {
            $this->setArray(array_replace($this->getArray(), $data));
        }
        return $this;
    }

    /**
     * Example filter using keys:
     * $array = ArrayMap::create($array)
     *     ->filter(function ($value, $key) {
     *         return $value > 2;
     *     })
     *     ->getArray();
     *
     * @param $callback
     * @return $this
     * @throws Exception\ArrayMapException
     */
    public function filter($callback) {
        if (!is_callable($callback)) {
            throw new ArrayMapException('Argument is not callable');
        }
        $array = array();
        foreach ($this->getArray() as $key => $item) {
            if(call_user_func($callback, $item, $key)) {
                $array[$key] = $item;
            }
        }
        $this->setArray($array);
        return $this;
    }

    public function userSortByValue($callback){
        if (!is_callable($callback)) {
            throw new ArrayMapException('Argument is not callable');
        }
        $array = $this->getArray();
        uasort($array, $callback);
        $this->setArray($array);
        return $this;
    }

    public function userSortByKey($callback){
        if (!is_callable($callback)) {
            throw new ArrayMapException('Argument is not callable');
        }
        $array = $this->getArray();
        uasort($array, $callback);
        $this->setArray($array);
        return $this;
    }
}
