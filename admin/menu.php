<?php if (!defined('__TYPECHO_ADMIN__')) exit; ?>
<!-- Sidebar -->
<aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 flex flex-col transition-all duration-300 transform md:translate-x-0 fixed md:relative z-20 h-full shadow-sm" id="sidebar">
    <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100 bg-white">
        <h1 class="text-xl font-bold text-discord-accent flex items-center">
             <div class="w-8 h-8 rounded bg-discord-accent text-white flex items-center justify-center mr-2">
                 <i class="fas fa-pen-nib text-sm"></i>
             </div>
            <span class="sidebar-text text-gray-800 tracking-tight">Typecho</span>
        </h1>
        <button id="sidebar-toggle" class="md:hidden text-gray-400 hover:text-gray-600 focus:outline-none">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 custom-scrollbar">
        <ul class="space-y-1 px-3">
            <!-- Dashboard -->
            <li>
                <a href="<?php $options->adminUrl('index.php'); ?>" class="flex items-center px-3 py-2 text-gray-600 <?php if($menu->current == 'index.php') echo 'bg-blue-50 text-discord-accent'; else echo 'hover:bg-gray-100 hover:text-gray-900'; ?> rounded-md font-medium transition-all group">
                    <i class="fas fa-tachometer-alt w-5 text-center mr-3 text-sm opacity-80"></i>
                    <span class="sidebar-text"><?php _e('控制台'); ?></span>
                </a>
            </li>
            
            <!-- Create -->
            <li class="mt-5 mb-2 px-3 text-xs font-bold text-gray-400 uppercase tracking-wider sidebar-text"><?php _e('撰写'); ?></li>
            <li>
                <a href="<?php $options->adminUrl('write-post.php'); ?>" class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors <?php if($menu->current == 'write-post.php') echo 'bg-blue-50 text-discord-accent'; ?>">
                    <i class="fas fa-pen-fancy w-5 text-center mr-3 text-sm opacity-80"></i>
                    <span class="sidebar-text"><?php _e('撰写文章'); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php $options->adminUrl('write-page.php'); ?>" class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors <?php if($menu->current == 'write-page.php') echo 'bg-blue-50 text-discord-accent'; ?>">
                    <i class="fas fa-file-alt w-5 text-center mr-3 text-sm opacity-80"></i>
                    <span class="sidebar-text"><?php _e('创建页面'); ?></span>
                </a>
            </li>

            <!-- Content -->
            <li class="mt-5 mb-2 px-3 text-xs font-bold text-gray-400 uppercase tracking-wider sidebar-text"><?php _e('管理'); ?></li>
            <li>
                <a href="<?php $options->adminUrl('manage-posts.php'); ?>" class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors <?php if($menu->current == 'manage-posts.php') echo 'bg-blue-50 text-discord-accent'; ?>">
                    <i class="fas fa-layer-group w-5 text-center mr-3 text-sm opacity-80"></i>
                    <span class="sidebar-text"><?php _e('文章'); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php $options->adminUrl('manage-pages.php'); ?>" class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors <?php if($menu->current == 'manage-pages.php') echo 'bg-blue-50 text-discord-accent'; ?>">
                    <i class="fas fa-file w-5 text-center mr-3 text-sm opacity-80"></i>
                    <span class="sidebar-text"><?php _e('页面'); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php $options->adminUrl('manage-comments.php'); ?>" class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors <?php if($menu->current == 'manage-comments.php') echo 'bg-blue-50 text-discord-accent'; ?>">
                    <i class="fas fa-comments w-5 text-center mr-3 text-sm opacity-80"></i>
                    <span class="sidebar-text"><?php _e('评论'); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php $options->adminUrl('manage-medias.php'); ?>" class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors <?php if($menu->current == 'manage-medias.php') echo 'bg-blue-50 text-discord-accent'; ?>">
                    <i class="fas fa-images w-5 text-center mr-3 text-sm opacity-80"></i>
                    <span class="sidebar-text"><?php _e('文件'); ?></span>
                </a>
            </li>

            <!-- Data -->
            <li class="mt-5 mb-2 px-3 text-xs font-bold text-gray-400 uppercase tracking-wider sidebar-text"><?php _e('数据'); ?></li>
            <li>
                <a href="<?php $options->adminUrl('manage-categories.php'); ?>" class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors <?php if($menu->current == 'manage-categories.php') echo 'bg-blue-50 text-discord-accent'; ?>">
                    <i class="fas fa-folder w-5 text-center mr-3 text-sm opacity-80"></i>
                    <span class="sidebar-text"><?php _e('分类'); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php $options->adminUrl('manage-tags.php'); ?>" class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors <?php if($menu->current == 'manage-tags.php') echo 'bg-blue-50 text-discord-accent'; ?>">
                    <i class="fas fa-tags w-5 text-center mr-3 text-sm opacity-80"></i>
                    <span class="sidebar-text"><?php _e('标签'); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php $options->adminUrl('manage-users.php'); ?>" class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors <?php if($menu->current == 'manage-users.php') echo 'bg-blue-50 text-discord-accent'; ?>">
                    <i class="fas fa-users w-5 text-center mr-3 text-sm opacity-80"></i>
                    <span class="sidebar-text"><?php _e('用户'); ?></span>
                </a>
            </li>

            <!-- Settings -->
            <?php if ($user->pass('administrator', true)): ?>
            <li class="mt-5 mb-2 px-3 text-xs font-bold text-gray-400 uppercase tracking-wider sidebar-text"><?php _e('系统'); ?></li>
            <li>
                <a href="<?php $options->adminUrl('themes.php'); ?>" class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors <?php if($menu->current == 'themes.php') echo 'bg-blue-50 text-discord-accent'; ?>">
                    <i class="fas fa-paint-brush w-5 text-center mr-3 text-sm opacity-80"></i>
                    <span class="sidebar-text"><?php _e('外观'); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php $options->adminUrl('plugins.php'); ?>" class="flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors <?php if($menu->current == 'plugins.php') echo 'bg-blue-50 text-discord-accent'; ?>">
                    <i class="fas fa-plug w-5 text-center mr-3 text-sm opacity-80"></i>
                    <span class="sidebar-text"><?php _e('插件'); ?></span>
                </a>
            </li>
            <li>
                 <div class="relative group-settings">
                    <button class="w-full flex items-center px-3 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors focus:outline-none justify-between <?php if(in_array($menu->current, ['options-general.php', 'options-discussion.php', 'options-reading.php', 'options-permalink.php', 'backup.php'])) echo 'bg-blue-50 text-discord-accent'; ?>" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.fa-chevron-right').classList.toggle('rotate-90')">
                        <div class="flex items-center">
                            <i class="fas fa-cog w-5 text-center mr-3 text-sm opacity-80"></i>
                            <span class="sidebar-text"><?php _e('设置'); ?></span>
                        </div>
                        <i class="fas fa-chevron-right text-xs transition-transform duration-200 <?php if(in_array($menu->current, ['options-general.php', 'options-discussion.php', 'options-reading.php', 'options-permalink.php', 'backup.php'])) echo 'rotate-90'; ?>"></i>
                    </button>
                    <ul class="mt-1 ml-2 pl-6 space-y-1 border-l-2 border-gray-100 <?php if(!in_array($menu->current, ['options-general.php', 'options-discussion.php', 'options-reading.php', 'options-permalink.php', 'backup.php'])) echo 'hidden'; ?>">
                        <li><a href="<?php $options->adminUrl('options-general.php'); ?>" class="block px-2 py-1.5 text-sm text-gray-500 hover:text-discord-accent <?php if($menu->current == 'options-general.php') echo 'text-discord-accent font-medium'; ?>"><?php _e('基本'); ?></a></li>
                        <li><a href="<?php $options->adminUrl('options-discussion.php'); ?>" class="block px-2 py-1.5 text-sm text-gray-500 hover:text-discord-accent <?php if($menu->current == 'options-discussion.php') echo 'text-discord-accent font-medium'; ?>"><?php _e('评论'); ?></a></li>
                        <li><a href="<?php $options->adminUrl('options-reading.php'); ?>" class="block px-2 py-1.5 text-sm text-gray-500 hover:text-discord-accent <?php if($menu->current == 'options-reading.php') echo 'text-discord-accent font-medium'; ?>"><?php _e('阅读'); ?></a></li>
                        <li><a href="<?php $options->adminUrl('options-permalink.php'); ?>" class="block px-2 py-1.5 text-sm text-gray-500 hover:text-discord-accent <?php if($menu->current == 'options-permalink.php') echo 'text-discord-accent font-medium'; ?>"><?php _e('永久链接'); ?></a></li>
                         <li><a href="<?php $options->adminUrl('backup.php'); ?>" class="block px-2 py-1.5 text-sm text-gray-500 hover:text-discord-accent <?php if($menu->current == 'backup.php') echo 'text-discord-accent font-medium'; ?>"><?php _e('备份'); ?></a></li>
                    </ul>
                 </div>
            </li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="p-4 border-t border-gray-100 bg-white">
        <div class="flex items-center group cursor-pointer hover:bg-gray-50 p-2 rounded-md transition-colors">
            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-discord-accent to-blue-400 flex items-center justify-center text-white font-bold shrink-0 shadow-sm">
                <?php echo strtoupper(substr($user->screenName, 0, 1)); ?>
            </div>
            <div class="ml-3 overflow-hidden sidebar-text">
                <p class="text-sm font-semibold text-gray-800 truncate"><a href="<?php $options->adminUrl('profile.php'); ?>"><?php $user->screenName(); ?></a></p>
                <p class="text-xs text-gray-500 truncate"><?php echo $user->group; ?></p>
            </div>
            <a href="<?php $options->logoutUrl(); ?>" class="ml-auto text-gray-400 hover:text-red-500 sidebar-text p-2 rounded-full hover:bg-red-50 transition-colors" title="<?php _e('登出'); ?>">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</aside>

<!-- Overlay for mobile sidebar -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden md:hidden"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const overlay = document.getElementById('sidebar-overlay');
    const mobileMenuBtn = document.getElementById('mobile-menu-btn'); // Will be added in header

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.classList.add('sidebar-open');
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('sidebar-open');
    }

    function toggleSidebar() {
        if (sidebar.classList.contains('-translate-x-full')) {
            openSidebar();
        } else {
            closeSidebar();
        }
    }

    if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);
    if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleSidebar);

    // Initial check for mobile
    if (window.innerWidth < 768) {
        closeSidebar();
    }

    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('sidebar-open');
        } else {
            closeSidebar();
        }
    });
});
</script>

