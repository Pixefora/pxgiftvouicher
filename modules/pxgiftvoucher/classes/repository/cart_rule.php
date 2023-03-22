<?php

namespace pxgiftvoucher\classes\repository;

use Db;
use Context;

final class cart_rule
{
    public static function getList(){

        $lang = Context::getContext()->language->id;

        $sql = 'SELECT SQL_CALC_FOUND_ROWS b.*, a.* FROM `'._DB_PREFIX_.'cart_rule` a 
        LEFT JOIN `'._DB_PREFIX_.'cart_rule_lang` b ON (b.`id_cart_rule` = a.`id_cart_rule` AND b.`id_lang` = '.$lang.') 
        WHERE 1 ORDER BY a.id_cart_rule DESC';

        \Logger::AddLog($sql);

        return Db::getInstance()->executeS($sql);

    }
}