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
 * Encapsulation of implicit filters
 *
 * @author Vojtěch Kohout
 */
class ImplicitFilters
{

    /** @var array */
    private $filters;

    /** @var array */
    private $targetedArgs;



    /**
     * @param array|string|Closure $filters
     * @param array $targetedArgs
     * @throws InvalidArgumentException
     */
    public function __construct($filters, array $targetedArgs = [])
    {
        if (!is_array($filters)) {
            if (!is_string($filters) and !($filters instanceof Closure)) {
                throw new InvalidArgumentException(
                    "Argument \$filters must contain either string (name of filter), instance of Closure or array (with names of filters or instances of Closure)."
                );
            }
            $filters = [$filters];
        }
        $this->filters = $filters;
        $this->targetedArgs = $targetedArgs;
    }



    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }



    /**
     * @return array
     */
    public function getTargetedArgs()
    {
        return $this->targetedArgs;
    }

}
