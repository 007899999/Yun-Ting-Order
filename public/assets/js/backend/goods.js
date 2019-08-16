define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'goods/index' + location.search,
                    add_url: 'goods/add',
                    edit_url: 'goods/edit',
                    // del_url: 'goods/del',
                    multi_url: 'goods/multi',
                    table: 'goods',
                }
            });

            var table = $("#table");
            //给添加按钮添加`data-area`属性
            $(".btn-add").data("area", ["90%", "90%"]);
            //当内容渲染完成给编辑按钮添加`data-area`属性
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                $(".btn-editone").data("area", ["90%", "90%"]);
            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                search:false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'),operate:false},
                        {field: 'category_id', title: __('Category_id'),operate:false},
                        {field: 'name', title: __('Name')},
                        {field: 'image', title: __('Image'), events: Table.api.events.image, formatter: Table.api.formatter.image,operate:false},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1')}, formatter: Table.api.formatter.status},
                        {field: 'price', title: __('Price'), operate:'BETWEEN'},
                        {field: 'is_evaluate', title: __('Is_evaluate'), searchList: {"0":__('Is_evaluate 0'),"1":__('Is_evaluate 1')}, formatter: Table.api.formatter.normal},
                        {field: 'operate', title: __('Operate'), table: table,
                            buttons: [
                                {
                                    name: 'start',
                                    text: __('上架'),
                                    title: __('上架'),
                                    classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                                    // icon: 'fa fa-magic',
                                    url: 'goods/start',
                                    confirm: '确认上架嘛?',
                                    success: function (data, ret) {
                                        Layer.alert("操作成功",function () {
                                            location.reload();
                                        });
                                    },
                                    hidden:function(row){
                                        if (row.status == '1'){
                                            return true;
                                        }
                                    },
                                },
                                {
                                    name: 'stop',
                                    text: __('下架'),
                                    title: __('下架'),
                                    classname: 'btn btn-xs btn-primary btn-magic btn-ajax',
                                    // icon: 'fa fa-magic',
                                    url: 'goods/stop',
                                    confirm: '确认下架嘛?',
                                    success: function (data, ret) {
                                        Layer.alert("操作成功",function () {
                                            location.reload();
                                        });
                                    },
                                    hidden:function(row){
                                        if (row.status == '0'){
                                            return true;
                                        }
                                    },
                                },
                                {
                                    name: 'stop',
                                    text: __('删除'),
                                    title: __('删除'),
                                    classname: 'btn btn-xs btn-primary btn-magic btn-ajax',
                                    // icon: 'fa fa-magic',
                                    url: 'goods/del',
                                    confirm: '确认删除嘛?',
                                    success: function (data, ret) {
                                        Layer.alert("操作成功",function () {
                                            location.reload();
                                        });
                                    },
                                },
                                {
                                    name: 'detail',
                                    text: __('搭配商品'),
                                    title: __('搭配商品'),
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    // icon: 'fa fa-list',
                                    url: 'goods/recommend',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    },
                                    visible: function (row) {
                                        //返回true时按钮显示,返回false隐藏
                                        return true;
                                    }
                                },
                            ],
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate}
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
                url: 'goods/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name'), align: 'left'},
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
                                    url: 'goods/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'goods/destroy',
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