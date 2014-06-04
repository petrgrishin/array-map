<?php
use \PetrGrishin\ArrayMap\ArrayMap;

/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

class ArrayMapTest extends PHPUnit_Framework_TestCase {

    public function testCreateInstance() {
        $instance = new ArrayMap();
        $this->assertInstanceOf(ArrayMap::className(), $instance);
    }

    public function testSimpleMapping() {
        $original = array(1, 2, 3);
        $instance = ArrayMap::create($original);
        $instance->map(function ($value) {
            return $value * 2;
        });
        $this->assertEquals(array(2, 4, 6), $instance->getArray());
    }

    public function testKeyMapping() {
        $original = array(1 => 1, 2 => 2, 3 => 3);
        $instance = ArrayMap::create($original);
        $instance->map(function ($value, $key) {
            return array(($key - 1) => $value * 2);
        });
        $this->assertEquals(array(0 => 2, 1 => 4, 2 => 6), $instance->getArray());
    }

    public function testMergeWith() {
        $original = array(1, 2, 3);
        $instance = ArrayMap::create($original);
        $instance->mergeWith(array(4, 5, 6), false);
        $this->assertEquals(array(1, 2, 3, 4, 5, 6), $instance->getArray());
    }

    public function testRecursiveMergeWith() {
        $original = array('a' => array(1), 'b', 'c');
        $instance = ArrayMap::create($original);
        $instance->mergeWith(array('a' => array(2), 'd', 'e'));
        $this->assertEquals(array('a' => array(1, 2), 'b', 'c', 'd', 'e'), $instance->getArray());
    }

    public function testFiltering() {
        $original = array('a' => 1, 'b' => 2, 'c' => 3);
        $instance = ArrayMap::create($original);
        $instance->filter(function ($value) {
            return $value > 2;
        });
        $this->assertEquals(array('c' => 3), $instance->getArray());
    }

    public function testFilteringUseKeys() {
        $original = array('a' => 1, 'b' => 2, 'c' => 3);
        $instance = ArrayMap::create($original);
        $instance->filter(function ($value, $key) {
            return $key === 'c';
        });
        $this->assertEquals(array('c' => 3), $instance->getArray());
    }

    public function testUserSortByValue() {
        $original = array('a' => 2, 'b' => 3, 'c' => 1);
        $instance = ArrayMap::create($original);
        $instance->userSortByValue(function ($first, $second) {
            return $first < $second ? -1 : 1;
        });
        $this->assertEquals(array('c' => 1, 'a' => 2, 'b' => 3), $instance->getArray());
    }

    public function testUserSortByKey() {
        $original = array('b' => 2, 'c' => 3, 'a' => 1);
        $instance = ArrayMap::create($original);
        $instance->userSortByValue(function ($first, $second) {
            return strcasecmp($first, $second) ? -1 : 1;
        });
        $this->assertEquals(array('a' => 1, 'b' => 2, 'c' => 3), $instance->getArray());
    }
}
