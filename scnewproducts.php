<?php

/**
 * 2007-2021 PrestaShop
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
 *  @copyright 2007-2021 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

use ProductAssembler;
use ProductPresenterFactory;
use PrestaShop\PrestaShop\Adapter\Entity\Language;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Scnewproducts extends Module implements WidgetInterface
{
    protected $config_form = false;
    private $templateFile;

    public function __construct()
    {
        $this->name = 'scnewproducts';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'SamiCode';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('New Producs Lists');
        $this->description = $this->l('Lists new products on home page in a slide');

        $this->confirmUninstall = $this->l('are you sure to uninstall ?');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:scnewproducts/views/templates/hook/scnewproducts.tpl';
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        $this->_clearCache('*');


        Configuration::updateValue('SCNEWPRODUCTS_HOME_NBR', 8);
        Configuration::updateValue('SCNEWPRODUCTS_HOME_TITLE', 'New Products');

        return parent::install()
            && $this->registerHook('addproduct')
            && $this->registerHook('updateproduct')
            && $this->registerHook('deleteproduct')
            && $this->registerHook('categoryUpdate')
            && $this->registerHook('displayHome')
            && $this->registerHook('actionAdminGroupsControllerSaveAfter');
    }

    public function uninstall()
    {
        $this->_clearCache('*');

        return parent::uninstall();
    }

    public function hookAddProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookUpdateProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookDeleteProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookCategoryUpdate($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionAdminGroupsControllerSaveAfter($params)
    {
        $this->_clearCache('*');
    }

    public function _clearCache($template, $cache_id = null, $compile_id = null)
    {
        parent::_clearCache($this->templateFile);
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $output = '';

        $errors = array();

        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitScnewproductsModule')) == true) {
            $nbr = Tools::getValue('SCNEWPRODUCTS_HOME_NBR');
            if ((!Validate::isInt($nbr) || $nbr <= 0)) {
                $errors[] = $this->trans('The number of products is invalid. Please enter a positive number.', array(), 'Modules.NewProducts.Admin');
            }

            $title = Tools::getValue('SCNEWPRODUCTS_HOME_TITLE');
            if ((!Validate::isString($title) || strlen($title) <= 0)) {
                $errors[] = $this->trans('Title field is required', array(), 'Modules.NewProducts.Admin');
            }

            if (isset($errors) && count($errors)) {
                $output = $this->displayError(implode('</br>', $errors));
            } else {
                Configuration::updateValue('SCNEWPRODUCTS_HOME_NBR', (int) $nbr);
                Configuration::updateValue('SCNEWPRODUCTS_HOME_TITLE', (int) $title);

                $this->_clearCache('*');

                $output = $this->displayConfirmation($this->trans('The settings have been update.', array(), 'Modules.NewProducts.Admin'));
            }
        }

        return $output . $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    public  function renderForm()
    {

        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitScnewproductsModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Number of products to be displayed', array(), 'Modules.NewProducts.Admin'),
                        'name' => 'SCNEWPRODUCTS_HOME_NBR',
                        'class' => 'fixed-width-xs',
                        'desc' => $this->trans('Set the number of products that you would like to display to slide on homepage (default: 8).', array(), 'Modules.NewProducts.Admin'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Title for section', array(), 'Modules.NewProducts.Admin'),
                        'name' => 'SCNEWPRODUCTS_HOME_TITLE',
                        'desc' => $this->trans('Set the title of section that you would like to display to slide on homepage (default: New Products).', array(), 'Modules.NewProducts.Admin'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'SCNEWPRODUCTS_HOME_NBR' => Configuration::get('SCNEWPRODUCTS_HOME_NBR'),
            'SCNEWPRODUCTS_HOME_TITLE' => Configuration::get('SCNEWPRODUCTS_HOME_TITLE'),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }




    public function renderWidget($hookName, array $configuration)
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId('scnewproducts'))) {
            $variables = $this->getWidgetVariables($hookName, $configuration);

            if (empty($variables)) {
                return false;
            }
            $this->smarty->assign($variables);
        }
        return $this->fetch($this->templateFile, $this->getCacheId('scnewproducts'));
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        $products = $this->getProducts();

        if (!empty($products)) {
            return array(
                'products' => $products,
            );
        }

        return false;
    }


    protected function getProducts()
    {
        $category = new Category(Context::getContext()->shop->getCategory(), (int)Context::getContext()->language->id);

        $searchProvider = new CategoryProductSearchProvider(
            $this->context->getTranslator(),
            $category
        );

        $context = new ProductSearchContext($this->context);

        $query = new ProductSearchQuery();

        $nbrProduct = (int)Configuration::get('SCNEWPRODUCTS_HOME_NBR');
        if ($nbrProduct <= 0) {
            $nbrProduct = 4;
        }

        $query
            ->setResultsPerPage($nbrProduct)
            ->setPage(1);

        $query->setSortOrder(new SortOrder('product', 'date_add', 'desc'));

        $result = $searchProvider->runQuery(
            $context,
            $query
        );

        $assembler = new ProductAssembler($this->context);


        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = $presenterFactory->getPresenter();

        $product_for_template = [];

        foreach ($result->getProducts() as $rawProduct) {
            $product_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }

        return $product_for_template;
    }
}