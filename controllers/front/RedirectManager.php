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
    const REDIRECT_TO_CART = 1;
    const REDIRECT_TO_CHECKOUT = 2;

    public function initContent()
    {
        parent::initContent();

        $idProduct = (int)Tools::getValue('id_product');
        $idProductAttribute = (int)Tools::getValue('id_product_attribute');
        $action = (int)Tools::getValue('action');

        switch ($action) {
            case self::REDIRECT_TO_CART:
                Tools::redirect('index.php?controller=cart&update=1&id_product='.$idProduct.'&id_product_attribute='.$idProductAttribute);
                break;
            case self::REDIRECT_TO_CHECKOUT:
                $cart = $this->context->cart;
                if (!Validate::isLoadedObject($cart)) {
                    Tools::redirect('index');
                }

                $cart->updateQty(1, $idProduct, $idProductAttribute);
                $cart->save();

                Tools::redirect('index.php?controller=order');
                break;
            default:
                Tools::redirect('index.php?controller=cart&update=1&id_product='.$idProduct.'&id_product_attribute='.$idProductAttribute);
                break;
        }
    }
}
