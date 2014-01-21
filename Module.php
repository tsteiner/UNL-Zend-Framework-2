<?php
namespace Unl;

use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $config = $e->getApplication()->getConfig();
        $wdnViewHelper = $e->getApplication()
                           ->getServiceManager()
                           ->get('viewHelperManager')
                           ->get('WdnTemplate');
        $view = $e->getApplication()
                  ->getServiceManager()
                  ->get('viewRenderer');

        $wdnViewHelper->setTemplate($config['unl']['wdntemplate']['template']);
        $wdnViewHelper->setOptions($config['unl']['wdntemplate']['options']);
        $wdnViewHelper->setBodyClasses(array());
        $view->placeholder('Site Title')->set('Zend Framework 2 Site');

        $contactinfo = new ViewModel();
        $contactinfo->setTemplate('wdn/contactinfo');
        $e->getViewModel()->addChild($contactinfo, 'contactinfo');

        $footercontent = new ViewModel();
        $footercontent->setTemplate('wdn/footercontent');
        $e->getViewModel()->addChild($footercontent, 'footercontent');

        $optionalfooter = new ViewModel();
        $optionalfooter->setTemplate('wdn/optionalfooter');
        $e->getViewModel()->addChild($optionalfooter, 'optionalfooter');
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        set_include_path(__DIR__ . '/pyrus/php' . PATH_SEPARATOR . get_include_path());
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
                'prefixes' => array(
                    'UNL' => __DIR__ . '/pyrus/php/UNL',
                ),
            ),
        );
    }
}
