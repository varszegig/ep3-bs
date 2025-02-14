<?php

return array(
    'router' => array(
        'routes' => array(
            'frontend' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Frontend\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Frontend\Controller\Index' => 'Frontend\Controller\IndexController',
        ),
    ),

    'controller_plugins' => array(
        'invokables' => array(
            'DetermineSquareGroup' => 'Frontend\Controller\Plugin\DetermineSquareGroup',
        ),
    ),

    'view_helpers' => array(
        'factories' => array (
            'FrontendSquareGroupList' => 'Frontend\View\Helper\SquareGroupListFactory',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);