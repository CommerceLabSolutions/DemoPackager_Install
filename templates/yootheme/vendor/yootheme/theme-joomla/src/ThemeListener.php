<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Document\Document;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Language;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Input\Input;
use YOOtheme\Config;
use YOOtheme\Event;
use YOOtheme\Metadata;
use YOOtheme\Url;

class ThemeListener
{
    public static function initTheme(Config $config)
    {
        $class = new \ReflectionClass(BaseController::class);

        // override views cache array
        if ($config('app.isSite') && $class->hasProperty('views')) {
            $views = $class->getProperty('views');
            $views->setAccessible(true);
            $views->setValue(new ViewsObject());
        }
    }

    public static function initHead(Metadata $metadata)
    {
        if (version_compare(JVERSION, '4.0', '<')) {
            return;
        }

        $metadata->set('style:fontawesome', [
            'href' => Url::to('~/media/system/css/joomla-fontawesome.min.css'),
            'rel' => 'preload',
            'as' => 'style',
            'onload' => 'this.onload=null;this.rel=\'stylesheet\'',
        ]);
    }

    public static function loadTemplate(Config $config, $event)
    {
        list($view) = $event->getArguments();

        $context = $view->get('context');
        $layout = $view->getLayout();

        if (in_array($context, ['com_content.category', 'com_content.featured', 'com_tags.tag'])) {
            $config->set('~theme.page_layout', 'blog');
        }

        if ($context === 'com_content.article' && $layout === 'default') {
            $item = $view->get('item');

            if ($item->category_alias !== 'uncategorised') {
                $config->set('~theme.page_layout', 'post');
            }
        }

        // Joomla 4 does not distribute com_search
        if (!ComponentHelper::isEnabled('com_search')) {
            $config->set('~theme.search_module', 'mod_finder');
        }
    }

    public static function afterDispatch(
        Config $config,
        Document $document,
        Input $input,
        Language $language,
        CMSApplication $cms
    ) {
        // is template active?
        if (
            !$config('~theme') ||
            $config('app.isAdmin') ||
            $input->getCmd('option') === 'com_ajax' ||
            $input->getCmd('tmpl') === 'component'
        ) {
            return;
        }

        $itemId = ($item = $cms->getMenu()->getDefault()) ? $item->id : 0;
        $siteUrl = Route::_("index.php?Itemid={$itemId}", false, 0, true);

        $language->load('tpl_yootheme', $config('theme.rootDir'));
        $document->setBase(htmlspecialchars(Uri::current()));

        $config->add('~theme', [
            'site_url' => $siteUrl,
            'direction' => $document->getDirection(),
            'page_class' => $cms->getParams()->get('pageclass_sfx'),
        ]);

        if (PluginHelper::isEnabled('content', 'emailcloak')) {
            static::fixEmailCloak($document);
        }

        if (($custom = $config('~theme.custom_js', '')) && $document instanceof HtmlDocument) {
            static::addCustomScript($document, $custom);
        }

        if ($config('~theme.jquery') || str_contains($custom, 'jQuery')) {
            HTMLHelper::_('jquery.framework');
        }

        Event::emit('theme.head');
    }

    protected static function fixEmailCloak(Document $document)
    {
        $document->addScriptDeclaration("document.addEventListener('DOMContentLoaded', function() {
            Array.prototype.slice.call(document.querySelectorAll('a span[id^=\"cloak\"]')).forEach(function(span) {
                span.innerText = span.textContent;
            });
        });");
    }

    protected static function addCustomScript(HtmlDocument $document, $script)
    {
        if (stripos(trim($script), '<script') !== 0) {
            $script = "<script>{$script}</script>";
        }

        $document->addCustomTag($script);
    }
}
