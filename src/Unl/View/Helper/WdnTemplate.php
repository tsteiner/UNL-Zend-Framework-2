<?php

namespace Unl\View\Helper;

use Zend\Stdlib\ArrayObject;
use Zend\View\Helper\AbstractHelper;

/**
* This view helper can be used to create a layout view script based on the WDN Template.
*/
class WdnTemplate extends AbstractHelper
{
    /**
     * @var string
     * The name of the dreamweaver template to use
     */
    protected $template = 'Local';

    /**
     * @var array
     * A list of CSS classes to be added to the <body> tag
     */
    protected $bodyClasses = array();

    /**
     * @var array
     * An array of options that will be passed to UNL_Templates::$options
     */
    protected $options = array();

    /**
     * @var string
     * URI users should be sent to when logging in.
     */
    protected $loginUri;

    /**
     * @var string
     * URI users should be sent to when logging out.
     */
    protected $logoutUri;

    /**
     * @var string
     */
    protected $googleAnalyticsId;

    public function __toString()
    {
        $layout = new ArrayObject();
        $this->getView()->doctype('XHTML1_TRANSITIONAL');

        $baseUrl = $this->getView()->basePath();

        $this->getView()->headLink(array(
            'rel'   => 'home',
            'href'  => $this->getView()->basePath(),
            'title' => $this->getView()->placeholder('Site Title'))
        );

        \UNL_Templates::setCachingService(new \UNL_Templates_CachingService_Null());
        \UNL_Templates::$options['version'] = \UNL_Templates::VERSION3x1;
        \UNL_Templates::$options = array_merge(\UNL_Templates::$options, $this->options);

        $template = \UNL_Templates::factory($this->template, array('sharedcodepath' => 'sharedcode'));

        if (in_array(\UNL_Templates::$options['version'], array(\UNL_Templates::VERSION3x1, '3x1'))) {
            $template->titlegraphic = $this->getView()->placeholder('Site Title');
            $template->pagetitle = '<h1>' . $this->getView()->placeholder('Page Title') . '</h1>';
        } else {
            $template->titlegraphic = '<h1>' . $this->getView()->placeholder('Site Title') . '</h1>';
            $template->pagetitle = '<h2>' . $this->getView()->placeholder('Page Title') . '</h2>';
        }


        $template->navlinks = $this->getView()->navigation('navigation')->menu()->render();
        $template->maincontentarea = $this->getView()->content . PHP_EOL;
        $template->head .= PHP_EOL
                         . $this->getView()->headLink() . PHP_EOL
                         . $this->getView()->headMeta() . PHP_EOL
                         . $this->getView()->headScript() . PHP_EOL
                         . $this->getView()->headStyle() . PHP_EOL
                         . '<script type="text/javascript">' . PHP_EOL
                         . "WDN.jQuery('html').data('baseUrl', '" . $this->getView()->basePath() . "');" . PHP_EOL
                         . "WDN.jQuery(function() {WDN.jQuery('body').data('baseUrl', '" . $this->getView()->basePath() . "');});" . PHP_EOL
                         . '</script>' . PHP_EOL
                         ;
        $template->loadSharedCodeFiles();
        $template->breadcrumbs = $this->getView()
                                      ->navigation('navigation')
                                      ->breadcrumbs()
                                      ->setMinDepth(0)
                                      ->setPartial('wdn/breadcrumbs')
                                      ->render();

        $htmlTitle = array(
            'UNL',
            trim($this->getView()->placeholder('Site Title')),
            trim($this->getView()->placeholder('Page Title')),
        );
        $htmlTitle = array_filter($htmlTitle);
        if (in_array(\UNL_Templates::$options['version'], array(\UNL_Templates::VERSION3x1, '3x1'))) {
            $template->doctitle = '<title>' . implode(' | ', array_reverse($htmlTitle)) . '</title>';
        } else {
            $template->doctitle = '<title>' . implode(' | ', $htmlTitle) . '</title>';
        }

        if (!$layout->leftColLinks) {
            $layout->leftColLinks = array();
        }


        $template->leftcollinks = '<h3>Related Links</h3>'
                                . $this->getView()
                                       ->navigation('related')
                                       ->menu()
                                       ->render();
        $template->contactinfo = '<h3>Contact Us</h3>' . $this->getView()->contactinfo;

        $template->optionalfooter = $this->getView()->optionalfooter;
        $template->footercontent = ''
            . $this->getView()->footercontent . PHP_EOL
            . '<script type="text/javascript">' . PHP_EOL
            . (isset($this->loginUri) ? "WDN.idm.setLoginURL('{$this->loginUri}');" . PHP_EOL : '')
            . (isset($this->logoutUri) ? "WDN.idm.setLogoutURL('{$this->logoutUri}');" . PHP_EOL : '');

        if ($this->googleAnalyticsId) {
            $gaId = $this->googleAnalyticsId;
            $template->footercontent .= <<<EOF
var _gaq = _gaq || [];
_gaq.push(['_setAccount', '$gaId']); //replace with your unique tracker id
_gaq.push(['_setDomainName', '.unl.edu']);
_gaq.push(['_setAllowLinker', true]);
_gaq.push(['_setAllowHash', false]);
_gaq.push(['_trackPageview']);

EOF;
        }
        $template->footercontent .= '</script>' . PHP_EOL;
        $template->footercontent .= $this->getView()->inlineScript();

        foreach ($this->bodyClasses as $bodyClass) {
            $template->__params['class']['value'] .= " $bodyClass";
        }

        return $template->toHtml();
    }


    /**
     * Transforms path-relative URLs into site-relative URLs.
     * All other URLs are returned unmodified.
     * @param string $url
     * @return string
     */
    protected function _processUrl($url)
    {
        $parts = parse_url($url);
        if (isset($parts['host'])) {
            return $url;
        }

        if (substr($parts['path'], 0, 1) == '/') {
            return $url;
        }

        return $this->getView()->basePath($url);
    }

    /**
     * Set the name of the Dreamweaver template to render
     * @param $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * Set a list of CSS classes to be added to the body tag
     * @param array $classes
     */
    public function setBodyClasses(array $classes)
    {
        $this->bodyClasses = $classes;
    }

    /**
     * Add a CSS class to the body tag
     * @param $class
     */
    public function addBodyClass($class)
    {
        $this->bodyClasses[] = $class;
    }

    /**
     * Set the options that will be passed to UNL_Templates::$options
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * Set an option that will be passed to UNL_Templates::$options
     * @param string $key
     * @param mixed $value
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
    }

    public function setLoginUri($uri)
    {
        $this->loginUri = $uri;
    }

    public function setLogoutUri($uri)
    {
        $this->logoutUri = $uri;
    }

    public function setGoogleAnalyticsId($id)
    {
        $this->googleAnalyticsId = $id;
    }
}
