<?php
/**
* 2007-2023 PrestaShop
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
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once (dirname(__FILE__).'/src/installer.php');
require_once (dirname(__FILE__).'/src/productDefault.php');
require_once (dirname(__FILE__).'/classes/repository/cart_rule.php');
require_once (dirname(__FILE__).'/classes/pxgiftvoucherhistory.php');
require_once (dirname(__FILE__).'/classes/pxgiftvouchercart.php');
require_once (dirname(__FILE__).'/classes/giftlistProductCartPro.php');
require_once (dirname(__FILE__).'/classes/repository/productsGift.php');
require_once (dirname(__FILE__).'/classes/actions/giftVoucherCancel.php');
require_once (dirname(__FILE__).'/classes/actions/giftVoucherCreator.php');
require_once (dirname(__FILE__).'/classes/help/HelperTools.php');




use pxgiftvoucher\src\installer\installer;
use pxgiftvoucher\classes\actions\giftVoucherCreator;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Entity\Order;
use PrestaShop\PrestaShop\Adapter\Entity\Cart;
use PrestaShop\PrestaShop\Adapter\Entity\OrderState;
use \PrestaShop\PrestaShop\Adapter\Entity\CartRule;





if (!defined('_PS_VERSION_')) {
    exit;
}

class Pxgiftvoucher extends Module implements WidgetInterface
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'pxgiftvoucher';
        $this->tab = 'advertising_marketing';
        $this->version = '1.0.0';
        $this->author = 'Pixefora';
        $this->need_instance = 0;
        $this->bootstrap = true;

        $this->tabs = [
            [
                'class_name' => 'AdminPxGiftVoucher',
                'visible' => true,
                'name' => 'Manage Gift Voucher',
                'parent_class_name' => 'AdminCatalog'
            ],
            [
                'class_name' => 'AdminPxGiftVoucherCart',
                'visible' => true,
                'name' => 'Manage Gift Cart Register',
                'parent_class_name' => 'AdminCatalog'
            ],

        ];

        parent::__construct();

        $this->displayName = $this->l('Tarjetas regalo');
        $this->description = $this->l('Permite crear tarjetas regalo en base a compras de clientes');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        $myInstaller = new installer($this);


        return parent::install() && $myInstaller->install();
    }

    public function uninstall()
    {
        Configuration::deleteByName('PXGIFTVOUCHER_PREFIX');
        Configuration::deleteByName('PXGIFTVOUCHER_ID_PRODUCT');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {



        $this->hookActionOrderStatusUpdate(['newOrderStatus' => new \OrderState(2), 'id_order' => 1134]);
         //Tools::redirect($this->context->link->getAdminLink('AdminPxGiftVoucher'));
         $message = '';

         if (((bool)Tools::isSubmit('submitPxgiftvoucherModule')) == true) {
             $this->postProcess();
             $message = 'Form Updated';
         }



         $this->context->smarty->assign('form', $this->renderForm());
         $this->context->smarty->assign('message', $message);

         $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

         return $output;
     }

     /**
      * Create the form that will be displayed in the configuration of your module.
      */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitPxgiftvoucherModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
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
        $OrderState = new OrderState();
        $OrderStateList = $OrderState->getOrderStates($this->context->language);



        return [
            'form' => [
                'legend' => [
                'title' => $this->l('Settings Gift Voucher'),
                'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Enter a valid id Product'),
                        'name' => 'PXGIFTVOUCHER_ID_PRODUCT',
                        'label' => $this->l('Id Product'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Prefix FROM VOUCHER'),
                        'name' => 'PXGIFTVOUCHER_PREFIX',
                        'label' => $this->l('Prefix'),
                        'lang' => true
                    ],
                    [
                        'type' => 'select',
                        'label' =>  $this->l('Status to Generate Voucher'),
                        'name' => 'PXGIFTVOUCHER_ID_STATUS_GENERATE',
                        'lang' => false,
                        'required' => true,
                        'options' => [
                            'query' => $OrderStateList,
                            'id' => 'id_order_state',
                            'name' => 'name'
                        ]
                    ],
                    [
                        'type' => 'select',
                        'label' =>  $this->l('Status to Cancel Voucher'),
                        'name' => 'PXGIFTVOUCHER_ID_STATUS_CANCEL',
                        'lang' => false,
                        'required' => true,
                        'options' => [
                            'query' => $OrderStateList,
                            'id' => 'id_order_state',
                            'name' => 'name'
                        ]
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {

        $languages = Language::getLanguages(false);




        $values = [
            'PXGIFTVOUCHER_ID_PRODUCT' => Configuration::get('PXGIFTVOUCHER_ID_PRODUCT'),
            'PXGIFTVOUCHER_ID_STATUS_CANCEL' => Configuration::get('PXGIFTVOUCHER_ID_STATUS_CANCEL'),
            'PXGIFTVOUCHER_ID_STATUS_GENERATE' => Configuration::get('PXGIFTVOUCHER_ID_STATUS_GENERATE')
        ];

        foreach ($languages as $lang)   {

                $values['PXGIFTVOUCHER_PREFIX'][$lang['id_lang']] = Configuration::get('PXGIFTVOUCHER_PREFIX',$lang['id_lang']);

        }


        return $values;
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        $languages = Language::getLanguages(false);
        $multilingual = [];

        foreach ($languages as $lang) {
            if (Tools::isSubmit('PXGIFTVOUCHER_PREFIX_' . $lang['id_lang'])) {

                $multilingual[ $lang['id_lang'] ] = Tools::getValue('PXGIFTVOUCHER_PREFIX_' . $lang['id_lang']);


            }
        }
         Configuration::updateValue('PXGIFTVOUCHER_PREFIX', $multilingual);

        foreach (array_keys($form_values) as $key) {

            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');

        return '
        <style>
            .item_145{
                width: 25% !important;
            }
            .item_145 div{
                width:100% !important;
                max-width:100% !important;
                text-align:center;
            }
            .item_145 div button{
                float:none !important;
            }
        </style>
        ';
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {


          /*  $variables = $this->getWidgetVariables($hookName, $configuration);

            $this->context->smarty->assign($variables);

            return $this->context->smarty->fetch($this->local_path.'views/templates/hook/diplayVoucherGiftList.tpl');*/

    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $products = $this->getProducts();

        if (!empty($products)) {
            return array(
                'products' => $products
            );
        }
        return false;
    }



    public function hookActionOrderStatusUpdate($params){


        $newOrderStatus = $params['newOrderStatus']->id;
        $id_order = $params['id_order'];


        $voucherClasses = [
            (int)Configuration::get('PXGIFTVOUCHER_ID_STATUS_CANCEL') => 'giftVoucherCancel',
            (int)Configuration::get('PXGIFTVOUCHER_ID_STATUS_GENERATE') => 'giftVoucherCreator'
        ];

        $order = new Order((int) $id_order);
        $id_cart = $order->id_cart;
        $cart = new Cart($id_cart);
        $id_buyed_to = giftlistProductCartPro::getCustomerFromCart($cart);
        $pxgiftvouchercart = new pxgiftvouchercart();


        if (isset($voucherClasses[$newOrderStatus]) && $pxgiftvouchercart->haveGiftProduct($cart,$id_buyed_to)){

            if ((int)Configuration::get('PXGIFTVOUCHER_ID_STATUS_GENERATE') == $newOrderStatus ) {

                $giftVoucherCreator = new giftVoucherCreator(
                    new pxgiftvoucherhistory(),
                    new CartRule(),
                    $cart,
                    $id_buyed_to,
                    $pxgiftvouchercart
                );

               $giftVoucherCreator->doit();
            }

        }

    }



}

