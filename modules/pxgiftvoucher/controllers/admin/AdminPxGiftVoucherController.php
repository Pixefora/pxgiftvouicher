<?php
use pxgiftvoucher\classes\repository\cart_rule;

class AdminPxGiftVoucherController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'pxgiftvoucher';
        $this->className = 'pxgiftvoucherhistory';
        $this->lang = false;

        parent::__construct();

        $this->fields_list = [
            'id_pxgiftvoucher' => [
                'title' => $this->trans('ID',[],'Admin.Global'),
            ],
            'buyed_by' => [
                'title' => $this->trans('Buyed By',[],'Admin.Global'),
            ],
            'id_costumer' => [
                'title' => $this->trans('Customer',[],'Admin.Global'),
            ],
            'id_cart_rule' => [
                'title' => $this->trans('Cart Rule',[],'Admin.Global'),
            ],
            'id_cart' => [
                'title' => $this->trans('Id Cart',[],'Admin.Global'),
            ],
            'price' => [
                'title' => $this->trans('Price',[],'Admin.Global'),
            ],
            'date_add' => [
                'title' => $this->trans('Date Add',[],'Admin.Global'),
            ],

        ];

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->trans('Delete selected', [], 'Admin.Actions'),
                'confirm' => $this->trans('Delete selected items?', [], 'Admin.Notifications.Warning'),
                'icon' => 'icon-trash',
            ],
        ];
    }


    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function renderForm()
    {
        $custumers = Customer::getCustomers();
        $cart_rule = cart_rule::getList();

        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Gift Voucher list',[],'Admin.International.Feature'),
                'icon' => 'icon-tag'
            ],
            'input' => [
                [
                    'type' => 'select',
                    'label' => $this->trans('Buyed by', [], 'Admin.Global'),
                    'name' => 'buyed_by',
                    'lang' => false,
                    'required' => true,
                    'options' => [
                        'query' => $custumers,
                        'id' => 'id_custumer',
                        'name' => 'email'
                    ]
                ],

                [
                    'type' => 'select',
                    'label' => $this->trans('Costumer', [], 'Admin.Global'),
                    'name' => 'id_costumer',
                    'lang' => false,
                    'required' => true,
                    'options' => [
                        'query' => $custumers,
                        'id' => 'id_custumer',
                        'name' => 'email'
                    ]
                ],

                [
                    'type' => 'select',
                    'label' => $this->trans('Cart Rule',[],'Admin.Global'),
                    'name' => 'id_cart_rule',
                    'required' => true,
                    'lang' => false,
                    'options' => [
                        'query' => $cart_rule,
                        'id' => 'id_cart_rule',
                        'name' => 'name'
                    ]
                ],

                [
                    'type' => 'text',
                    'label' => $this->trans('Price',[],'Admin.Global'),
                    'name' => 'price',
                    'required' => true,
                    'hint' => $this->trans('Price',[],'Admin.International.Help')
                ]
            ],
            'submit' => [
                'title' => $this->trans('Save',[],'Admin.Actions'),
            ]
        ];
        return parent::renderForm();
    }


}