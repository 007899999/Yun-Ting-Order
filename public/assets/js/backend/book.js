define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'book/index' + location.search,
                    add_url: 'book/add',
                    // edit_url: 'book/edit',
                    // del_url: 'book/del',
                    multi_url: 'book/multi',
                    table: 'balcony_book',
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
                        // {checkbox: true},
                        // {field: 'balcony.id', title: __('Balcony.id'),operate:false},
                        {field: 'balcony.name', title: __('Balcony.name')},
                        {field: 'nickname', title: __('Nickname')},
                        {field: 'mobile', title: __('Mobile')},
                        {field: 'start_time', title: __('Start_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'end_time', title: __('End_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'status', title: "状态",searchList: {"1":"可预约","2":"已预约","3":"预约过期"}, formatter: Table.api.formatter.status,operate:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'book',
                                    text: __('预约'),
                                    title: __('预约'),
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    // icon: 'fa fa-list',
                                    url: function (value, row, index) {
                                        return "Book/add?id="+value.balcony.id+"&start="+value.start + "&end="+value.end;
                                    },
                                    callback: function (data) {

                                    },
                                    visible: function (row) {
                                        return true;
                                    },
                                    hidden:function(row){
                                        if (row.status == '2'){
                                            return true;
                                        }
                                        if (row.status == '3'){
                                            return true;
                                        }
                                    },
                                },
                                {
                                    name: 'book',
                                    text: __('取消预约'),
                                    title: __('取消预约'),
                                    classname: 'btn btn-xs btn-primary btn-magic btn-ajax',
                                    // icon: 'fa fa-list',
                                    url: function (value, row, index) {
                                        return "Book/cancel?id="+value.id;
                                    },
                                    confirm: '确认取消嘛?',
                                    success: function (data, ret) {
                                        Layer.alert("操作成功",function () {
                                            location.reload();
                                        });
                                    },
                                    hidden:function(row){
                                        if (row.status == '1'){
                                            return true;
                                        }
                                        if (row.status == '3'){
                                            return true;
                                        }
                                    },
                                }
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
                url: 'book/recyclebin' + location.search,
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
                                    url: 'book/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'book/destroy',
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