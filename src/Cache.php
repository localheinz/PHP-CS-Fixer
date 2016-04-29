<?php

namespace PhpCsFixer;

use Serializable;

interface Cache extends Serializable
{
    /**
     * @return string
     */
    public function php();

    /**
     * @return string
     */
    public function version();

    /**
     * @return bool
     */
    public function linting();

    /**
     * @return array
     */
    public function rules();

    /**
     * @param string $version
     * @param bool $linting
     * @param array $rules
     * @return bool
     */
    public function isStale($version, $linting, $rules);

    /**
     * @param string $key
     * @param int $value
     */
    public function set($key, $value);

    /**
     * @param string $key
     *
     * @return int
     */
    public function get($key);

    /**
     * @param string $key
     */
    public function clear($key);
}
