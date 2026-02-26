<?php
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<main class="flex-1 flex flex-col overflow-hidden bg-discord-light">
    <!-- Top Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10">
        <div class="flex items-center text-discord-muted">
            <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-arrow-up mr-2 hidden md:inline"></i>
            <span class="mx-2 hidden md:inline">/</span>
            <span class="font-medium text-discord-text"><?php _e('系统升级'); ?></span>
        </div>
        <div class="flex items-center space-x-4">
            <a href="<?php $options->siteUrl(); ?>" class="text-discord-muted hover:text-discord-accent transition-colors" title="<?php _e('查看网站'); ?>" target="_blank">
                <i class="fas fa-globe"></i>
            </a>
            <a href="<?php $options->adminUrl('profile.php'); ?>" class="text-discord-muted hover:text-discord-accent transition-colors" title="<?php _e('个人资料'); ?>">
                <i class="fas fa-user-circle"></i>
            </a>
        </div>
    </header>

    <!-- Content Area -->
    <div class="flex-1 overflow-y-auto p-4 md:p-8">
        <div class="w-full max-w-none mx-auto">
            <div class="bg-white border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-discord-text flex items-center">
                        <i class="fas fa-arrow-up text-discord-accent mr-2"></i><?php _e('系统升级'); ?>
                    </h2>
                </div>
                <div class="p-6">
                    <div id="typecho-welcome">
                        <form action="<?php echo $security->getTokenUrl(
                            \Typecho\Router::url('do', ['action' => 'upgrade', 'widget' => 'Upgrade'],
                                \Typecho\Common::url('index.php', $options->rootUrl))); ?>" method="post">
                            <h3 class="text-lg font-semibold text-discord-text mb-4"><?php _e('检测到新版本!'); ?></h3>
                            <ul class="space-y-2 mb-6">
                                <li class="text-discord-text"><?php _e('您已经更新了系统程序, 我们还需要执行一些后续步骤来完成升级'); ?></li>
                                <li class="text-discord-text"><?php _e('此程序将把您的系统从 <strong>%s</strong> 升级到 <strong>%s</strong>', $options->version, \Typecho\Common::VERSION); ?></li>
                                <li class="text-discord-text"><strong
                                        class="text-red-500"><?php _e('在升级之前强烈建议先<a href="%s">备份您的数据</a>', \Typecho\Common::url('backup.php', $options->adminUrl)); ?></strong>
                                </li>
                            </ul>
                            <p>
                                <button class="px-6 py-2 bg-discord-accent text-white font-medium hover:bg-blue-600 transition-colors" type="submit"><?php _e('完成升级 &raquo;'); ?></button>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer自然跟随内容 -->
    <?php include 'copyright.php'; ?>
</main>

<?php
include 'common-js.php';
?>
<script>
    (function () {
        if (window.sessionStorage) {
            sessionStorage.removeItem('update');
        }
    })();
</script>
<?php include 'footer.php'; ?>
