<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();
$pages = \Widget\Contents\Page\Admin::alloc();
?>
<main class="flex-1 flex flex-col overflow-hidden bg-discord-light">
    <!-- Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10">
        <div class="flex items-center text-discord-muted">
             <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-file-alt mr-2 hidden md:inline"></i>
            <span class="font-medium text-discord-text"><?php _e('管理独立页面'); ?></span>
        </div>
        
        <div class="flex items-center space-x-4">
             <a href="<?php $options->adminUrl('write-page.php'); ?>" class="px-3 py-1.5 bg-discord-accent text-white text-sm font-medium hover:bg-blue-600 transition-colors">
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
                    <?php $pages->backLink(); ?>
                    <?php if ('' != $request->keywords): ?>
                        <a href="<?php $options->adminUrl('manage-pages.php'); ?>" class="px-2 py-1 bg-gray-200 hover:bg-gray-300 transition-colors"><?php _e('&laquo; 取消筛选'); ?></a>
                    <?php endif; ?>
                </div>

                <form method="get" class="flex flex-wrap items-center gap-2">
                    <div class="flex items-center bg-white border border-gray-100 focus-within:border-discord-accent transition-colors search-input-container">
                        <div class="px-3 flex items-center">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="keywords" value="<?php echo htmlspecialchars($request->keywords ?? ''); ?>" placeholder="<?php _e('请输入关键字'); ?>" class="pr-3 py-1.5 bg-white text-sm focus:outline-none w-48 md:w-64 border-0">
                    </div>
                    <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors text-sm font-medium"><?php _e('筛选'); ?></button>
                </form>
            </div>

            <!-- Page List -->
            <div class="bg-white border border-gray-200 overflow-hidden">
                <form method="post" name="manage_pages" class="operate-form">
                    <div class="p-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 operate-bar">
                         <div class="flex items-center space-x-2">
                             <label class="flex items-center space-x-2 text-sm text-gray-500 cursor-pointer select-none">
                                 <input type="checkbox" class="typecho-table-select-all text-discord-accent focus:ring-discord-accent border-gray-300">
                                 <span><?php _e('全选'); ?></span>
                             </label>
                             <div class="relative group">
                                <button type="button" class="btn-dropdown-toggle px-3 py-1 text-xs font-medium bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 flex items-center">
                                    <?php _e('选中项'); ?> <i class="fas fa-chevron-down ml-1"></i>
                                </button>
                                <div class="dropdown-menu absolute left-0 mt-1 w-40 bg-white border border-gray-100 py-1 hidden group-hover:block z-50">
                                    <a href="<?php $security->index('/action/contents-page-edit?do=delete'); ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"><?php _e('删除'); ?></a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <a href="<?php $security->index('/action/contents-page-edit?do=mark&status=publish'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><?php _e('标记为公开'); ?></a>
                                    <a href="<?php $security->index('/action/contents-page-edit?do=mark&status=hidden'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><?php _e('标记为隐藏'); ?></a>
                                </div>
                             </div>
                         </div>
                         <div class="view-toggle">
                             <button type="button" class="btn-table-view active" title="<?php _e('表格视图'); ?>">
                                 <i class="fas fa-table"></i>
                                 <span class="hidden sm:inline"><?php _e('表格'); ?></span>
                             </button>
                             <button type="button" class="btn-card-view" title="<?php _e('卡片视图'); ?>">
                                 <i class="fas fa-th-large"></i>
                                 <span class="hidden sm:inline"><?php _e('卡片'); ?></span>
                             </button>
                         </div>
                    </div>

                    <div class="table-wrapper" data-table-scroll>
                    <table class="w-full text-left border-collapse typecho-list-table">
                        <thead>
                            <tr class="text-xs font-bold text-gray-500 uppercase border-b border-gray-100 bg-gray-50/50 nodrag">
                                <th class="w-10 pl-4 py-3"></th>
                                <th class="w-16 py-3 text-center"><i class="fas fa-comment-alt"></i></th>
                                <th class="py-3"><?php _e('标题'); ?></th>
                                <th class="py-3"><?php _e('子页面'); ?></th>
                                <th class="py-3 hidden md:table-cell"><?php _e('作者'); ?></th>
                                <th class="py-3 pr-4 text-right"><?php _e('日期'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if ($pages->have()): ?>
                                <?php 
                                // Store pages data for card view
                                $pagesData = [];
                                while ($pages->next()): 
                                    // Store all necessary data in an array
                                    $pagesData[] = [
                                        'cid' => $pages->cid,
                                        'title' => $pages->title,
                                        'type' => $pages->type,
                                        'status' => $pages->status,
                                        'revision' => $pages->revision,
                                        'modified' => $pages->modified,
                                        'created' => $pages->created,
                                        'commentsNum' => $pages->commentsNum,
                                        'permalink' => $pages->permalink,
                                        'author' => [
                                            'uid' => $pages->author->uid,
                                            'screenName' => $pages->author->screenName
                                        ],
                                        'children' => $pages->children
                                    ];
                                ?>
                                    <tr id="<?php $pages->theId(); ?>" class="group hover:bg-gray-50 transition-colors">
                                        <td class="pl-4 py-3">
                                            <input type="checkbox" value="<?php $pages->cid(); ?>" name="cid[]" class="text-discord-accent focus:ring-discord-accent border-gray-300">
                                        </td>
                                        <td class="py-3 text-center">
                                            <a href="<?php $options->adminUrl('manage-comments.php?cid=' . $pages->cid); ?>" 
                                               class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium <?php echo $pages->commentsNum > 0 ? 'bg-discord-accent text-white' : 'bg-gray-100 text-gray-500'; ?>">
                                                <?php $pages->commentsNum(); ?>
                                            </a>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <a href="<?php $options->adminUrl('write-page.php?cid=' . $pages->cid); ?>" class="text-discord-text font-medium hover:text-discord-accent transition-colors">
                                                    <?php $pages->title(); ?>
                                                </a>
                                                <?php
                                                if ('page_draft' == $pages->type) echo '<span class="ml-2 px-1.5 py-0.5 text-xs bg-yellow-100 text-yellow-700">' . _t('草稿') . '</span>';
                                                elseif ($pages->revision) echo '<span class="ml-2 px-1.5 py-0.5 text-xs bg-green-100 text-green-700">' . _t('有修订') . '</span>';
                                                
                                                if ('hidden' == $pages->status) echo '<span class="ml-2 px-1.5 py-0.5 text-xs bg-gray-200 text-gray-600">' . _t('隐藏') . '</span>';
                                                ?>
                                            </div>
                                            <div class="mt-1 flex items-center space-x-3 text-xs text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="<?php $options->adminUrl('write-page.php?cid=' . $pages->cid); ?>" class="hover:text-discord-accent"><i class="fas fa-edit mr-1"></i><?php _e('编辑'); ?></a>
                                                <?php if ('page_draft' != $pages->type): ?>
                                                    <a href="<?php $pages->permalink(); ?>" target="_blank" class="hover:text-discord-accent"><i class="fas fa-external-link-alt mr-1"></i><?php _e('查看'); ?></a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="py-3 text-sm text-gray-600">
                                            <?php if (count($pages->children) > 0): ?>
                                                <a href="<?php $options->adminUrl('manage-pages.php?parent=' . $pages->cid); ?>" class="text-discord-accent hover:underline bg-discord-light px-2 py-0.5 text-xs font-medium"><?php echo _n('1', '%d', count($pages->children)); ?></a>
                                            <?php else: ?>
                                                <a href="<?php $options->adminUrl('write-page.php?parent=' . $pages->cid); ?>" class="text-gray-400 hover:text-discord-accent text-xs"><i class="fas fa-plus"></i> <?php echo _e('新增'); ?></a>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 hidden md:table-cell text-sm text-gray-600">
                                            <?php $pages->author(); ?>
                                        </td>
                                        <td class="py-3 pr-4 text-right text-sm text-gray-500">
                                            <?php if ('page_draft' == $pages->type || $pages->revision): ?>
                                                <span class="block text-xs text-green-600"><?php $modifyDate = new \Typecho\Date($pages->revision ? $pages->revision['modified'] : $pages->modified); _e('保存于 %s', $modifyDate->word()); ?></span>
                                            <?php else: ?>
                                                <?php $pages->dateWord(); ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        <div class="mb-2 text-4xl text-gray-300"><i class="far fa-file"></i></div>
                                        <?php _e('没有找到任何页面'); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    </div>

                    <!-- Card View Container -->
                    <div class="card-view-container">
                        <?php if (!empty($pagesData)): ?>
                            <?php foreach ($pagesData as $page): ?>
                                <div class="content-card" id="card-<?php echo $page['cid']; ?>">
                                    <input type="checkbox" value="<?php echo $page['cid']; ?>" name="cid[]" class="card-checkbox text-discord-accent focus:ring-discord-accent border-gray-300">
                                    
                                    <div class="card-header">
                                        <div class="flex-1">
                                            <a href="<?php $options->adminUrl('write-page.php?cid=' . $page['cid']); ?>" class="card-title">
                                                <?php echo htmlspecialchars($page['title']); ?>
                                            </a>
                                            <div class="card-badges">
                                                <?php
                                                if ('page_draft' == $page['type']) echo '<span class="px-1.5 py-0.5 text-xs bg-yellow-100 text-yellow-700">' . _t('草稿') . '</span>';
                                                elseif ($page['revision']) echo '<span class="px-1.5 py-0.5 text-xs bg-green-100 text-green-700">' . _t('有修订') . '</span>';
                                                
                                                if ('hidden' == $page['status']) echo '<span class="px-1.5 py-0.5 text-xs bg-gray-200 text-gray-600">' . _t('隐藏') . '</span>';
                                                ?>
                                            </div>
                                        </div>
                                        <a href="<?php $options->adminUrl('manage-comments.php?cid=' . $page['cid']); ?>" 
                                           class="card-comment-badge flex-shrink-0 <?php echo $page['commentsNum'] > 0 ? 'bg-discord-accent text-white' : 'bg-gray-100 text-gray-500'; ?>">
                                            <?php echo $page['commentsNum']; ?>
                                        </a>
                                    </div>
                                    
                                    <div class="card-meta">
                                        <div class="card-meta-item">
                                            <i class="fas fa-user text-gray-400"></i>
                                            <span><?php echo htmlspecialchars($page['author']['screenName']); ?></span>
                                        </div>
                                        <?php if (count($page['children']) > 0): ?>
                                        <div class="card-meta-item">
                                            <i class="fas fa-sitemap text-gray-400"></i>
                                            <a href="<?php $options->adminUrl('manage-pages.php?parent=' . $page['cid']); ?>" class="hover:text-discord-accent">
                                                <?php echo sprintf(_n('1 个子页面', '%d 个子页面', count($page['children'])), count($page['children'])); ?>
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                        <div class="card-meta-item">
                                            <i class="fas fa-clock text-gray-400"></i>
                                            <span>
                                                <?php if ('page_draft' == $page['type'] || $page['revision']): ?>
                                                    <?php $modifyDate = new \Typecho\Date($page['revision'] ? $page['revision']['modified'] : $page['modified']); echo $modifyDate->word(); ?>
                                                <?php else: ?>
                                                    <?php echo (new \Typecho\Date($page['created']))->word(); ?>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="card-actions">
                                        <a href="<?php $options->adminUrl('write-page.php?cid=' . $page['cid']); ?>">
                                            <i class="fas fa-edit"></i> <?php _e('编辑'); ?>
                                        </a>
                                        <?php if ('page_draft' != $page['type']): ?>
                                            <a href="<?php echo $page['permalink']; ?>" target="_blank">
                                                <i class="fas fa-external-link-alt"></i> <?php _e('查看'); ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (count($page['children']) === 0): ?>
                                            <a href="<?php $options->adminUrl('write-page.php?parent=' . $page['cid']); ?>">
                                                <i class="fas fa-plus"></i> <?php _e('新增子页面'); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="px-4 py-8 text-center text-gray-500">
                                <div class="mb-2 text-4xl text-gray-300"><i class="far fa-file"></i></div>
                                <?php _e('没有找到任何页面'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <?php if ($pages->have()): ?>
                
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
    background-color: white;
    color: #4b5563; /* text-gray-600 */
    font-size: 0.875rem; /* text-sm */
    border: 1px solid #e5e7eb; /* border-gray-200 */
    transition: all 0.2s;
    text-decoration: none;
}

.typecho-pager li a:hover {
    background-color: #f3f4f6; /* bg-gray-100 */
    color: #5865F2; /* text-discord-accent */
    border-color: #d1d5db; /* border-gray-300 */
}

.typecho-pager li.current span {
    background-color: #5865F2; /* bg-discord-accent */
    color: white;
    border-color: #5865F2;
    font-weight: 600;
}
</style>

<?php
include 'common-js.php';
include 'table-js.php';
?>

<?php if (!$request->is('keywords')): ?>
    <script type="text/javascript">
        (function () {
            $(document).ready(function () {
                var table = $('.typecho-list-table').tableDnD({
                    onDrop: function () {
                        var ids = [];

                        $('input[type=checkbox]', table).each(function () {
                            ids.push($(this).val());
                        });

                        $.post('<?php $security->index('/action/contents-page-edit?do=sort'); ?>',
                            $.param({cid: ids}));
                    }
                });
            });
        })();
    </script>
<?php endif; ?>

<script>
// Mobile-aware view mode initialization for manage-pages.php
$(document).ready(function() {
    var VIEW_MODE_KEY = 'typecho_list_view_mode';
    var USER_PREFERENCE_KEY = 'typecho_list_view_user_set';
    var MOBILE_BREAKPOINT = 768; // 移动端断点：小于 768px 为移动端
    
    // 检测是否为移动端
    function isMobile() {
        return $(window).width() < MOBILE_BREAKPOINT;
    }
    
    // 获取用户是否手动设置过视图模式
    function hasUserPreference() {
        try {
            return localStorage.getItem(USER_PREFERENCE_KEY) === 'true';
        } catch(e) {
            return false;
        }
    }
    
    // 获取保存的视图模式
    function getSavedViewMode() {
        try {
            return localStorage.getItem(VIEW_MODE_KEY) || null;
        } catch(e) {
            return null;
        }
    }
    
    // 应用视图模式
    function applyViewMode(mode) {
        var $container = $('.operate-form').closest('.bg-white');
        
        if (mode === 'card') {
            $container.addClass('view-mode-card');
            $('.view-toggle .btn-table-view').removeClass('active');
            $('.view-toggle .btn-card-view').addClass('active');
        } else {
            $container.removeClass('view-mode-card');
            $('.view-toggle .btn-table-view').addClass('active');
            $('.view-toggle .btn-card-view').removeClass('active');
        }
    }
    
    // 初始化视图模式
    function initializeViewMode() {
        var savedMode = getSavedViewMode();
        var userHasPreference = hasUserPreference();
        var mobile = isMobile();
        
        // 决策逻辑：
        // 1. 如果用户手动设置过，使用用户的选择
        // 2. 如果没有手动设置过，在移动端默认使用卡片模式
        // 3. 桌面端默认使用表格模式
        var defaultMode = mobile ? 'card' : 'table';
        var finalMode = userHasPreference && savedMode ? savedMode : defaultMode;
        
        console.log('View Mode Init (Pages):', {
            'Screen Width': $(window).width() + 'px',
            'Is Mobile': mobile,
            'User Has Preference': userHasPreference,
            'Saved Mode': savedMode,
            'Final Mode': finalMode
        });
        
        applyViewMode(finalMode);
    }
    
    // 监听用户手动切换视图（由 common-js.php 中的代码触发）
    // 当用户点击切换按钮时，标记为用户已设置偏好
    $('.view-toggle button').on('click', function() {
        try {
            localStorage.setItem(USER_PREFERENCE_KEY, 'true');
            console.log('User preference saved: User manually switched view mode (Pages)');
        } catch(e) {
            // Ignore localStorage errors
        }
    });
    
    // 执行初始化
    if ($('.view-toggle').length > 0) {
        initializeViewMode();
    }
    
    // 监听窗口大小变化（可选）
    // 只在用户没有手动设置偏好时，根据屏幕大小自动调整
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // 只有在用户没有手动设置偏好时才自动调整
            if (!hasUserPreference() && $('.view-toggle').length > 0) {
                var mobile = isMobile();
                var currentMode = mobile ? 'card' : 'table';
                applyViewMode(currentMode);
                
                console.log('Auto-adjusted to', currentMode, 'mode due to window resize (Pages)');
            }
        }, 250); // 防抖，250ms 后才执行
    });
});
</script>


