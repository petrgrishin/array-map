<?php
use \PetrGrishin\ArrayMap\ArrayMap;
use PetrGrishin\ArrayObject\ArrayObject;

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

    public function testReplaceWith() {
        $original = array('a' => 1, 'b' => 2, 'c' => 3);
        $instance = ArrayMap::create($original);
        $instance->replaceWith(array('c' => 4, 'd' => 5), false);
        $this->assertEquals(array('a' => 1, 'b' => 2, 'c' => 4, 'd' => 5), $instance->getArray());
    }

    public function testRecursiveReplaceWith() {
        $original = array('a' => array('x' => 1));
        $instance = ArrayMap::create($original);
        $instance->replaceWith(array('a' => array('x' => 2)));
        $this->assertEquals(array('a' => array('x' => 2)), $instance->getArray());
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

    public function testArrayObject() {
        $original = array('a' => 1, 'b' => 2, 'c' => 3);
        $expected = array('a' => 2, 'b' => 4, 'c' => 6);
        $arrayObject = new ArrayMapTest_ArrayObject();
        $arrayObject->setArray($original);
        $this->assertEquals($original, $arrayObject->getArray());
        $instance = ArrayMap::create($arrayObject);
        $this->assertEquals($original, $arrayObject->getArray());
        $instance->map(function ($value, $key) {
            return array($key => $value * 2);
        });
        $this->assertEquals($expected, $instance->getArray());
        $this->assertEquals($expected, $arrayObject->getArray());
    }

    public function testNesting() {
        $original = array('a' => 1, 'b' => 2, 'c' => 3);
        $expected = array('a' => 2, 'b' => 4, 'c' => 6);
        $instanceFirst = ArrayMap::create($original);
        $instanceSecond = ArrayMap::create($instanceFirst);
        $instanceThird = ArrayMap::create($instanceSecond);
        $instanceThird->map(function ($value, $key) {
            return array($key => $value * 2);
        });
        $this->assertEquals($expected, $instanceFirst->getArray());
    }
}

class ArrayMapTest_ArrayObject implements ArrayObject {

    private $_data;

    /**
     * @param array|ArrayObject $data
     */
    public function setArray($data) {
        $this->_data = $data;
    }

    /**
     * @return array
     */
    public function getArray() {
        return $this->_data;
    }
}
