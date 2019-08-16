define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'finance/Overviewtoday/index' + location.search,
                    add_url: 'finance/Overviewtoday/add',
                    // edit_url: 'Book/edit',
                    // del_url: 'Book/del',
                    multi_url: 'finance/Overviewtoday/multi',
                    table: 'Overviewtoday',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                commonSearch:false,
                search:false,
                columns: [
                    [
                        // {checkbox: true},
                        // {field: 'time', title: "日期"},
                        {field: 'balcony_name', title: "包厢名称"},
                        {field: 'sum_payable_money', title: "营业额"},
                        {field: 'sum_pay_money', title: "实收额"},
                        {field: 'sum_pay_money', title: "开发票额"},
                        {field: 'order_count', title: "总单数"},
                        {field: 'order_avg', title: "单均消费"},
                        {field: 'member_count', title: "客流量"},
                        // {field: 'status', title: "人均消费"},
                        {field: 'payment_from', title: "支付类型",searchList: {"0":"微信","1":"支付宝","2":"现金","3":"刷卡","4":"挂账"}, formatter: Table.api.formatter.status},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'detail',
                                    text: __(''),
                                    title: __('查看详情'),
                                    extend:'data-area = \'["60%","80%"]\' data-shade=\'[0.5,"#000"]\'',
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-list',
                                    url: 'Order/detail',
                                }
                            ],
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
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});