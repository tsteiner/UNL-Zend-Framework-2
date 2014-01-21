<?php
return array(
    'navigation' => array(
        'default' => array(
            /* Example
            'example' => array(
                'label' => 'Example',
                'route' => 'home',
            ),
            */
        ),
        'related' => array(
            /* Example
            'google' => array(
                'label' => 'Google',
                'uri' => 'http://google.com/',
            )
            */
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'related'    => 'Unl\Navigation\Service\RelatedNavigationFactory',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'wdntemplate' => 'Unl\View\Helper\WdnTemplate',
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            'unl' => __DIR__ . '/../view',
        ),
    ),
    'unl' => array(
        'wdntemplate' => array(
            'template' => 'Local',
            'options'  => array(
                'version' => '4',
            ),
        ),
    ),
);
