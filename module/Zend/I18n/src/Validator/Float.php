<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Laminas\I18n\Validator;

/**
 * Stub class for backwards compatibility.
 *
 * Since PHP 7 adds "float" as a reserved keyword, we can no longer have a class
 * named that and retain PHP 7 compatibility. The original class has been
 * renamed to "IsFloat", and this class is now an extension of it. It raises an
 * E_USER_DEPRECATED to warn users to migrate.
 *
 * @deprecated
 */
class Float extends IsFloat
{
    /**
     * Constructor for the integer validator
     *
     * @param array|Traversable $options
     * @throws Exception\ExtensionNotLoadedException if ext/intl is not present
     */
    public function __construct($options = [])
    {
        trigger_error(
            sprintf(
                'The class %s has been deprecated; please use %s\\IsFloat',
                __CLASS__,
                __NAMESPACE__
            ),
            E_USER_DEPRECATED
        );

        parent::__construct($options);
    }
}
