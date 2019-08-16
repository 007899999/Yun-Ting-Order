define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'finance/Overview/index' + location.search,
                    add_url: 'finance/Overview/add',
                    // edit_url: 'Book/edit',
                    // del_url: 'Book/del',
                    multi_url: 'finance/Overview/multi',
                    table: 'Overview',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                searchFormVisible: false,
                search:false,
                columns: [
                    [
                        // {checkbox: true},
                        {field: 'time', title: "日期",operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'sum_payable_money', title: "营业额",operate:false},
                        {field: 'sum_pay_money', title: "实收额",operate:false},
                        {field: 'sum_pay_money', title: "开发票额",operate:false},
                        {field: 'order_count', title: "总单数",operate:false},
                        {field: 'order_avg', title: "单均消费",operate:false},
                        {field: 'member_count', title: "客流量",operate:false},
                        // {field: 'status', title: "人均消费"},
                        {field: 'wechat', title: "微信",operate:false},
                        {field: 'ali', title: "支付宝",operate:false},
                        {field: 'cash', title: "现金",operate:false},
                        {field: 'card', title: "刷卡",operate:false},
                        {field: 'gua', title: "挂账",operate:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'book',
                                    text: __('查看'),
                                    title: __('查看'),
                                    extend:'data-area = \'["60%","80%"]\' data-shade=\'[0.5,"#000"]\'',
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    // icon: 'fa fa-list',
                                    url: function (value, row, index) {
                                        return "finance/overview/detail?timestamp="+value.timestamps+"&type=1";
                                    },
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