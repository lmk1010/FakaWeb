<?php
/* Smarty version 3.1.46, created on 2026-03-29 10:15:54
  from '/Users/liumingkang/AI/Project/acg-faka-main/app/View/Admin/User/Group.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69c8fbda580597_26549275',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '79e85ab1a6aaf7c3220950dc903b41fb2bddb445' => 
    array (
      0 => '/Users/liumingkang/AI/Project/acg-faka-main/app/View/Admin/User/Group.html',
      1 => 1687539156,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../Header.html' => 1,
    'file:../Toolbar.html' => 1,
    'file:../Footer.html' => 1,
  ),
),false)) {
function content_69c8fbda580597_26549275 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<style>
    .layui-popup .layui-layer-content {
        position: relative !important;
        overflow: auto !important;
    }
</style>
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <?php $_smarty_tpl->_subTemplateRender("file:../Toolbar.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-fluid">
            <!--begin::Tables Widget 9-->
            <div class="card mb-5 mb-xl-8">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <div class="card-toolbar">
                        <button class="btn btn-sm btn-light-primary btn-app-create me-3"><i
                                    class="fas fa-plus"></i>
                            新增等级
                        </button>
                    </div>
                </div>
                <!--end::Header-->
                <div class="card-body py-3">
                    <table id="user-group"></table>
                </div>
            </div>

            <!--end::Tables Widget 9-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->

<?php echo '<script'; ?>
>

    $(function () {
        layui.use(['hex'], function () {
            let cao = layui.hex;
            let table = $("#user-group");
            let queryParams = null;
            table.bootstrapTable({
                url: "/admin/api/group/data",//请求的url地址
                method: "post",//请求方式
                // striped:true,//是否显示行间隔色
                pageSize: 17,//每页显示的数量
                pageList: [17, 25, 50, 100],
                showRefresh: false,//是否显示刷新按钮
                cache: false,//是否使用缓存
                showToggle: false,//是否显示详细视图和列表视图的切换按钮
                pagination: true,//是否显示分页
                hegiht: 20,//表格的高度
                pageNumber: 1,//初始化显示第几页，默认第1页
                singleSelect: true,//复选框只能选择一条记录
                sidePagination: 'server',//分页显示方式，可以选择客户端和服务端（server|client）
                contentType: "application/x-www-form-urlencoded",//使用post请求时必须加上
                dataType: "json",//接收的数据类型
                queryParamsType: 'limit',//参数格式，发送标准的Restful类型的请求
                queryParams: function (params) {
                    params.page = (params.offset / params.limit) + 1;
                    if (queryParams) {
                        for (const key in params) {
                            queryParams[key] = params[key];
                        }
                    } else {
                        queryParams = params;
                    }
                    return queryParams;
                },
                //回调函数
                responseHandler: function (res) {
                    return {
                        "total": res.count,
                        "rows": res.data
                    }
                },
                columns: [
                    {
                        field: "name", title: "等级名称", formatter: function (val, item) {
                            return '<span class="badge badge-light">' + item.name + '</span>'
                        }
                    },
                    {
                        field: "icon", title: "图标", formatter: function (val, item) {
                            if (!item.icon) {
                                return "-";
                            }
                            return '<img src="' + item.icon + '"  style="height: 32px;"/>';
                        }
                    },
                    {
                        field: "recharge", title: "所需元气", formatter: function (val, item) {
                            return '<span class="badge badge-light-success">' + item.recharge + '</span>'
                        }
                    },
                    {
                        field: "discount", title: "会员折扣", formatter: function (val, item) {
                            return '<span class="badge badge-light-primary">' + parseFloat(item.discount * 100).toFixed(2) + '%</span>'
                        }
                    }, {
                        field: 'operate',
                        title: '操作',
                        formatter: function (val, item) {
                            let html = '<button type="button"  class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 edit"><i class="fa fa-edit"></i></button> ';

                            if (item.id != 1) {
                                html += '<button type="button"  class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 del"><i class="fas fa-trash"></i></button> ';
                            }
                            return html;
                        },
                        events: {               // 注册按钮组事件
                            'click .edit': function (event, value, row, index) {
                                modal(row);
                            },
                            'click .del': function (event, value, row, index) {
                                layer.confirm('您正在移除该等级，是否要继续？', {
                                    btn: ['确认移除', '取消']
                                }, function () {
                                    $.post('/admin/api/group/del', {list: [row.id]}, res => {
                                        layer.msg(res.msg);
                                        table.bootstrapTable('refresh', {silent: true});
                                    });
                                });
                            }
                        }
                    }
                ]
            });
            let modal = (values = {}) => {
                cao.popup('/admin/api/group/save', [
                    {title: "等级图标", name: "icon", type: "image", placeholder: "请选择图片", width: 50},
                    {title: "等级名称", name: "name", type: "input", placeholder: "请输入等级名称"},
                    {
                        title: "会员折扣",
                        name: "discount",
                        type: "input",
                        placeholder: "请使用小数表达百分比",
                        tips: "当会员购买商品时，系统将会通过这个费率计算出给会员的优惠折扣，就和理发店办理VIP一样。"
                    },
                    {
                        title: "累计元气",
                        name: "recharge",
                        type: "input",
                        placeholder: "请输入累计元气",
                        tips: "当会员元气累计达到这个数量时，将会自动升级为该会员等级，元气=充值/消费1:1获得"
                    },
                ], res => {
                    table.bootstrapTable('refresh', {silent: true});
                }, values, '660px', false, '添加', uuid => {
                    if (values.hasOwnProperty("id") && values.id == 1) {
                        let d = cao.popupElement("recharge", "input", uuid).parent().parent();
                        d.hide();
                    }
                });
            }

            $('.btn-app-create').click(function () {
                modal();
            });
        });
    });
<?php echo '</script'; ?>
>

<?php $_smarty_tpl->_subTemplateRender("file:../Footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
