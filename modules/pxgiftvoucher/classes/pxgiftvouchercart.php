<?php

class pxgiftvouchercart extends ObjectModel
{
    public $id_cart;
    public $id_buyed_to;
    public $id_product;
    public $id_attribute;
    public $date_add;

    public static $definition = [
        'table' => 'pxgiftvoucher_cart',
        'primary' => 'id_pxgiftvoucher_cart',
        'multilang' => false,
        'multilang_shop' => false,
        'fields' => [
            'id_cart' =>   ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'id_buyed_to' =>   ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'id_product' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'id_attribute' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'date_add' => ['type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDateFormat','required' => true ]
        ]

    ];



    public function haveGiftProduct(Cart $cart, int $id_buyed_to ){

        $products = $cart->getProducts();
        $found = false;

        foreach ($products as $product){

            if ($product['id_product'] == (int)Configuration::get('PXGIFTVOUCHER_ID_PRODUCT')){
                $found = true;
                $this->reset($cart,$id_buyed_to);
            }

        }

        return $found;

    }

    public function getPriceFromGiftProduct(Cart $cart){

        $products = $cart->getProducts();
        $total = 0;

        foreach ($products as $product){

            if ($product['id_product'] == (int)Configuration::get('PXGIFTVOUCHER_ID_PRODUCT')){

                $quantity = $product['cart_quantity'];
                $price = $product['price_attribute'];
                $total+= $price * $quantity;
            }

        }

        return $price;


    }


    private function reset(Cart $cart, int $id_buyed_to){

        $id_product = (int)Configuration::get('PXGIFTVOUCHER_ID_PRODUCT');

        $sql = "DELETE FROM "._DB_PREFIX_."pxgiftvoucher_cart WHERE 
        id_cart = ".$cart->id." 
        AND id_product = ".$id_product."
        AND id_buyed_to = ".$id_buyed_to."
        ";

        Db::getInstance()->execute($sql);

    }


}
