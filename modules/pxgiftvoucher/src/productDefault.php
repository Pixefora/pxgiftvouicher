<?php

namespace pxgiftvoucher\src\installer;

use Product;
use Tools;
use StockAvailable;
use Context;
use Language;


final class productDefault
{
    protected $productClass;
    protected $tools;
    protected $stockAvailable;
    protected $context;
    protected $language;

    protected $ean13;
    protected $ref;
    protected $name;
    protected $text;
    protected $catDef;
    protected $price;
    protected $catAll;



    public function __construct(){
        $this->productClass = new Product();
        $this->tools = new Tools();
        $this->stockAvailable = new StockAvailable();
        $this->context = new Context();
        $this->language = new Language();

        $this->ean13 = '';
        $this->ref = 'giftvoucher';
        $this->name = 'Gift Voucher';
        $this->text = 'This is a Gift Voucher';
        $this->catDef = 1;
        $this->price = 0;
        $this->catAll = [1,2];
    }

    public function createDefault(): bool {


        $this->productClass->ean13 = $this->ean13;
        $this->productClass->reference = $this->ref;
        $this->productClass->name = $this->createMultiLangField($this->name);
        $this->productClass->description = htmlspecialchars($this->text);
        $this->productClass->id_category_default = $this->catDef;
        $this->productClass->redirect_type = '301';
        $this->productClass->price = number_format($this->price, 6, '.', '');
        $this->productClass->minimal_quantity = 1;
        $this->productClass->show_price = 1;
        $this->productClass->on_sale = 0;
        $this->productClass->online_only = 0;
        $this->productClass->meta_description = '';
        $this->productClass->link_rewrite = $this->createMultiLangField($this->tools::str2url($this->name));
        $this->productClass->add();                        // Submit new product
        $this->stockAvailable::setQuantity((int) $this->productClass->id, 0,  $this->productClass->quantity, $this->context::getContext()->shop->id);
        $this->productClass->addToCategories($this->catAll);     // After product is submitted insert all categories


        return $this->productClass->id;

    }

    private function createMultiLangField($field) {
        $res = array();
        foreach ($this->language::getIDs(false) as $id_lang) {
            $res[$id_lang] = $field;
        }
        return $res;
    }

}