define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'stock/index' + location.search,
                    // add_url: 'stock/add',
                    edit_url: 'stock/edit',
                    // del_url: 'stock/del',
                    multi_url: 'stock/multi',
                    modify_url: 'stock/edit',
                    table: 'goods_attributes_price',
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
                        {checkbox: true},
                        {field: 'id', title: __('id'),operate:false},
                        {field: 'category_id', title: "关联分类", operate:false},
                        // {field: 'sum_stock', title: "总库存", operate:false},
                        {field: 'image', title: __('Image'), events: Table.api.events.image, formatter: Table.api.formatter.image,operate:false},
                        {field: 'name', title: "商品名称",operate:false},
                        {field: 'attribute_price_names', title: "规格信息",operate:false},
                        {field: 'price', title: __('Price'), operate:'BETWEEN'},
                        {field: 'remindtime', title: "超时提醒(分钟)"},
                        {field: 'raw', title: "剩余量/原料用量"},
                        // {field: 'min_stock', title: __('Min_stock')},
                        {field: 'status', title: "状态",operate:false},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,formatter: Table.api.formatter.operate,
                            // buttons: [
                            //    {
                            //        name: 'modify_url',
                            //        text: __('补货'),
                            //        title: __('补货'),
                            //        // extend:'data-area = \'["60%","60%"]\' data-shade=\'[0.5,"#000"]\'',
                            //        classname: 'btn btn-xs btn-info btn-dialog',
                            //        // icon: 'fa fa-list',
                            //        url: 'stock/replenishment',
                            //        hidden:function(row){
                            //            if (row.is_relation != '0'){
                            //                return true;
                            //            }
                            //        },
                            //     }
                            // ]
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
                url: 'stock/recyclebin' + location.search,
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
                                    url: 'stock/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'stock/destroy',
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
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});