define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ordergoods/index' + location.search,
                    // add_url: 'ordergoods/add',
                    // edit_url: 'ordergoods/edit',
                    del_url: 'ordergoods/del',
                    multi_url: 'ordergoods/multi',
                    table: 'order_goods',
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
                        {field: 'balcony_name', title: "包厢名称"},
                        {field: 'order_no', title: __('Order_no')},
                        {field: 'payable_money', title: "订单金额", operate:'BETWEEN'},
                        {field: 'payment_status', title: "支付状态", searchList: {"0":"未支付","1":"已支付"}, formatter: Table.api.formatter.status,operate:false},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'prepare', title: "备餐状态",searchList: {"0":"上餐完毕","1":"部分菜品备餐中"}, formatter: Table.api.formatter.status,operate:false},
                        {field: 'overtime', title: "是否超时",searchList: {"0":"暂无菜品超时","1":"部分菜品已超时"}, formatter: Table.api.formatter.status,operate:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'detail',
                                    text: __('详情'),
                                    title: __('查看详情'),
                                    extend:'data-area = \'["60%","80%"]\' data-shade=\'[0.5,"#000"]\'',
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    // icon: 'fa fa-list',
                                    url: 'Order/detail',
                                },
                            ],}
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
                url: 'ordergoods/recyclebin' + location.search,
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
                                    url: 'ordergoods/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'ordergoods/destroy',
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
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});