<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();
?>

<main class="flex-1 flex flex-col overflow-hidden bg-discord-light">
    <!-- Top Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10">
        <div class="flex items-center text-discord-muted">
            <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-user-circle mr-2 hidden md:inline"></i>
            <span class="mx-2 hidden md:inline">/</span>
            <span class="font-medium text-discord-text"><?php _e('个人设置'); ?></span>
        </div>
        <div class="flex items-center space-x-4">
            <a href="<?php $options->siteUrl(); ?>" class="text-discord-muted hover:text-discord-accent transition-colors" title="<?php _e('查看网站'); ?>" target="_blank">
                <i class="fas fa-globe"></i>
            </a>
            <a href="<?php $options->adminUrl('profile.php'); ?>" class="text-discord-accent transition-colors" title="<?php _e('个人资料'); ?>">
                <i class="fas fa-user-circle"></i>
            </a>
        </div>
    </header>

    <!-- Content Area -->
    <div class="flex-1 overflow-y-auto p-4 md:p-8">
        <div class="w-full max-w-none mx-auto">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Sidebar / User Info Card -->
                <div class="lg:col-span-4 xl:col-span-3">
                    <div class="bg-white p-6 text-center border border-gray-100">
                        <div class="relative inline-block mb-4 group">
                            <div class="w-32 h-32 overflow-hidden mx-auto border-4 border-discord-light">
                                <img src="<?php echo \Typecho\Common::gravatarUrl($user->mail, 220, 'X', 'mm', $request->isSecure()); ?>" alt="<?php $user->screenName(); ?>" class="w-full h-full object-cover">
                            </div>
                            <a href="https://gravatar.com/" target="_blank" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-white text-sm font-medium">
                                <i class="fas fa-camera mr-1"></i> <?php _e('修改头像'); ?>
                            </a>
                        </div>
                        
                        <h2 class="text-xl font-bold text-discord-text"><?php $user->screenName(); ?></h2>
                        <p class="text-discord-muted text-sm mt-1"><?php $user->name(); ?></p>
                        
                        <div class="mt-6 border-t border-gray-100 pt-4 text-left space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-discord-muted"><?php _e('用户组'); ?></span>
                                <span class="bg-discord-accent text-white px-2 py-0.5 text-xs font-medium capitalize"><?php echo $user->group; ?></span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-discord-muted"><?php _e('文章'); ?></span>
                                <span class="font-medium text-discord-text"><?php echo $stat->myPublishedPostsNum; ?></span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-discord-muted"><?php _e('评论'); ?></span>
                                <span class="font-medium text-discord-text"><?php echo $stat->myPublishedCommentsNum; ?></span>
                            </div>
                            <?php if ($user->logged > 0): ?>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-discord-muted"><?php _e('最后登录'); ?></span>
                                <span class="text-xs text-discord-text" title="<?php echo date('Y-m-d H:i:s', $user->logged); ?>">
                                    <?php $logged = new \Typecho\Date($user->logged); echo $logged->word(); ?>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Main Form Area -->
                <div class="lg:col-span-8 xl:col-span-9 space-y-6">
                    
                    <!-- Profile Settings -->
                    <div class="bg-white p-6 border border-gray-100">
                        <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                            <i class="fas fa-id-card text-discord-accent mr-3 text-lg"></i>
                            <h3 class="text-lg font-bold text-discord-text"><?php _e('个人资料'); ?></h3>
                        </div>
                        
                        <div class="typecho-reform-style">
                            <?php \Widget\Users\Profile::alloc()->profileForm()->render(); ?>
                        </div>
                    </div>

                    <?php if ($user->pass('contributor', true)): ?>
                    <!-- Writing Settings -->
                    <div class="bg-white p-6 border border-gray-100">
                         <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                            <i class="fas fa-pen-fancy text-green-500 mr-3 text-lg"></i>
                            <h3 class="text-lg font-bold text-discord-text"><?php _e('撰写设置'); ?></h3>
                        </div>
                        <div class="typecho-reform-style">
                            <?php \Widget\Users\Profile::alloc()->optionsForm()->render(); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Password Settings -->
                    <div class="bg-white p-6 border border-gray-100">
                         <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                            <i class="fas fa-lock text-red-500 mr-3 text-lg"></i>
                            <h3 class="text-lg font-bold text-discord-text"><?php _e('密码修改'); ?></h3>
                        </div>
                        <div class="typecho-reform-style">
                            <?php \Widget\Users\Profile::alloc()->passwordForm()->render(); ?>
                        </div>
                    </div>
                    
                    <?php \Widget\Users\Profile::alloc()->personalFormList(); ?>

                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer自然跟随内容 -->
    <?php include 'copyright.php'; ?>
</main>

<style>
/* Page-specific message styling */
.typecho-reform-style .message {
    padding: 0.75rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
}
.typecho-reform-style .message.success {
    background-color: #DEF7EC;
    color: #03543F;
}
.typecho-reform-style .message.error {
    background-color: #FDE8E8;
    color: #9B1C1C;
}
</style>

<?php
include 'common-js.php';
include 'form-js.php';
\Typecho\Plugin::factory('admin/profile.php')->call('bottom');
include 'footer.php';
?>
