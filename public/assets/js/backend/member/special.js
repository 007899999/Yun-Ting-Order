define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'member/special/index' + location.search,
                    // add_url: 'member/birthday/add',
                    // edit_url: 'member/birthday/edit',
                    // del_url: 'member/birthday/del',
                    // multi_url: 'member/birthday/multi',
                    table: 'member',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search:false,
                searchFormVisible: false,
                commonSearch: false,
                columns: [
                    [
                        // {checkbox: true},
                        // {field: 'id', title: "id",operate:false},
                        {field: 'member.avatar', title: "会员头像", events: Table.api.events.image, formatter: Table.api.formatter.image,operate:false},
                        // {field: 'level', title: __('Level')},
                        {field: 'member.nickname', title: "会员昵称"},
                        {field: 'member.mobile', title: "手机号"},
                        {field: 'eventstime', title: "日期", operate:false, addclass:'datetimerange'},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            // buttons: [
                            //     {
                            //         name: 'detail',
                            //         text: __(''),
                            //         title: __('查看详情'),
                            //         extend:'data-area = \'["80%","90%"]\' data-shade=\'[0.5,"#000"]\'',
                            //         classname: 'btn btn-xs btn-primary btn-dialog',
                            //         icon: 'fa fa-list',
                            //         url: 'Member/detail',
                            //     },
                            // ],
                        // }
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
                url: 'member/recyclebin' + location.search,
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
                                    url: 'member/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'member/destroy',
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