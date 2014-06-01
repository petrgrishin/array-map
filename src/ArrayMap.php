<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

namespace PetrGrishin\ArrayMap;


use PetrGrishin\ArrayMap\Exception\ArrayMapException;

class ArrayMap {
    /** @var array */
    private $data;

    /**
     * @return string
     */
    public static function className() {
        return get_called_class();
    }

    /**
     * @param array|null $data
     * @return static
     */
    public static function create(array $data = null) {
        return new static($data);
    }

    /**
     * @param array|null $data
     */
    public function __construct(array $data = null) {
        $this->setArray($data ?: array());
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setArray(array $data) {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getArray() {
        return $this->data;
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
        foreach ($this->data as $key => $item) {
            $array = array_merge_recursive($array, (array)call_user_func($callback, $item, $key));
        }
        $this->data = $array;
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
            $this->data = array_merge_recursive($this->data, $data);
        } else {
            $this->data = array_merge($this->data, $data);
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
        foreach ($this->data as $key => $item) {
            if(call_user_func($callback, $item, $key)) {
                $array[$key] = $item;
            }
        }
        $this->data = $array;
        return $this;
    }
}
