<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Laminas\Stdlib\Hydrator;

use Laminas\Stdlib\Extractor\ExtractionInterface;
use Laminas\Hydrator\HydratorInterface as BaseHydratorInterface;

/**
 * @deprecated Use Laminas\Hydrator\HydratorInterface from laminas/laminas-hydrator instead.
 */
interface HydratorInterface extends BaseHydratorInterface, HydrationInterface, ExtractionInterface
{
}
