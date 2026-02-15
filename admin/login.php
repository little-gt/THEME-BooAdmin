<?php
include 'common.php';

if ($user->hasLogin()) {
    $response->redirect($options->adminUrl);
}
$rememberName = htmlspecialchars(\Typecho\Cookie::get('__typecho_remember_name', ''));
\Typecho\Cookie::delete('__typecho_remember_name');

$bodyClass = 'body-100';

include 'header.php';
?>
<div class="flex-1 overflow-y-auto w-full" style="background-image: url('https://image.uc.cn/s/uae/g/3n/mos-production/1220/85df1a9657682d1c.jpg'); background-size: cover; background-position: center;">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-[480px] rounded shadow-2xl p-8 transform transition-all hover:scale-[1.01] duration-300">
            <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-1 flex items-center justify-center">
                <?php _e('欢迎回来！'); ?>
            </h1>
            <p class="text-gray-500 text-sm"><?php _e('很高兴见到你！'); ?></p>
        </div>

        <form action="<?php $options->loginAction(); ?>" method="post" name="login" role="form" class="space-y-4">
            <div>
                <label for="name" class="block text-xs font-bold text-gray-500 uppercase mb-2"><?php _e('用户名'); ?> <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo $rememberName; ?>" class="w-full px-3 py-2.5 bg-gray-200 border-none rounded text-sm focus:outline-none focus:ring-0 text-discord-text transition-all" required autofocus />
            </div>
            
            <div>
                <label for="password" class="block text-xs font-bold text-gray-500 uppercase mb-2"><?php _e('密码'); ?> <span class="text-red-500">*</span></label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2.5 bg-gray-200 border-none rounded text-sm focus:outline-none focus:ring-0 text-discord-text transition-all" required />
                <div class="mt-1 text-xs">
                    <?php $activates = array_keys(Typecho_Plugin::export()['activated']);
                    if (in_array('Passport', $activates)) {
                    echo '<a href="' . Typecho_Common::url('passport/forgot', $options->index) . '">' . '忘记密码' . '</a>';}?>
                </div>
            </div>

            <button type="submit" class="w-full py-2.5 bg-discord-accent text-white rounded font-medium hover:bg-blue-600 transition-colors shadow-sm mb-2 mt-4">
                <?php _e('登录'); ?>
            </button>
            
            <div class="text-sm text-gray-500 mt-2">
                 <?php _e('需要账户？'); ?> <a href="<?php $options->registerUrl(); ?>" class="text-discord-accent hover:underline"><?php _e('注册'); ?></a>
            </div>
            
            <input type="hidden" name="referer" value="<?php echo htmlspecialchars($request->get('referer')); ?>" />
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
