<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Laminas\Stdlib\Hydrator\Aggregate;

use Laminas\Hydrator\Aggregate\HydratorListener as BaseHydratorListener;

/**
 * Aggregate listener wrapping around a hydrator. Listens
 * to {@see \Laminas\Stdlib\Hydrator\Aggregate::EVENT_HYDRATE} and
 * {@see \Laminas\Stdlib\Hydrator\Aggregate::EVENT_EXTRACT}
 *
 * @deprecated Use Laminas\Hydrator\Aggregate\HydratorListener from laminas/laminas-hydrator instead.
 */
class HydratorListener extends BaseHydratorListener
{
}
