<?php



class giftlistProductCartPro extends ObjectModel
{


    public $id_wishlist;
    public $id_product;
    public $id_product_attribute;
    public $quantity_rel;
    public $quantity;
    public $quantity_init;
    public $quantity_left_rel;
    public $quantity_left;
    public $booked;
    public $priority;
    public $position;
    public $alert_qty;
    public $contribution;
    public $id_pdt_original;

    public static $definition = [
        'table' => 'giftlist_product_pro',
        'primary' => 'id_wishlist_product',
        'multilang' => false,
        'multilang_shop' => false,
        'fields' => [
            'id_wishlist' =>   ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'id_product' =>   ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'id_product_attribute' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'quantity_rel' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'quantity' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'quantity_init' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'quantity_left_rel' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'quantity_left' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'booked' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'priority' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'position' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'alert_qty' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'contribution' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'id_pdt_original' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],


        ]

    ];


    public static function getCustomerFromCart(Cart $cart){

        $sql = "SELECT DISTINCT  id_customer FROM `"._DB_PREFIX_."giftlist_product_cart_pro` as gpcp
            INNER JOIN "._DB_PREFIX_."giftlist_product_pro as gpp ON gpp.id_wishlist_product = gpcp.id_wishlist_product
            INNER JOIN "._DB_PREFIX_."giftlist_pro as  gp ON gp.id_wishlist = gpp.id_wishlist
            WHERE id_cart = ".$cart->id;


        return Db::getInstance()->getValue($sql);

    }

}