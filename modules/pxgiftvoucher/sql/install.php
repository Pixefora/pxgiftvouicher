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
$sql = [];

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pxgiftvoucher` (
    `id_pxgiftvoucher` int(11) NOT NULL AUTO_INCREMENT,
    `buyed_by` int(11) NOT NULL,
    `id_costumer` int(11) NOT NULL,
    `id_cart_rule` int(11) NOT NULL,
    `id_cart` int(11) NOT NULL,
    `price` decimal(20,6) NOT NULL,
    `date_add` datetime NOT NULL,
    PRIMARY KEY  (`id_pxgiftvoucher`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';


$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pxgiftvoucher_cart` (
    `id_pxgiftvoucher_cart` int(11) NOT NULL AUTO_INCREMENT,
    `id_cart` int(11) NOT NULL,
    `id_buyed_to` int(11) NOT NULL,
    `id_product` int(11) NOT NULL,
    `id_attribute` int(11) NOT NULL,
    `date_add` datetime NOT NULL,
    PRIMARY KEY  (`id_pxgiftvoucher_cart`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
