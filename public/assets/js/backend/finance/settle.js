define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'finance/settle/index' + location.search,
                    add_url: 'finance/settle/add',
                    edit_url: 'finance/settle/edit',
                    // del_url: 'Book/del',
                    multi_url: 'finance/settle/multi',
                    table: 'Settle',
                }
            });

            var table = $("#table");
            //当表格数据加载完成时
            table.on('load-success.bs.table', function (e, data) {
                //这里可以获取从服务端获取的JSON数据
                console.log(data);
                //这里我们手动设置底部的值
                $("#money").text(data.extend.money);
                $("#price").text(data.extend.price);
            });

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
                        {field: 'member.nickname', title: "用户昵称"},
                        {field: 'balcony_name', title: "消费包厢"},
                        {field: 'payable_money', title: "消费金额",operate:'BETWEEN'},
                        {field: 'updatetime', title: "挂账日期", operate:false, addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'remark', title: "备注"},
                        {field: 'payment_status', title: "是否支付", searchList: {"0":"未支付","1":"已支付"}, formatter: Table.api.formatter.status},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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