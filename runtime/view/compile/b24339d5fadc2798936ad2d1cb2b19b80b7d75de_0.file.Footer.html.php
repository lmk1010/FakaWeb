<?php
/* Smarty version 3.1.46, created on 2026-03-29 10:17:08
  from '/Users/liumingkang/AI/Project/acg-faka-main/app/View/User/Theme/Cartoon/Index/Footer.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.46',
  'unifunc' => 'content_69c8fc24444e53_60103677',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b24339d5fadc2798936ad2d1cb2b19b80b7d75de' => 
    array (
      0 => '/Users/liumingkang/AI/Project/acg-faka-main/app/View/User/Theme/Cartoon/Index/Footer.html',
      1 => 1687539156,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69c8fc24444e53_60103677 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="content-icp"><?php echo $_smarty_tpl->tpl_vars['setting']->value['icp'];?>
</div>
<!--start::HOOK-->
<?php echo hook(\App\Consts\Hook::USER_GLOBAL_VIEW_BODY);?>

<?php echo hook(\App\Consts\Hook::USER_VIEW_INDEX_BODY);?>

<!--end::HOOK-->
</body>
<!--start::HOOK-->
<?php echo hook(\App\Consts\Hook::USER_GLOBAL_VIEW_FOOTER);?>

<?php echo hook(\App\Consts\Hook::USER_VIEW_INDEX_FOOTER);?>

<!--end::HOOK-->
</html><?php }
}
