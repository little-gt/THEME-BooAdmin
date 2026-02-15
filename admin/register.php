<?php
include 'common.php';

if ($user->hasLogin() || !$options->allowRegister) {
    $response->redirect($options->siteUrl);
}
$rememberName = htmlspecialchars(\Typecho\Cookie::get('__typecho_remember_name', ''));
$rememberMail = htmlspecialchars(\Typecho\Cookie::get('__typecho_remember_mail', ''));
\Typecho\Cookie::delete('__typecho_remember_name');
\Typecho\Cookie::delete('__typecho_remember_mail');

$bodyClass = 'body-100';

include 'header.php';
?>
<div class="flex-1 overflow-y-auto w-full" style="background-image: url('https://image.uc.cn/s/uae/g/3n/mos-production/1220/85df1a9657682d1c.jpg'); background-size: cover; background-position: center;">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-[480px] rounded shadow-2xl p-8 transform transition-all hover:scale-[1.01] duration-300">
            <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-1 flex items-center justify-center">
                <?php _e('创建账号'); ?>
            </h1>
        </div>

        <form action="<?php $options->registerAction(); ?>" method="post" name="register" role="form" class="space-y-4">
            <div>
                <label for="name" class="block text-xs font-bold text-gray-500 uppercase mb-2"><?php _e('用户名'); ?> <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" placeholder="" value="<?php echo $rememberName; ?>" class="w-full px-3 py-2.5 bg-gray-200 border-none rounded text-sm focus:outline-none focus:ring-0 text-discord-text transition-all" required autofocus />
            </div>
            
            <div>
                <label for="mail" class="block text-xs font-bold text-gray-500 uppercase mb-2"><?php _e('电子邮箱'); ?> <span class="text-red-500">*</span></label>
                <input type="email" id="mail" name="mail" placeholder="" value="<?php echo $rememberMail; ?>" class="w-full px-3 py-2.5 bg-gray-200 border-none rounded text-sm focus:outline-none focus:ring-0 text-discord-text transition-all" required />
            </div>

            <button type="submit" class="w-full py-2.5 bg-discord-accent text-white rounded font-medium hover:bg-blue-600 transition-colors shadow-sm mb-2 mt-4">
                <?php _e('继续'); ?>
            </button>
            
            <div class="text-sm text-gray-500 mt-2">
                <a href="<?php $options->adminUrl('login.php'); ?>" class="text-discord-accent hover:underline"><?php _e('已有账号？'); ?></a>
            </div>
        </form>
        
        <div class="mt-8 text-center text-xs text-gray-400">
            &copy; <?php echo date('Y'); ?> Typecho Team.
        </div>
    </div>
</div>
</div>
<?php 
include 'common-js.php';
?>
<script>
$(document).ready(function () {
    $('#name').focus();
});
</script>
<?php
include 'footer.php';
?>
