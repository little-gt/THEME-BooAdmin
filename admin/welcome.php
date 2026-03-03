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
            <i class="fas fa-rocket mr-2 hidden md:inline"></i>
            <span class="mx-2 hidden md:inline">/</span>
            <span class="font-medium text-discord-text"><?php _e('欢迎页'); ?></span>
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
            <div class="bg-white p-6 mb-6 flex items-center justify-between border-l-4 border-discord-accent">
                <div>
                    <h2 class="text-2xl font-bold text-discord-text mb-2"><?php _e('欢迎使用 %s', $options->title); ?></h2>
                    <p class="text-discord-muted"><?php _e('开始您的创作之旅，以下是推荐的首次操作。'); ?></p>
                </div>
                <div class="w-14 h-14 bg-discord-accent text-white flex items-center justify-center text-xl">
                    <i class="fas fa-rocket"></i>
                </div>
            </div>

            <div class="bg-white border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-discord-text flex items-center">
                        <i class="fas fa-compass text-discord-accent mr-2"></i>
                        <?php _e('快速开始'); ?>
                    </h3>
                </div>

                <div class="p-6">
                    <form action="<?php $options->adminUrl(); ?>" method="get" class="space-y-4">
                        <div class="space-y-3">
                            <a href="<?php $options->adminUrl('profile.php#change-password'); ?>" class="block w-full p-4 border border-gray-200 hover:border-discord-accent hover:bg-blue-50 transition-all group flex items-center">
                                <div class="w-10 h-10 bg-red-100 text-red-500 flex items-center justify-center mr-4 group-hover:bg-discord-accent group-hover:text-white transition-colors">
                                    <i class="fas fa-key"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-discord-text"><?php _e('更改密码'); ?></h4>
                                    <p class="text-xs text-gray-500"><?php _e('强烈建议更改您的默认密码以保障安全'); ?></p>
                                </div>
                                <i class="fas fa-chevron-right text-gray-300 group-hover:text-discord-accent"></i>
                            </a>

                            <?php if($user->pass('contributor', true)): ?>
                            <a href="<?php $options->adminUrl('write-post.php'); ?>" class="block w-full p-4 border border-gray-200 hover:border-discord-accent hover:bg-blue-50 transition-all group flex items-center">
                                <div class="w-10 h-10 bg-green-100 text-green-500 flex items-center justify-center mr-4 group-hover:bg-discord-accent group-hover:text-white transition-colors">
                                    <i class="fas fa-pen-nib"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-discord-text"><?php _e('撰写文章'); ?></h4>
                                    <p class="text-xs text-gray-500"><?php _e('发布您的第一篇精彩内容'); ?></p>
                                </div>
                                <i class="fas fa-chevron-right text-gray-300 group-hover:text-discord-accent"></i>
                            </a>

                            <a href="<?php $options->siteUrl(); ?>" target="_blank" class="block w-full p-4 border border-gray-200 hover:border-discord-accent hover:bg-blue-50 transition-all group flex items-center">
                                <div class="w-10 h-10 bg-blue-100 text-blue-500 flex items-center justify-center mr-4 group-hover:bg-discord-accent group-hover:text-white transition-colors">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-discord-text"><?php _e('查看站点'); ?></h4>
                                    <p class="text-xs text-gray-500"><?php _e('访问您的网站首页'); ?></p>
                                </div>
                                <i class="fas fa-external-link-alt text-gray-300 group-hover:text-discord-accent text-sm"></i>
                            </a>
                            <?php else: ?>
                            <a href="<?php $options->siteUrl(); ?>" target="_blank" class="block w-full p-4 border border-gray-200 hover:border-discord-accent hover:bg-blue-50 transition-all group flex items-center">
                                <div class="w-10 h-10 bg-blue-100 text-blue-500 flex items-center justify-center mr-4 group-hover:bg-discord-accent group-hover:text-white transition-colors">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-discord-text"><?php _e('查看站点'); ?></h4>
                                    <p class="text-xs text-gray-500"><?php _e('访问网站首页'); ?></p>
                                </div>
                                <i class="fas fa-external-link-alt text-gray-300 group-hover:text-discord-accent text-sm"></i>
                            </a>
                            <?php endif; ?>
                        </div>

                        <div class="pt-6 mt-2 border-t border-gray-100">
                            <button type="submit" class="w-full py-3 bg-discord-accent text-white font-bold hover:bg-blue-600 transition-colors">
                                <?php _e('进入后台管理 &raquo;'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-6 text-xs text-gray-400">
                &copy; <?php echo date('Y'); ?> Typecho Team. All Rights Reserved.
            </div>
        </div>
    </div>

    <!-- Footer自然跟随内容 -->
    <?php include 'copyright.php'; ?>
</main>

<?php
include 'common-js.php';
include 'footer.php';
?>
