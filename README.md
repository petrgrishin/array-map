array-map
=========
[![Travis CI](https://travis-ci.org/petrgrishin/array-map.png "Travis CI")](https://travis-ci.org/petrgrishin/array-map)
[![Coverage Status](https://coveralls.io/repos/petrgrishin/array-map/badge.png?branch=master)](https://coveralls.io/r/petrgrishin/array-map?branch=master)

The object oriented approach to working with arrays

Installation
------------
Add a dependency to your project's composer.json:
```json
{
    "require": {
        "petrgrishin/array-map": "~1.0"
    }
}
```

Usage examples
--------------
#### Map
Using keys
```php
$array = ArrayMap::create($array)
    ->map(function ($value, $key) {
        return array($key => $value);
    })
    ->getArray();
```

Simple
```php
$array = ArrayMap::create($array)
    ->map(function ($value) {
        return $value;
    })
    ->getArray();
```

#### Merge
Recursive merge
```php
$array = ArrayMap::create($array)
    ->mergeWith(array(
        1 => 1,
        2 => 2,
        3 => array(
            1 => 1,
            2 => 2,
        ),
    ))
    ->getArray();
```

One level merge
```php
$array = ArrayMap::create($array)
    ->mergeWith(array(
        1 => 1,
        2 => 2,
    ), false)
    ->getArray();
```

#### Filtering
```php
$array = ArrayMap::create($array)
    ->filter(function ($value, $key) {
        return $value > 10 && $key > 2;
    })
    ->getArray();
```

#### User sort
Sort by value
```php
$array = ArrayMap::create($array)
    ->userSortByValue(function ($first, $second) {
        return $first < $second ? -1 : 1;
    })
    ->getArray();
```

Sort by key
```php
$array = ArrayMap::create($array)
    ->userSortByKey(function ($first, $second) {
        return $first < $second ? -1 : 1;
    })
    ->getArray();
```