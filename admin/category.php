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
            <i class="fas fa-edit mr-2 hidden md:inline"></i>
            <span class="font-medium text-discord-text"><?php _e('编辑分类'); ?></span>
        </div>
        
        <div class="flex items-center space-x-4">
            <a href="<?php $options->adminUrl('manage-categories.php'); ?>" class="px-3 py-1.5 bg-gray-100 text-gray-600 text-sm font-medium hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> <?php _e('返回'); ?>
            </a>
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
            <div class="bg-white border border-gray-200 p-6">
                <?php \Widget\Metas\Category\Edit::alloc()->form()->render(); ?>
            </div>
        </div>
    </div>
    
    <!-- Footer自然跟随内容 -->
    <?php include 'copyright.php'; ?>
</main>

<?php
include 'common-js.php';
include 'form-js.php';
include 'footer.php';
?>
