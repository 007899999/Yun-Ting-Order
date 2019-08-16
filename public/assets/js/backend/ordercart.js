define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ordercart/index' + location.search,
                    add_url: 'ordercart/add',
                    edit_url: 'ordercart/edit',
                    del_url: 'ordercart/del',
                    multi_url: 'ordercart/multi',
                    table: 'order_cart',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'balcony_id', title: __('Balcony_id')},
                        {field: 'goods_id', title: __('Goods_id')},
                        {field: 'attr_id', title: __('Attr_id')},
                        {field: 'num', title: __('Num')},
                        {field: 'attribute_price_ids', title: __('Attribute_price_ids')},
                        {field: 'attribute_price_names', title: __('Attribute_price_names')},
                        {field: 'price', title: __('Price'), operate:'BETWEEN'},
                        {field: 'name', title: __('Name')},
                        {field: 'image', title: __('Image'), events: Table.api.events.image, formatter: Table.api.formatter.image},
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