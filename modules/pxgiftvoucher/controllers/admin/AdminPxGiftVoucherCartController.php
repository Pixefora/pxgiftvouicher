<?php

class AdminPxGiftVoucherCartController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'pxgiftvoucher_cart';
        $this->className = 'pxgiftvouchercart';
        $this->lang = false;

        parent::__construct();

        $this->fields_list = [
            'id_pxgiftvoucher_cart' => [
                'title' => $this->trans('ID',[],'Admin.Global'),
            ],
            'id_buyed_to' => [
                'title' => $this->trans('Buyed To',[],'Admin.Global'),
            ],
            'id_cart' => [
                'title' => $this->trans('Id Cart',[],'Admin.Global'),
            ],
            'id_product' => [
                'title' => $this->trans('ID Product',[],'Admin.Global'),
            ],
            'id_attribute' => [
                'title' => $this->trans('Id Attribute',[],'Admin.Global'),
            ],
            'date_add' => [
                'title' => $this->trans('Date Add',[],'Admin.Global'),
            ],

        ];


    }



}