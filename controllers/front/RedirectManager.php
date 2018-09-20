<?php
/**
* 2007-2018 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2018 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/

class ps_buybuttonliteRedirectManagerModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $id_product = (int)Tools::getValue('id_product');
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        $action = (int)Tools::getValue('action');


        switch ($action) {
            case 1:
                Tools::redirect('index.php?controller=cart&update=1&id_product='.$id_product.'&id_product_attribute='.$id_product_attribute);
                break;
            case 2:
                $cart = $this->context->cart;
                if (!Validate::isLoadedObject($cart)) {
                    Tools::redirect('index');
                }

                $cart->updateQty(1, $id_product, $id_product_attribute);
                $cart->save();

                Tools::redirect('index.php?controller=order');
                break;
            default:
                Tools::redirect('index.php?controller=cart&update=1&id_product='.$id_product.'&id_product_attribute='.$id_product_attribute);
                break;
        }
    }

    /**
     * Check if product exist
     *
     * @param int $id_product Id product
     * @param int $id_product_attribute id product attribute
     *
     * @return bool
     */
    public function checkParams($id_product, $id_product_attribute = null)
    {
        // TO DO : check if product exist, if it is active and check quantity available
    }
}
