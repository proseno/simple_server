<?php

namespace Core;

class DataObject implements \ArrayAccess
{
    protected array $_data = [];

    protected static array $_underscoreCache = [];

    public function __construct(array $data = [])
    {
        $this->_data = $data;
    }

    public function addData(array $arr): static
    {
        foreach ($arr as $index => $value) {
            $this->setData($index, $value);
        }
        return $this;
    }

    public function setData($key, $value = null): static
    {
        if ($key === (array)$key) {
            $this->_data = $key;
        } else {
            $this->_data[$key] = $value;
        }
        return $this;
    }

    public function unsetData($key = null): static
    {
        if ($key === null) {
            $this->setData([]);
        } elseif (is_string($key)) {
            if (isset($this->_data[$key]) || array_key_exists($key, $this->_data)) {
                unset($this->_data[$key]);
            }
        } elseif ($key === (array)$key) {
            foreach ($key as $element) {
                $this->unsetData($element);
            }
        }
        return $this;
    }

    public function getData($key = '', $index = null)
    {
        if ('' === $key) {
            return $this->_data;
        }

        /* process a/b/c key as ['a']['b']['c'] */
        if (strpos($key, '/') !== false) {
            $data = $this->getDataByPath($key);
        } else {
            $data = $this->_getData($key);
        }

        if ($index !== null) {
            if ($data === (array)$data) {
                $data = $data[$index] ?? null;
            } elseif (is_string($data)) {
                $data = explode(PHP_EOL, $data);
                $data = $data[$index] ?? null;
            } elseif ($data instanceof \Core\DataObject) {
                $data = $data->getData($index);
            } else {
                $data = null;
            }
        }
        return $data;
    }

    public function getDataByPath($path)
    {
        $keys = explode('/', $path);

        $data = $this->_data;
        foreach ($keys as $key) {
            if ((array)$data === $data && isset($data[$key])) {
                $data = $data[$key];
            } elseif ($data instanceof \Core\DataObject) {
                $data = $data->getDataByKey($key);
            } else {
                return null;
            }
        }
        return $data;
    }

    public function getDataByKey($key)
    {
        return $this->_getData($key);
    }

    protected function _getData($key)
    {
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        }
        return null;
    }

    public function setDataUsingMethod($key, $args = []): static
    {
        $method = 'set' . str_replace('_', '', ucwords($key, '_'));
        $this->{$method}($args);
        return $this;
    }

    public function getDataUsingMethod($key, $args = null)
    {
        $method = 'get' . str_replace('_', '', ucwords($key, '_'));
        return $this->{$method}($args);
    }

    public function hasData($key = ''): bool
    {
        if (empty($key) || !is_string($key)) {
            return !empty($this->_data);
        }
        return array_key_exists($key, $this->_data);
    }

    public function toArray(array $keys = []): array
    {
        if (empty($keys)) {
            return $this->_data;
        }

        $result = [];
        foreach ($keys as $key) {
            if (isset($this->_data[$key])) {
                $result[$key] = $this->_data[$key];
            } else {
                $result[$key] = null;
            }
        }
        return $result;
    }

    public function convertToArray(array $keys = []): array
    {
        return $this->toArray($keys);
    }

    public function toXml(array $keys = [], $rootName = 'item', $addOpenTag = false, $addCdata = true): string
    {
        $xml = '';
        $data = $this->toArray($keys);
        foreach ($data as $fieldName => $fieldValue) {
            if ($addCdata === true) {
                $fieldValue = "<![CDATA[{$fieldValue}]]>";
            } else {
                $fieldValue = str_replace(
                    ['&', '"', "'", '<', '>'],
                    ['&amp;', '&quot;', '&apos;', '&lt;', '&gt;'],
                    $fieldValue
                );
            }
            $xml .= "<{$fieldName}>{$fieldValue}</{$fieldName}>\n";
        }
        if ($rootName) {
            $xml = "<{$rootName}>\n{$xml}</{$rootName}>\n";
        }
        if ($addOpenTag) {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $xml;
        }
        return $xml;
    }

    public function convertToXml(
        array $arrAttributes = [],
              $rootName = 'item',
              $addOpenTag = false,
              $addCdata = true
    ): string {
        return $this->toXml($arrAttributes, $rootName, $addOpenTag, $addCdata);
    }

    public function toString($format = '')
    {
        if (empty($format)) {
            $result = implode(', ', $this->getData());
        } else {
            preg_match_all('/\{\{([a-z0-9_]+)\}\}/is', $format, $matches);
            foreach ($matches[1] as $var) {
                $format = str_replace('{{' . $var . '}}', $this->getData($var), $format);
            }
            $result = $format;
        }
        return $result;
    }

    public function __call($method, $args)
    {
        switch (substr($method, 0, 3)) {
            case 'get':
                $key = $this->_underscore(substr($method, 3));
                $index = $args[0] ?? null;
                return $this->getData($key, $index);
            case 'set':
                $key = $this->_underscore(substr($method, 3));
                $value = $args[0] ?? null;
                return $this->setData($key, $value);
            case 'uns':
                $key = $this->_underscore(substr($method, 3));
                return $this->unsetData($key);
            case 'has':
                $key = $this->_underscore(substr($method, 3));
                return isset($this->_data[$key]);
        }
        throw new \Exception("Invalid method " . get_class($this) . "::$method");
    }

    public function isEmpty(): bool
    {
        if (empty($this->_data)) {
            return true;
        }
        return false;
    }

    protected function _underscore($name)
    {
        if (isset(self::$_underscoreCache[$name])) {
            return self::$_underscoreCache[$name];
        }
        $result = strtolower(trim(preg_replace('/([A-Z]|[0-9]+)/', "_$1", $name), '_'));
        self::$_underscoreCache[$name] = $result;
        return $result;
    }

    public function serialize($keys = [], $valueSeparator = '=', $fieldSeparator = ' ', $quote = '"'): string
    {
        $data = [];
        if (empty($keys)) {
            $keys = array_keys($this->_data);
        }

        foreach ($this->_data as $key => $value) {
            if (in_array($key, $keys)) {
                $data[] = $key . $valueSeparator . $quote . $value . $quote;
            }
        }
        return implode($fieldSeparator, $data);
    }

    public function debug($data = null, &$objects = []): array|string
    {
        if ($data === null) {
            $hash = spl_object_hash($this);
            if (!empty($objects[$hash])) {
                return '*** RECURSION ***';
            }
            $objects[$hash] = true;
            $data = $this->getData();
        }
        $debug = [];
        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                $debug[$key] = $value;
            } elseif (is_array($value)) {
                $debug[$key] = $this->debug($value, $objects);
            } elseif ($value instanceof \Core\DataObject) {
                $debug[$key . ' (' . get_class($value) . ')'] = $value->debug(null, $objects);
            }
        }
        return $debug;
    }

    public function offsetSet($offset, $value)
    {
        $this->_data[$offset] = $value;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->_data[$offset]) || array_key_exists($offset, $this->_data);
    }

    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
    }

    public function offsetGet($offset)
    {
        if (isset($this->_data[$offset])) {
            return $this->_data[$offset];
        }
        return null;
    }
}
