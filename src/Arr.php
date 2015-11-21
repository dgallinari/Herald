<?php
namespace HeraldOfArms\Herald;

use InvalidArgumentException;

class Arr
{
    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public static function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array  $array
     * @param  string $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        $key = trim($key);

        if (is_null($key)) {
            return $array;
        }
        if (isset($array[$key])) {
            return $array[$key];
        }
        foreach (explode(':', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return static::value($default);
            }
            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Check if an item exists in an array using "dot" notation.
     *
     * @param  array  $array
     * @param  string $key
     * @param  bool   $not_empty
     *
     * @return bool
     */
    public static function has($array, $key, $not_empty = false)
    {
        $key = trim($key);

        if (empty($array) || is_null($key)) {
            return false;
        }
        if (array_key_exists($key, $array)) {
            return ($not_empty) ? !empty($array[$key]) : true;
        }
        foreach (explode(':', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return false;
            }
            $array = $array[$segment];
        }

        return ($not_empty) ? !empty($array[$key]) : true;
    }

    /**
     * Check the array contains the required keys.
     *
     * @param string[] $options
     * @param string[] $required
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public static function requires(array $options, array $required = [])
    {
        foreach ($required as $key) {
            if (!static::has($options, $key)) {
                throw new InvalidArgumentException("Missing required parameter: {$key}");
            }
        }
    }
}