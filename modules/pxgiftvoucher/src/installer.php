<?php

namespace pxgiftvoucher\src\installer;

use pxgiftvoucher\src\installer\productDefault;



final class installer
{
    private $module;


    public function __construct(\pxgiftvoucher $module){
        $this->module = $module;
    }

    public function install(){
        $productDefault = new productDefault();

       // $id = $productDefault->createDefault();

        if (!$this->registerHooks()){
            return false;
        }

        return true;
    }

    private function registerHooks(){
        $hooks = [
            'header',
            'displayBackOfficeHeader',
            'diplayVoucherGiftList',
            'actionOrderStatusUpdate'

        ];

        return  array_map([$this->module,'registerHook'],$hooks);
    }





}