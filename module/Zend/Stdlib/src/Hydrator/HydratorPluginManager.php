<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Laminas\Stdlib\Hydrator;

use Laminas\Hydrator\HydratorPluginManager as BaseHydratorPluginManager;

/**
 * Plugin manager implementation for hydrators.
 *
 * Enforces that adapters retrieved are instances of HydratorInterface
 *
 * @deprecated Use Laminas\Hydrator\HydratorPluginManager from laminas/laminas-hydrator instead.
 */
class HydratorPluginManager extends BaseHydratorPluginManager
{
    /**
     * Default aliases
     *
     * @var array
     */
    protected $aliases = [
        'delegatinghydrator' => 'Laminas\Stdlib\Hydrator\DelegatingHydrator',
    ];

    /**
     * Default set of adapters
     *
     * @var array
     */
    protected $invokableClasses = [
        'arrayserializable' => 'Laminas\Stdlib\Hydrator\ArraySerializable',
        'classmethods'      => 'Laminas\Stdlib\Hydrator\ClassMethods',
        'objectproperty'    => 'Laminas\Stdlib\Hydrator\ObjectProperty',
        'reflection'        => 'Laminas\Stdlib\Hydrator\Reflection'
    ];

    /**
     * Default factory-based adapters
     *
     * @var array
     */
    protected $factories = [
        'Laminas\Stdlib\Hydrator\DelegatingHydrator' => 'Laminas\Stdlib\Hydrator\DelegatingHydratorFactory',
        'zendstdlibhydratordelegatinghydrator'    => 'Laminas\Stdlib\Hydrator\DelegatingHydratorFactory',
    ];
}
