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
<div class="min-h-screen flex bg-discord-light text-discord-text">
    <!-- Left Side: Hero Image -->
    <div class="hidden md:flex md:w-1/2 flex-col justify-center items-center bg-cover bg-center relative" style="background-image: url('https://image.uc.cn/s/uae/g/3n/mos-production/1220/85df1a9657682d1c.jpg');">
        <div class="absolute inset-0 bg-gradient-to-br from-discord-accent/80 to-purple-900/80 pointer-events-none"></div>
        <div class="relative z-10 text-white p-12 text-center">
            <h1 class="text-4xl font-bold mb-4"><?php _e('BooAdmin'); ?></h1>
            <p class="text-lg opacity-90"><?php _e('一个现代化、简洁且强大的 Typecho 后台主题'); ?></p>
        </div>
        <div class="absolute bottom-6 text-white/50 text-xs">
            &copy; <?php echo date('Y'); ?> Typecho Team.
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="w-full md:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-16 bg-white overflow-y-auto">
        <div class="w-full max-w-md space-y-8">
            <div class="text-center md:text-left">
                <h2 class="text-3xl font-bold text-gray-900 mb-2"><?php _e('欢迎回来'); ?></h2>
                <p class="text-gray-500"><?php _e('请登录您的账号以继续'); ?></p>
            </div>

            <form action="<?php $options->loginAction(); ?>" method="post" name="login" role="form" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1"><?php _e('用户名'); ?></label>
                    <input type="text" id="name" name="name" value="<?php echo $rememberName; ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-discord-accent/50 focus:border-discord-accent transition-all" required autofocus placeholder="<?php _e('请输入用户名或邮箱'); ?>" />
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700"><?php _e('密码'); ?></label>
                        <div class="text-sm">
                            <?php 
                            $activates = \Typecho\Plugin::export();
                            $hasPassport = false;
                            if (isset($activates['activated']) && in_array('Passport', array_keys($activates['activated']))) {
                                $hasPassport = true;
                                echo '<a href="' . \Typecho\Common::url('passport/forgot', $options->index) . '" class="font-medium text-discord-accent hover:text-discord-accent/80">' . _t('忘记密码？') . '</a>';
                            }
                            ?>
                        </div>
                    </div>
                    <input type="password" id="password" name="password" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-discord-accent/50 focus:border-discord-accent transition-all" required placeholder="<?php _e('请输入密码'); ?>" />
                    
                    <?php if (!$hasPassport): ?>
                    <p class="mt-2 text-xs text-gray-400">
                        <?php _e('未安装找回密码插件？'); ?> 
                        <a href="https://github.com/little-gt/PLUGION-Passport" target="_blank" class="text-discord-accent hover:underline"><?php _e('点击获取 Passport'); ?></a>
                    </p>
                    <?php endif; ?>
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" value="1" class="h-4 w-4 text-discord-accent focus:ring-discord-accent border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        <?php _e('下次自动登录'); ?>
                    </label>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-discord-accent hover:bg-discord-accent/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-discord-accent transition-all transform hover:-translate-y-0.5">
                        <?php _e('登 录'); ?>
                    </button>
                    <input type="hidden" name="referer" value="<?php echo htmlspecialchars($request->get('referer')); ?>" />
                </div>
            </form>

            <?php if ($options->allowRegister): ?>
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500"><?php _e('或者'); ?></span>
                </div>
            </div>

            <div class="text-center">
                <a href="<?php $options->registerUrl(); ?>" class="text-sm font-medium text-discord-accent hover:text-discord-accent/80 hover:underline">
                    <?php _e('创建一个新账号'); ?>
                </a>
            </div>
            <?php endif; ?>
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
