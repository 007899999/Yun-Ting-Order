define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/index' + location.search,
                    // add_url: 'order/add',
                    // edit_url: 'order/edit',
                    del_url: 'order/del',
                    multi_url: 'order/multi',
                    table: 'order',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search:false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('id'),operate:false},
                        {field: 'balcony_name', title: __('Balcony_name')},
                        {field: 'order_no', title: __('Order_no')},
                        {field: 'payable_money', title: "订单金额", operate:'BETWEEN'},
                        {field: 'payment_status', title: __('Payment_status'), searchList: {"0":__('Payment_status 0'),"1":__('Payment_status 1')}, formatter: Table.api.formatter.status,operate:false},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'detail',
                                    text: __('微信'),
                                    title: __('微信'),
                                    extend:'data-area = \'["50%","40%"]\' data-shade=\'[0.5,"#000"]\'',
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    // icon: 'fa fa-list',
                                    url: 'Order/other_pay?type=1',
                                },
                                {
                                    name: 'detail',
                                    text: __('支付宝'),
                                    title: __('支付宝'),
                                    extend:'data-area = \'["50%","40%"]\' data-shade=\'[0.5,"#000"]\'',
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    // icon: 'fa fa-list',
                                    url: 'Order/other_pay?type=2',
                                },
                                {
                                    name: 'detail',
                                    text: __('刷卡'),
                                    title: __('刷卡'),
                                    extend:'data-area = \'["50%","40%"]\' data-shade=\'[0.5,"#000"]\'',
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    // icon: 'fa fa-list',
                                    url: 'Order/card',
                                },
                                {
                                    name: 'detail',
                                    text: __('现金'),
                                    title: __('现金'),
                                    extend:'data-area = \'["50%","40%"]\' data-shade=\'[0.5,"#000"]\'',
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    // icon: 'fa fa-list',
                                    url: 'Order/cash',
                                },
                                {
                                    name: 'detail',
                                    text: __('挂账单'),
                                    title: __('挂账单'),
                                    extend:'data-area = \'["50%","40%"]\' data-shade=\'[0.5,"#000"]\'',
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    // icon: 'fa fa-list',
                                    url: 'Order/settle',
                                },
                                {
                                    name: 'detail',
                                    text: __('修改金额'),
                                    title: __('修改金额'),
                                    extend:'data-area = \'["50%","40%"]\' data-shade=\'[0.5,"#000"]\'',
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    // icon: 'fa fa-list',
                                    url: 'Order/modify_money',
                                },
                                {
                                    name: 'detail',
                                    text: __('详情'),
                                    title: __('查看详情'),
                                    extend:'data-area = \'["60%","80%"]\' data-shade=\'[0.5,"#000"]\'',
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    // icon: 'fa fa-list',
                                    url: 'Order/detail',
                                },
                            ],
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'order/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '130px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'order/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'order/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        card: function () {
            Controller.api.bindevent();
        },
        cash: function () {
            Controller.api.bindevent();
        },
        settle: function () {
            Controller.api.bindevent();
        },
        modify_money: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});