<?php

/**
 * This file is part of the Lean Mapper library (http://www.leanmapper.com)
 *
 * Copyright (c) 2013 Vojtěch Kohout (aka Tharos)
 *
 * For the full copyright and license information, please view the file
 * license.md that was distributed with this source code.
 */

declare(strict_types=1);

namespace LeanMapper;

use Closure;
use LeanMapper\Exception\InvalidArgumentException;

/**
 * \Dibi\Fluent with filter support
 *
 * @author Vojtěch Kohout
 */
class Fluent extends \Dibi\Fluent
{

    /** @var array */
    public static $masks = [ // fixes missing UNION in dibi
        'SELECT' => [
            'SELECT', 'DISTINCT', 'FROM', 'WHERE', 'GROUP BY',
            'HAVING', 'ORDER BY', 'LIMIT', 'OFFSET', 'UNION',
        ],
        'UPDATE' => ['UPDATE', 'SET', 'WHERE', 'ORDER BY', 'LIMIT'],
        'INSERT' => ['INSERT', 'INTO', 'VALUES', 'SELECT'],
        'DELETE' => ['DELETE', 'FROM', 'USING', 'WHERE', 'ORDER BY', 'LIMIT'],
    ];

    /** @var array|null */
    private $relatedKeys;



    /**
     * Applies given filter to current statement
     *
     * @param Closure|string $filter
     * @param mixed|null $args
     * @return FilteringResult|null
     */
    public function applyFilter($filter, $args = null)
    {
        $args = func_get_args();
        $args[0] = $this;
        return call_user_func_array(
            $filter instanceof Closure ? $filter : $this->getConnection()->getFilterCallback($filter),
            $args
        );
    }



    /**
     * @param array|null $args
     * @return self
     */
    public function createSelect($args = null)
    {
        return call_user_func_array([$this->getConnection(), 'select'], func_get_args());
    }



    /**
     * Exports current state
     *
     * @param string|null $clause
     * @return array
     */
    public function _export(string $clause = null, array $args = []): array
    {
        $args = func_get_args();

        $reflector = new \ReflectionClass(get_class($this));
        $parent = $reflector->getParentClass();
        $method = $parent->getMethod('_export');
        $method->setAccessible(true);
        return $method->invokeArgs($this, $args);
    }



    /**
     * @return array|null
     */
    public function getRelatedKeys()
    {
        return $this->relatedKeys;
    }



    /**
     * @param array|null $keys
     * @return self
     * @throws InvalidArgumentException
     */
    public function setRelatedKeys($keys)
    {
        if (!is_array($keys) and $keys !== null) {
            throw new InvalidArgumentException('Invalid related keys given. Expected array or null, ' . gettype($keys) . ' given.');
        }
        $this->relatedKeys = $keys;
        return $this;
    }

}
