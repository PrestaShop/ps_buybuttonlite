<?php
/**
 * 2007-2018 PrestaShop.
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

        $idProduct = (int) Tools::getValue('id_product');
        $idProductAttribute = (int) Tools::getValue('id_product_attribute');
        $action = (int) Tools::getValue('action');

        switch ($action) {
            case self::REDIRECT_TO_CART:
                $this->redirectToCart($idProduct, $idProductAttribute);

                break;
            case self::REDIRECT_TO_CHECKOUT:
                $this->redirectToCheckout($idProduct, $idProductAttribute);

                break;
            default:
                $this->redirectToCart($idProduct, $idProductAttribute);

                break;
        }
    }

    /**
     * Redirect to the cart with the product.
     *
     * @param int $idProduct id of the product to add in the cart
     * @param int $idProductAttribute id of the product attribute if the product is a combination
     *
     * @return none Redirect to the cart
     */
    public function redirectToCart($idProduct, $idProductAttribute = null)
    {
        Tools::redirect('index.php?controller=cart&update=1&id_product=' . $idProduct . '&id_product_attribute=' . $idProductAttribute);
    }

    /**
     * Redirect to the checkout page with the product.
     *
     * @param int $idProduct id of the product to add in the cart
     * @param int $idProductAttribute id of the product attribute if the product is a combination
     *
     * @return none Redirect to the checkout
     */
    public function redirectToCheckout($idProduct, $idProductAttribute = null)
    {
        if (Validate::isLoadedObject($this->context->cart)) {
            $this->context->cart->updateQty(1, $idProduct, $idProductAttribute);
        } else {
            $cart = new Cart();
            $cart->id_currency = $this->context->currency->id;
            $cart->id_lang = $this->context->language->id;
            $cart->save();

            $this->context->cart = $cart;
            $this->context->cart->updateQty(1, $idProduct, $idProductAttribute);
            $this->context->cookie->id_cart = $cart->id;
        }

        Tools::redirect('index.php?controller=order');
    }
}
