<?php
/**
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Ps_BuybuttonLite extends Module
{
    public function __construct()
    {
        $this->name = 'ps_buybuttonlite';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'PrestaShop';
        $this->need_instance = 0;

        $this->module_key = 'bab535c26e031e9d224c0d128e063283';

        $this->bootstrap = true;

        parent::__construct();

        $this->controllerAdmin = 'AdminAjaxPs_buybuttonlite';

        $this->displayName = $this->trans('Buy button lite', array(), 'Modules.Buybuttonlite.Admin');
        $this->description = $this->trans('Create a product checkout redirect link to post on a blog or any social media', array(), 'Modules.Buybuttonlite.Admin');
        $this->ps_version = (bool)version_compare(_PS_VERSION_, '1.7', '>=');

        // Settings paths
        $this->css_path = $this->_path.'views/css/';

        // Confirm uninstall
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall this module?', array(), 'Modules.Buybuttonlite.Admin');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * install()
     *
     * @param none
     * @return bool
     */
    public function install()
    {
        if (parent::install() &&
            $this->installTab()) {
                return true;
        }
        return false;
    }

    /**
     * uninstall()
     *
     * @param none
     * @return bool
     */
    public function uninstall()
    {
        if (parent::uninstall() &&
            $this->uninstallTab()) {
            return true;
        }
        return false;
    }

    /**
     * Register admin controller (ajax call)
     *
     * @param none
     * @return bool
     */
    public function installTab()
    {
        $tab = new Tab();
        $tab->class_name = $this->controllerAdmin;
        $tab->active = 1;
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->name;
        }
        $tab->id_parent = -1;
        $tab->module = $this->name;

        return $tab->add();
    }

    /**
     * Unregister admin controller
     *
     * @param none
     * @return bool
     */
    public function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName($this->controllerAdmin);
        $tab = new Tab($id_tab);
        if (Validate::isLoadedObject($tab)) {
            return ($tab->delete());
        } else {
            return false;
        }
    }

    /**
     * Load dependencies
     *
     * @param none
     * @return bool
     */
    public function loadAsset()
    {
        $css = array(
            'https://unpkg.com/element-ui/lib/theme-chalk/index.css',
            $this->css_path.'override-element-ui.css',
            $this->css_path.'back.css',
        );

        return $this->context->controller->addCSS($css, 'all');
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $this->loadAsset();

        $link = new Link();
        $adminAjaxController = $link->getAdminLink($this->controllerAdmin);

        $availableTranckingLanguage = array('en', 'fr', 'es', 'it', 'de', 'nl', 'pt', 'pl', 'ru');

        $iso_code = 'en';
        if (in_array($this->context->language->iso_code, $availableTranckingLanguage)) {
            $iso_code = $this->context->language->iso_code;
        }
        $trackingAddons = 'https://addons.prestashop.com/'.$iso_code.'/blog-forum-actualites/41139-buy-button.html?utm_source=back-office&utm_medium=native-module&utm_campaign=back-office-'.strtoupper($iso_code).'&utm_content=Permalink';

        $confTranslations = array(
            'selectProduct' => $this->trans('Select Product', array(), 'Modules.Buybuttonlite.Admin'),
            'searchProduct' => $this->trans('Search Product', array(), 'Modules.Buybuttonlite.Admin'),
            'action' => $this->trans('Select action', array(), 'Modules.Buybuttonlite.Admin'),
            'sharableLink' => $this->trans('Get sharable link', array(), 'Modules.Buybuttonlite.Admin'),
            'copyToClipboard' => $this->trans('Copy to clipboard', array(), 'Modules.Buybuttonlite.Admin'),
            'linkCopied' => $this->trans('Link copied to clipboard', array(), 'Modules.Buybuttonlite.Admin'),
        );

        $bannerPromoTranslations = array(
            'copyToClipboard' => $this->trans('Copy to clipboard', array(), 'Modules.Buybuttonlite.Admin'),
            'discover' => $this->trans('Discover', array(), 'Modules.Buybuttonlite.Admin'),
            'screenshots' => $this->trans('Screenshots', array(), 'Modules.Buybuttonlite.Admin'),
            'goFurther' => $this->trans('To go further', array(), 'Modules.Buybuttonlite.Admin'),
            'addonsMarketplace' => $this->trans('Addons marketplace', array(), 'Modules.Buybuttonlite.Admin'),
            'discoverOn' => $this->trans('Discover on Addons Marketplace', array(), 'Modules.Buybuttonlite.Admin'),
            'developedBy' => $this->trans('Developed by PrestaShop', array(), 'Modules.Buybuttonlite.Admin')
        );

        Media::addJsDef(array(
            'context' => json_encode(Context::getContext()),
            'confTranslations' => json_encode($confTranslations),
            'bannerPromoTranslations' => json_encode($bannerPromoTranslations),
            'adminAjaxController' => $adminAjaxController,
            'trackingAddonsLink' => $trackingAddons,
            'psBaseUrl' => Tools::getHttpHost(true),
            'psVersion' => _PS_VERSION_
        ));

        $this->context->smarty->assign('modulePath', $this->_path);

        return $this->context->smarty->fetch($this->local_path.'views/templates/admin/app.tpl');
    }
}
