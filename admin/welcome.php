<?php
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<main class="flex-1 flex flex-col overflow-hidden bg-discord-light items-center justify-center p-4">
    <div class="w-full max-w-lg">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-discord-text mb-2"><?php _e('欢迎使用'); ?></h1>
            <div class="w-16 h-16 rounded-full bg-discord-accent text-white flex items-center justify-center mx-auto mb-4 shadow-lg text-2xl">
                <i class="fas fa-rocket"></i>
            </div>
            <p class="text-discord-muted"><?php _e('Typecho "%s" 管理后台', $options->title); ?></p>
        </div>

        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <div class="p-8">
                <h3 class="text-lg font-bold text-discord-text mb-6 flex items-center justify-center">
                    <?php _e('开始您的创作之旅'); ?>
                </h3>
                
                <form action="<?php $options->adminUrl(); ?>" method="get" class="space-y-4">
                    <div class="space-y-3">
                        <a href="<?php $options->adminUrl('profile.php#change-password'); ?>" class="block w-full p-4 border border-gray-200 rounded-lg hover:border-discord-accent hover:bg-blue-50 transition-all group flex items-center">
                            <div class="w-10 h-10 rounded-full bg-red-100 text-red-500 flex items-center justify-center mr-4 group-hover:bg-discord-accent group-hover:text-white transition-colors">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-discord-text"><?php _e('更改密码'); ?></h4>
                                <p class="text-xs text-gray-500"><?php _e('强烈建议更改您的默认密码以保障安全'); ?></p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-300 group-hover:text-discord-accent"></i>
                        </a>

                        <?php if($user->pass('contributor', true)): ?>
                        <a href="<?php $options->adminUrl('write-post.php'); ?>" class="block w-full p-4 border border-gray-200 rounded-lg hover:border-discord-accent hover:bg-blue-50 transition-all group flex items-center">
                            <div class="w-10 h-10 rounded-full bg-green-100 text-green-500 flex items-center justify-center mr-4 group-hover:bg-discord-accent group-hover:text-white transition-colors">
                                <i class="fas fa-pen-nib"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-discord-text"><?php _e('撰写文章'); ?></h4>
                                <p class="text-xs text-gray-500"><?php _e('发布您的第一篇精彩内容'); ?></p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-300 group-hover:text-discord-accent"></i>
                        </a>

                        <a href="<?php $options->siteUrl(); ?>" target="_blank" class="block w-full p-4 border border-gray-200 rounded-lg hover:border-discord-accent hover:bg-blue-50 transition-all group flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center mr-4 group-hover:bg-discord-accent group-hover:text-white transition-colors">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-discord-text"><?php _e('查看站点'); ?></h4>
                                <p class="text-xs text-gray-500"><?php _e('访问您的网站首页'); ?></p>
                            </div>
                            <i class="fas fa-external-link-alt text-gray-300 group-hover:text-discord-accent text-sm"></i>
                        </a>
                        <?php else: ?>
                        <a href="<?php $options->siteUrl(); ?>" target="_blank" class="block w-full p-4 border border-gray-200 rounded-lg hover:border-discord-accent hover:bg-blue-50 transition-all group flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center mr-4 group-hover:bg-discord-accent group-hover:text-white transition-colors">
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
                        <button type="submit" class="w-full py-3 bg-discord-accent text-white rounded-md font-bold hover:bg-blue-600 transition-colors shadow-sm transform hover:scale-[1.02] duration-200">
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
</main>

<?php
include 'common-js.php';
include 'footer.php';
?>
