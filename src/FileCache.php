<?php

namespace PhpCsFixer;

final class FileCache implements Cache
{
    /**
     * @var string
     */
    private $php;

    /**
     * @var string
     */
    private $version;

    /**
     * @var bool
     */
    private $linting;

    /**
     * @var array
     */
    private $rules;

    /**
     * @var array
     */
    private $values = array();

    /**
     * @param string $version
     * @param bool $linting
     * @param array $rules
     */
    public function __construct($version, $linting, array $rules)
    {
        $this->php = PHP_VERSION;
        $this->version = $version;
        $this->linting = $linting;
        $this->rules = $rules;
    }

    public function php()
    {
        return $this->php;
    }

    public function version()
    {
        return $this->version;
    }

    public function linting()
    {
        return $this->linting;
    }

    public function rules()
    {
        return $this->rules;
    }

    public function isStale($version, $linting, $rules)
    {
        return $this->php !== PHP_VERSION || $this->version !== $version || $this->linting !== $linting || $this->rules !== $rules;
    }

    public function set($key, $value)
    {
        $this->values[$key] = $value;
    }

    public function get($key)
    {
        if (!array_key_exists($key, $this->values)) {
            return;
        }

        return $this->values[$key];
    }

    public function clear($filename)
    {
        unset($this->values[$filename]);
    }

    public function serialize()
    {
        return serialize(array(
            'php' => $this->php,
            'version' => $this->version,
            'linting' => $this->linting,
            'rules' => $this->rules,
            'hashes' => $this->values,
        ));
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        $this->php = $data['php'];
        $this->version = $data['version'];
        $this->linting = $data['linting'];
        $this->rules = $data['rules'];
        $this->values = $data['hashes'];
    }
}
