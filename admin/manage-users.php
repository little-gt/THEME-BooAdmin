<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$users = \Widget\Users\Admin::alloc();
?>
<main class="flex-1 flex flex-col overflow-hidden bg-discord-light">
    <!-- Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-10">
        <div class="flex items-center text-discord-muted">
             <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-users mr-2 hidden md:inline"></i>
            <span class="font-medium text-discord-text"><?php _e('管理用户'); ?></span>
        </div>
        
        <div class="flex items-center space-x-4">
            <a href="<?php $options->adminUrl('user.php'); ?>" class="px-3 py-1.5 bg-discord-accent text-white rounded hover:bg-discord-accent-hover transition-colors text-sm font-medium">
                <i class="fas fa-plus mr-1"></i> <?php _e('新增'); ?>
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
            
            <!-- Filters -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                <div class="flex items-center space-x-2 text-sm text-gray-500">
                    <?php if ('' != $request->keywords): ?>
                        <a href="<?php $options->adminUrl('manage-users.php'); ?>" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 transition-colors"><?php _e('&laquo; 取消筛选'); ?></a>
                    <?php endif; ?>
                </div>

                <form method="get" class="flex flex-wrap items-center gap-2">
                     <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="keywords" value="<?php echo htmlspecialchars($request->keywords ?? ''); ?>" placeholder="<?php _e('请输入关键字'); ?>" class="pl-9 pr-3 py-1.5 bg-white border border-gray-300 rounded text-sm focus:outline-none focus:border-discord-accent shadow-sm w-48 md:w-64">
                    </div>
                    <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 transition-colors text-sm font-medium"><?php _e('筛选'); ?></button>
                </form>
            </div>

            <!-- User List -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <form method="post" name="manage_users" class="operate-form">
                    <div class="p-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 operate-bar">
                         <div class="flex items-center space-x-2">
                             <label class="flex items-center space-x-2 text-sm text-gray-500 cursor-pointer select-none">
                                 <input type="checkbox" class="typecho-table-select-all rounded text-discord-accent focus:ring-discord-accent border-gray-300">
                                 <span><?php _e('全选'); ?></span>
                             </label>
                             <div class="relative group">
                                <button type="button" class="btn-dropdown-toggle px-3 py-1 text-xs font-medium bg-white border border-gray-300 rounded hover:bg-gray-50 text-gray-700 shadow-sm flex items-center">
                                    <?php _e('选中项'); ?> <i class="fas fa-chevron-down ml-1"></i>
                                </button>
                                <div class="dropdown-menu absolute left-0 mt-1 w-40 bg-white rounded-md shadow-lg border border-gray-100 py-1 hidden group-hover:block z-50">
                                    <a lang="<?php _e('你确认要删除这些用户吗?'); ?>" href="<?php $security->index('/action/users-edit?do=delete'); ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"><?php _e('删除'); ?></a>
                                </div>
                             </div>
                         </div>
                    </div>

                    <div class="table-wrapper" data-table-scroll>
                    <table class="w-full text-left border-collapse typecho-list-table draggable">
                        <thead>
                            <tr class="text-xs font-bold text-gray-500 uppercase border-b border-gray-100 bg-gray-50/50 nodrag">
                                <th class="w-10 pl-4 py-3"></th>
                                <th class="w-16 py-3 text-center"><i class="fas fa-edit"></i></th>
                                <th class="py-3"><?php _e('用户名'); ?></th>
                                <th class="py-3 hidden md:table-cell"><?php _e('昵称'); ?></th>
                                <th class="py-3 hidden md:table-cell"><?php _e('电子邮件'); ?></th>
                                <th class="py-3 pr-4 text-right"><?php _e('用户组'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if ($users->have()): ?>
                                <?php while ($users->next()): ?>
                                    <tr id="user-<?php $users->uid(); ?>" class="group hover:bg-gray-50 transition-colors">
                                        <td class="pl-4 py-3">
                                            <input type="checkbox" value="<?php $users->uid(); ?>" name="uid[]" class="rounded text-discord-accent focus:ring-discord-accent border-gray-300">
                                        </td>
                                        <td class="py-3 text-center">
                                            <a href="<?php $options->adminUrl('manage-posts.php?__typecho_all_posts=off&uid=' . $users->uid); ?>" 
                                               class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium <?php echo $users->postsNum > 0 ? 'bg-discord-accent text-white' : 'bg-gray-100 text-gray-500'; ?>">
                                                <?php $users->postsNum(); ?>
                                            </a>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3 text-gray-500 text-xs font-bold overflow-hidden">
                                                    <?php if ($users->mail): ?>
                                                        <img src="<?php echo \Typecho\Common::gravatarUrl($users->mail, 64, 'X', 'mm', $request->isSecure()); ?>" alt="<?php $users->screenName(); ?>" class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <i class="fas fa-user"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <a href="<?php $options->adminUrl('user.php?uid=' . $users->uid); ?>" class="text-discord-text font-medium hover:text-discord-accent transition-colors block">
                                                        <?php $users->name(); ?>
                                                    </a>
                                                    <a href="<?php $users->permalink(); ?>" target="_blank" class="text-gray-400 hover:text-discord-accent opacity-0 group-hover:opacity-100 transition-opacity text-xs" title="<?php _e('浏览 %s', $users->screenName); ?>"><i class="fas fa-external-link-alt"></i> <?php _e('主页'); ?></a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 hidden md:table-cell text-sm text-gray-600">
                                            <?php $users->screenName(); ?>
                                        </td>
                                        <td class="py-3 hidden md:table-cell text-sm text-gray-600">
                                            <?php if ($users->mail): ?>
                                                <a href="mailto:<?php $users->mail(); ?>" class="hover:text-discord-accent"><?php $users->mail(); ?></a>
                                            <?php else: ?>
                                                <span class="text-gray-400"><?php _e('暂无'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 pr-4 text-left text-sm">
                                            <?php 
                                            $groupClass = 'bg-gray-100 text-gray-500';
                                            $groupName = '';
                                            switch ($users->group) {
                                                case 'administrator':
                                                    $groupClass = 'bg-red-100 text-red-600';
                                                    $groupName = _t('管理员');
                                                    break;
                                                case 'editor':
                                                    $groupClass = 'bg-blue-100 text-blue-600';
                                                    $groupName = _t('编辑');
                                                    break;
                                                case 'contributor':
                                                    $groupClass = 'bg-green-100 text-green-600';
                                                    $groupName = _t('贡献者');
                                                    break;
                                                case 'subscriber':
                                                    $groupClass = 'bg-yellow-100 text-yellow-600';
                                                    $groupName = _t('关注者');
                                                    break;
                                                case 'visitor':
                                                    $groupClass = 'bg-gray-100 text-gray-500';
                                                    $groupName = _t('访问者');
                                                    break;
                                                default:
                                                    $groupClass = 'bg-gray-100 text-gray-500';
                                                    $groupName = $users->group;
                                                    break;
                                            } 
                                            ?>
                                            <span class="px-2 py-0.5 rounded text-xs font-medium <?php echo $groupClass; ?>"><?php echo $groupName; ?></span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        <div class="mb-2 text-4xl text-gray-300"><i class="fas fa-users"></i></div>
                                        <?php _e('没有找到任何用户'); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    </div>
                </form>
            </div>
            
            <?php if ($users->have()): ?>
                <div class="mt-4 flex justify-end">
                     <?php $users->pageNav('&laquo;', '&raquo;', 1, '...', array(
                         'wrapTag' => 'ul', 
                         'wrapClass' => 'flex items-center space-x-1 typecho-pager list-none', 
                         'itemTag' => 'li', 
                         'textTag' => 'span', 
                         'currentClass' => 'current', 
                         'prevClass' => 'prev', 
                         'nextClass' => 'next'
                     )); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Footer在main内部 -->
    <?php include 'copyright.php'; ?>
</main>
<style>
.typecho-pager li a, .typecho-pager li span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    border-radius: 6px;
    background-color: white;
    color: #4b5563;
    font-size: 0.875rem;
    border: 1px solid #e5e7eb;
    transition: all 0.2s;
    text-decoration: none;
}
.typecho-pager li a:hover {
    background-color: #f3f4f6;
    color: #5865F2;
    border-color: #d1d5db;
}
.typecho-pager li.current span {
    background-color: #5865F2;
    color: white;
    border-color: #5865F2;
    font-weight: 600;
}
</style>

<?php
include 'common-js.php';
include 'table-js.php';
?>
