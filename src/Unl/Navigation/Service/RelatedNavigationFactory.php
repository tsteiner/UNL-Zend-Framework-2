<?php

namespace Unl\Navigation\Service;

/**
 * Default navigation factory.
 */
class RelatedNavigationFactory extends \Zend\Navigation\Service\AbstractNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'related';
    }
}
