define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'balcony/index' + location.search,
                    add_url: 'balcony/add',
                    edit_url: 'balcony/edit',
                    del_url: 'balcony/del',
                    multi_url: 'balcony/multi',
                    table: 'balcony',
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
                        {field: 'id', title: __('Id'),operate:false},
                        {field: 'name', title: __('Name')},
                        {field: 'password', title: __('Password'),operate:false},
                        {field: 'createtime', title: "创建时间", operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'is_online', title: "是否在线", searchList: {"1":"在线","0":"离线"}, formatter: Table.api.formatter.status,operate:false},
                        {field: 'member', title: "会员登录",operate:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'member_login',
                                    text: __('会员登录'),
                                    title: __('会员登录'),
                                    extend:'data-area = \'["40%","30%"]\' data-shade=\'[0.5,"#000"]\'',
                                    classname: 'btn btn-xs btn-success btn-dialog',
                                    // icon: 'fa fa-list',
                                    url: 'Balcony/member_login' + location.search,
                                    hidden:function(row){
                                        if (row.is_online == '0' || row.member_id != '0'){
                                            return true;
                                        }
                                    },
                                },
                                {
                                    name: 'member_logout',
                                    text: __('会员退出'),
                                    title: __('会员退出'),
                                    extend:'data-area = \'["40%","30%"]\' data-shade=\'[0.5,"#000"]\'',
                                    classname: 'btn btn-xs btn-primary btn-magic btn-ajax',
                                    // icon: 'fa fa-list',
                                    confirm: '确认退出嘛?',
                                    url: 'Balcony/member_logout' + location.search,
                                    success: function (data, ret) {
                                        Layer.alert("操作成功",function () {
                                            location.reload();
                                        });
                                    },
                                    hidden:function(row){
                                        if (row.is_online == '0' || row.member_id == '0'){
                                            return true;
                                        }
                                    },
                                },
                                {
                                    name: 'detail',
                                    text: __('历史订单'),
                                    title: __('历史订单'),
                                    extend:'data-area = \'["60%","80%"]\' data-shade=\'[0.5,"#000"]\'',
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    // icon: 'fa fa-list',
                                    url: 'Balcony/historical_orders' + location.search,
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
                url: 'balcony/recyclebin' + location.search,
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
                                    url: 'balcony/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'balcony/destroy',
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