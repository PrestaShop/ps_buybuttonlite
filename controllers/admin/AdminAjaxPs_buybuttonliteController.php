<?php
/**
* 2007-2016 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/

class AdminAjaxPs_buybuttonliteController extends ModuleAdminController
{
    public function ajaxProcessSearchProducts()
    {
        $context = Context::getContext();
        $id_lang = $context->language->id;
        $id_shop = $context->shop->id;
        $query = pSQL(Tools::getValue('product_search'));

        $sql = new DbQuery();
        $sql->select('p.`id_product`, pa.`id_product_attribute`, pl.`name`, p.`reference`');
        $sql->from('product', 'p');
        $sql->join(Shop::addSqlAssociation('product', 'p'));
        $sql->leftJoin('product_lang', 'pl', '
            p.`id_product` = pl.`id_product`
            AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('pl')
        );
        $sql->leftJoin('product_attribute', 'pa', 'pa.`id_product` = p.`id_product`');
        $where = 'pl.`name` LIKE \'%' . pSQL($query) . '%\'
        OR p.`reference` LIKE \'%' . pSQL($query) . '%\'
        OR EXISTS(SELECT * FROM `' . _DB_PREFIX_ . 'product_supplier` sp WHERE sp.`id_product` = p.`id_product` AND `product_supplier_reference` LIKE \'%' . pSQL($query) . '%\')';
        $sql->orderBy('pl.`name` ASC');
        if (Combination::isFeatureActive()) {
            $where .= ' OR EXISTS(SELECT * FROM `' . _DB_PREFIX_ . 'product_attribute` `pa` WHERE pa.`id_product` = p.`id_product` AND (pa.`reference` LIKE \'%' . pSQL($query) . '%\'))';
        }
        $sql->where($where);
        $result = Db::getInstance()->executeS($sql);
        if (!$result) {
            return false;
        }
        $results_array = array();
        foreach ($result as $row) {
            if ($this->hasCombinations($row['id_product'])) {
                $row['attribute_name'] = $this->getAttributes($row['id_product_attribute'], $id_lang);
                $row['image_link'] = $this->getProductAttributeImage($row['id_product'], $row['id_product_attribute']);
            } else {
                $row['image_link'] = $this->getProductImage($row['id_product']);
            }
            $results_array[] = $row;
        }
        $this->content = json_encode($results_array);
    }

    public function getProductImage($id_product)
    {
        $product = new Product($id_product);
        $link_rewrite = $product->link_rewrite;
        $link = new Link();
        $id_image = Image::getCover($id_product);
        $id_image = $id_image['id_image'];

        $image_link = $link->getImageLink($link_rewrite, $id_image, ImageType::getFormattedName('small'));

        return Tools::getProtocol().$image_link;
    }

    public function getProductAttributeImage($id_product, $id_product_attribute)
    {
        $context = Context::getContext();
        $id_lang = $context->language->id;
        $id_shop = $context->shop->id;

        $product = new Product($id_product);
        $link_rewrite = $product->link_rewrite;
        $link = new Link();
        $id_image = Image::getBestImageAttribute($id_shop, $id_lang, $id_product, $id_product_attribute);
        $id_image = $id_image['id_image'];

        $image_link = $link->getImageLink($link_rewrite, $id_image, ImageType::getFormattedName('small'));

        return Tools::getProtocol().$image_link;
    }

    public function hasCombinations($id_product)
    {
        if (is_null($id_product) || 0 >= $id_product) {
            return false;
        }
        $attributes = Product::getAttributesInformationsByProduct($id_product);
        return !empty($attributes);
    }

    public function getAttributes($id_product_attribute, $id_lang)
    {
        $sql = 'SELECT agl.name as label, al.name as value
            FROM ' . _DB_PREFIX_ . 'product_attribute_combination pac
            LEFT JOIN ' . _DB_PREFIX_ . 'attribute a ON (a.id_attribute = pac.id_attribute)
            LEFT JOIN ' . _DB_PREFIX_ . 'attribute_lang al ON (al.id_attribute = a.id_attribute AND al.id_lang=' . (int) $id_lang . ')
            LEFT JOIN ' . _DB_PREFIX_ . 'attribute_group ag ON (ag.id_attribute_group = a.id_attribute_group)
            LEFT JOIN ' . _DB_PREFIX_ . 'attribute_group_lang agl ON (agl.id_attribute_group = ag.id_attribute_group AND agl.id_lang=' . (int) $id_lang . ')
            WHERE pac.id_product_attribute=' . (int) $id_product_attribute;

        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        foreach ($results as $attribute) {
            $attributes[] = implode($attribute, ' - ');
        }
        $attributesList = implode($attributes, ', ');

        return $attributesList;
    }
}
