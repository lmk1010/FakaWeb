<?php
/* Smarty version 3.1.46, created on 2026-03-29 10:17:08
  from '/Users/liumingkang/AI/Project/acg-faka-main/app/View/User/Theme/Cartoon/Index/Header.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69c8fc2442a2b1_71632445',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0b45ed9444641d1e6d92e78583e49a2d782a92c1' => 
    array (
      0 => '/Users/liumingkang/AI/Project/acg-faka-main/app/View/User/Theme/Cartoon/Index/Header.html',
      1 => 1687539156,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69c8fc2442a2b1_71632445 (Smarty_Internal_Template $_smarty_tpl) {
?><!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['config']->value['keywords'];?>
"/>
    <meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['config']->value['description'];?>
"/>
    <link href="<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
?v=<?php echo $_smarty_tpl->tpl_vars['app']->value['version'];?>
" rel="icon">
    <link rel="stylesheet" href="/assets/static/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/static/font/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/static/css/i.css?v=<?php echo $_smarty_tpl->tpl_vars['app']->value['version'];?>
">

    <?php echo '<script'; ?>
 src="/assets/static/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/assets/static/jquery.sliderBar.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/assets/static/layer/layer.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/assets/static/pay.js?v=<?php echo $_smarty_tpl->tpl_vars['app']->value['version'];?>
"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/assets/static/clipboard.js"><?php echo '</script'; ?>
>
    <title><?php echo $_smarty_tpl->tpl_vars['config']->value['title'];?>
</title>
    <!--start::HOOK-->
    <?php echo hook(\App\Consts\Hook::USER_GLOBAL_VIEW_HEADER);?>

    <?php echo hook(\App\Consts\Hook::USER_VIEW_INDEX_HEADER);?>

    <!--end::HOOK-->
</head>
<body style="background: url('<?php echo $_smarty_tpl->tpl_vars['config']->value['background_url'];?>
') fixed no-repeat;background-size: cover;">
<div id="app">
    <nav class="navbar navbar-expand-lg navbar-light bg-light"
         style="background-color: rgba(255,255,255,0.85) !important;">
        <div class="container">
           <div class="navbar-brand">
               <a href="<?php if ($_smarty_tpl->tpl_vars['user']->value) {?>/user/dashboard/index<?php } else { ?>/<?php }?>">
                   <img src="<?php if ($_smarty_tpl->tpl_vars['user']->value['avatar']) {
echo $_smarty_tpl->tpl_vars['user']->value['avatar'];
} else {
echo $_smarty_tpl->tpl_vars['favicon']->value;
}?>" height="30px" style="border-radius: 50%;box-shadow: #f0d1d4 1px 1px 1px;">
                   <?php if ($_smarty_tpl->tpl_vars['user']->value) {?><span style="position: relative;top: 4px;left: 3px;font-weight: bold;color: #1396558a;"> <?php echo $_smarty_tpl->tpl_vars['user']->value['username'];?>
  <?php } else { ?> <span style="position: relative;top: 4px;left: 3px;font-weight: bold;color: #1396558a;">  <?php echo $_smarty_tpl->tpl_vars['config']->value['shop_name'];?>
  <?php }?>
               </span></a>
                <?php if ($_smarty_tpl->tpl_vars['user']->value) {?>
               <a href="/user/dashboard/index"><span style="position: relative;top: 4px;left: 10px;font-size: 14px;font-weight: bold;color: #7be7bfd9;"><i class="fa fa-yen"></i><?php echo $_smarty_tpl->tpl_vars['user']->value['balance'];?>
</span></a>
               <?php } else { ?>  <a href="/user/authentication/login?goto=/"><span style="position: relative;top: 4px;left: 10px;font-size: 18px;color: #79b9fbbd;font-weight: bold;"><i class="fa fa-sign-in"></i> 登录</span></a> <?php }?>
           </div>
            <div class="row">
                <a class="nav-link" href="/" style="font-weight: bolder;"><i class="fa fa-shopping-cart" aria-hidden="true"></i>  购物</a>
                <a class="nav-link" href="/user/index/query"  style="font-weight: bolder;"><i class="fa fa-search-plus" aria-hidden="true"></i> 查订单</a>
            <?php if ($_smarty_tpl->tpl_vars['config']->value['service_url']) {?><a class="nav-link" href="<?php echo $_smarty_tpl->tpl_vars['config']->value['service_url'];?>
" target="_blank"  style="font-weight: bolder;"><i class="fa fa-twitch" aria-hidden="true"></i> 在线客服</a><?php }?>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, hook(\App\Consts\Hook::USER_VIEW_HEADER_NAV), 'item');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
                    <a class="nav-link" href="<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
" style="font-weight: bolder;" target="<?php echo $_smarty_tpl->tpl_vars['item']->value['target'];?>
"><i class="<?php echo $_smarty_tpl->tpl_vars['item']->value['icon'];?>
" aria-hidden="true"></i>  <?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</a>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </div>
            <!--<div class="navbar-collapse">
                <ul class="navbar-nav mr-auto"></ul>
            </div>-->
        </div>
    </nav><?php }
}
