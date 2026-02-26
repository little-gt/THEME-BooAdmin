<?php
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<main class="flex-1 flex flex-col overflow-hidden bg-discord-light">
    <!-- Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10">
        <div class="flex items-center text-discord-muted">
             <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-link mr-2 hidden md:inline"></i>
            <span class="font-medium text-discord-text"><?php _e('永久链接设置'); ?></span>
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

    <div class="flex-1 overflow-y-auto p-4 md:p-8">
        <div class="w-full max-w-none mx-auto">
            
            <!-- Settings Tabs -->
            <div class="settings-tabs-wrapper mb-6">
            <div class="flex bg-white select-none overflow-x-auto settings-tabs">
                <a href="<?php $options->adminUrl('options-general.php'); ?>" class="flex-1 text-center px-4 py-3 text-sm font-medium transition-all text-gray-500 hover:text-discord-text"><?php _e('基本设置'); ?></a>
                <a href="<?php $options->adminUrl('options-discussion.php'); ?>" class="flex-1 text-center px-4 py-3 text-sm font-medium transition-all text-gray-500 hover:text-discord-text"><?php _e('评论设置'); ?></a>
                <a href="<?php $options->adminUrl('options-reading.php'); ?>" class="flex-1 text-center px-4 py-3 text-sm font-medium transition-all text-gray-500 hover:text-discord-text"><?php _e('阅读设置'); ?></a>
                <a href="<?php $options->adminUrl('options-permalink.php'); ?>" class="flex-1 text-center px-4 py-3 text-sm font-medium transition-all active"><?php _e('永久链接'); ?></a>
            </div>
            </div>

            <div class="bg-white border border-gray-200 p-6">
                <?php \Widget\Options\Permalink::alloc()->form()->render(); ?>
            </div>
        </div>
    </div>
    
    <!-- Footer自然跟随内容 -->
    <?php include 'copyright.php'; ?>
</main>

<style>
/* Custom styles for permalink page specifically */
.typecho-option span { display: inline-block; margin-right: 0.5rem; color: #6b7280; font-family: monospace; }
</style>

<?php
include 'common-js.php';
include 'form-js.php';
?>

<?php include 'footer.php'; ?>
