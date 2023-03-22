<?php

namespace pxgiftvoucher\classes\actions;

use pxgiftvoucher\classes\help\HelperTools;


class giftVoucherCreator
{
   private $pxgiftvoucherhistory;
   private $cartRule;
   private $cart;
   private $id_buyed_to;
   private $pxgiftvouchercart;
   private $price;

    public function __construct(\pxgiftvoucherhistory $pxgiftvoucherhistory, \CartRule $cartRule, \Cart $cart, int $id_buyed_to, \pxgiftvouchercart $pxgiftvouchercart){

        $this->pxgiftvoucherhistory = $pxgiftvoucherhistory;
        $this->cartRule = $cartRule;
        $this->cart = $cart;
        $this->id_buyed_to = $id_buyed_to;
        $this->pxgiftvouchercart = $pxgiftvouchercart;
        $this->price = $this->pxgiftvouchercart->getPriceFromGiftProduct($this->cart);

    }

    public  function doit(){

        $id_cart_rule = $this->pxgiftvoucherhistory->checkAndGetIdCartRule($this->id_buyed_to,$this->cart->id);

        if ($this->pxgiftvoucherhistory->checkIfCanCreate($this->id_buyed_to,$this->cart->id)){
            return;
        }

        if ($id_cart_rule === false){

            $this->create();
            $this->createHistory();
            return;
        }

        $this->cartRule = new \CartRule($id_cart_rule);
        $this->updatePrice();
        $this->createHistory();

    }


    private function create(){


        $names = HelperTools::setLangStringsConfig('PXGIFTVOUCHER_PREFIX');

        $this->cartRule->id_customer = $this->id_buyed_to;
        $this->cartRule->description = '';
        $this->cartRule->quantity = 1;
        $this->cartRule->quantity_per_user = 1;
        $this->cartRule->partial_use = 1;
        $this->cartRule->reduction_amount = $this->price;
        $this->cartRule->highlight = 1;
        $this->cartRule->active = 1;
        $this->cartRule->name = $names;
        $this->cartRule->date_from = date('Y-m-d H:i:s');
        $this->cartRule->date_to = date('2050-m-d H:i:s');

        return $this->cartRule->add();

    }

    private function updatePrice(){

        $this->cartRule->reduction_amount+= $this->price;

        return $this->cartRule->update();
    }

    private function createHistory(){

        $id_cart_rule = $this->cartRule->id;
        $id_customer = $this->cart->id_customer;
        $this->pxgiftvoucherhistory->buyed_by = $id_customer;
        $this->pxgiftvoucherhistory->date_add = date('Y-m-d');
        $this->pxgiftvoucherhistory->id_cart_rule = $id_cart_rule;
        $this->pxgiftvoucherhistory->id_cart = $this->cart->id;
        $this->pxgiftvoucherhistory->price = $this->price;
        $this->pxgiftvoucherhistory->id_costumer = $this->id_buyed_to;

        $this->pxgiftvoucherhistory->save();
    }




}