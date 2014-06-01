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
        "petrgrishin/array-map": "dev-master"
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