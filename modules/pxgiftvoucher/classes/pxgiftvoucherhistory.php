<?php


final class pxgiftvoucherhistory extends ObjectModel
{
    public $id_costumer;
    public $buyed_by;
    public $id_cart_rule;
    public $price;
    public $id_cart;
    public $date_add;


    public static $definition = [
        'table' => 'pxgiftvoucher',
        'primary' => 'id_pxgiftvoucher',
        'multilang' => false,
        'multilang_shop' => false,
        'fields' => [
            'id_costumer' =>   ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'buyed_by' =>   ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'id_cart_rule' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'price' => ['type'=>self::TYPE_FLOAT,'validate' => 'isPrice','size' => '20','required' => true ],
            'id_cart' =>     ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true ],
            'date_add' => ['type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDateFormat','required' => true ]

        ]

    ];


    public function checkAndGetIdCartRule(int $id_costumer, int $id_cart){

        $sql = "SELECT DISTINCT id_cart_rule FROM "._DB_PREFIX_."pxgiftvoucher
          WHERE id_costumer = ".$id_costumer." AND id_cart <> ".$id_cart;

        return Db::getInstance()->getValue($sql);


    }

    public function checkIfCanCreate(int $id_costumer, int $id_cart){

        $sql = "SELECT DISTINCT id_cart_rule FROM "._DB_PREFIX_."pxgiftvoucher
          WHERE id_costumer = ".$id_costumer." AND id_cart = ".$id_cart;

        $value = Db::getInstance()->executeS($sql);

        if (count($value) == 0)
            return false;

        return true;
    }


}