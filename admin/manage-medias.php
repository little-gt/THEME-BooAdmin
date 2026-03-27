<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();
$attachments = \Widget\Contents\Attachment\Admin::alloc();
?>
<main class="flex-1 flex flex-col overflow-hidden bg-discord-light">
    <!-- Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10">
        <div class="flex items-center text-discord-muted">
             <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-images mr-2 hidden md:inline"></i>
            <span class="mx-2 hidden md:inline">/</span>
            <span class="font-medium text-discord-text"><?php _e('管理文件'); ?></span>
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
            
            <!-- Filters -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                <div class="flex items-center space-x-2 text-sm text-gray-500">
                    <?php if ('' != $request->keywords): ?>
                        <a href="<?php $options->adminUrl('manage-medias.php'); ?>" class="px-2 py-1 bg-gray-200 hover:bg-gray-300 transition-colors"><?php _e('&laquo; 取消筛选'); ?></a>
                    <?php endif; ?>
                </div>

                <form method="get" class="flex flex-wrap items-center gap-2">
                    <div class="flex items-center bg-white border border-gray-100 focus-within:border-discord-accent transition-colors search-input-container">
                        <div class="px-3 flex items-center">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="keywords" value="<?php echo htmlspecialchars($request->keywords ?? ''); ?>" placeholder="<?php _e('请输入关键字'); ?>" class="pr-3 py-1.5 bg-white text-sm focus:outline-none w-48 md:w-64 border-0">
                    </div>
                    <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors text-sm font-medium flex items-center">
                        <i class="fas fa-filter mr-1"></i><?php _e('筛选'); ?>
                    </button>
                </form>
            </div>

            <!-- Media List -->
            <div class="bg-white border border-gray-200 overflow-hidden">
                <form method="post" name="manage_medias" class="operate-form">
                    <div class="p-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 operate-bar">
                         <div class="flex items-center space-x-2">
                             <label class="flex items-center space-x-2 text-sm text-gray-500 cursor-pointer select-none">
                                 <input type="checkbox" class="typecho-table-select-all text-discord-accent focus:ring-discord-accent border-gray-300">
                                 <span><?php _e('全选'); ?></span>
                             </label>
                             <div class="relative group">
                                <button type="button" class="btn-dropdown-toggle px-3 py-1 text-xs font-medium bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 flex items-center">
                                    <i class="fas fa-tasks mr-1"></i><?php _e('选中项'); ?> <i class="fas fa-chevron-down ml-1"></i>
                                </button>
                                <div class="dropdown-menu absolute left-0 mt-1 w-40 bg-white border border-gray-100 py-1 hidden z-50">
                                    <a lang="<?php _e('你确认要删除这些文件吗?'); ?>" href="<?php $security->index('/action/contents-attachment-edit?do=delete'); ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"><i class="fas fa-trash-alt mr-1"></i><?php _e('删除'); ?></a>
                                </div>
                             </div>
                             <button lang="<?php _e('您确认要清理未归档的文件吗?'); ?>" class="px-3 py-1 text-xs font-medium bg-red-50 border border-red-200 hover:bg-red-100 text-red-600 btn-operate flex items-center" href="<?php $security->index('/action/contents-attachment-edit?do=clear'); ?>"><i class="fas fa-broom mr-1"></i><?php _e('清理未归档'); ?></button>
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
                    <table class="w-full text-left border-collapse typecho-list-table draggable">
                        <thead>
                            <tr class="text-xs font-bold text-gray-500 uppercase border-b border-gray-100 bg-gray-50/50 nodrag">
                                <th class="w-10 pl-4 py-3"></th>
                                <th class="w-16 py-3 text-center"><?php _e('评论数'); ?></th>
                                <th class="py-3"><?php _e('文件名'); ?></th>
                                <th class="py-3 hidden md:table-cell"><?php _e('上传者'); ?></th>
                                <th class="py-3 hidden md:table-cell"><?php _e('所属文章'); ?></th>
                                <th class="py-3 pr-4 text-right"><?php _e('发布日期'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if ($attachments->have()): ?>
                                <?php 
                                // Store attachments data for card view
                                $attachmentsData = [];
                                while ($attachments->next()): 
                                    $mime = \Typecho\Common::mimeIconType($attachments->attachment->mime);
                                    // Store all necessary data in an array
                                    $attachmentsData[] = [
                                        'cid' => $attachments->cid,
                                        'title' => $attachments->title,
                                        'mime' => $mime,
                                        'isImage' => $attachments->attachment->isImage,
                                        'url' => $attachments->attachment->url,
                                        'commentsNum' => $attachments->commentsNum,
                                        'author' => [
                                            'screenName' => $attachments->author->screenName
                                        ],
                                        'parentPost' => [
                                            'cid' => $attachments->parentPost->cid ?? null,
                                            'type' => $attachments->parentPost->type ?? null,
                                            'title' => $attachments->parentPost->title ?? null
                                        ],
                                        'created' => $attachments->created
                                    ];
                                ?>
                                    <tr id="<?php $attachments->theId(); ?>" class="group hover:bg-gray-50 transition-colors">
                                        <td class="pl-4 py-3">
                                            <input type="checkbox" value="<?php $attachments->cid(); ?>" name="cid[]" class="text-discord-accent focus:ring-discord-accent border-gray-300">
                                        </td>
                                        <td class="py-3 text-center">
                                            <a href="<?php $options->adminUrl('manage-comments.php?cid=' . $attachments->cid); ?>" 
                                               class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium <?php echo $attachments->commentsNum > 0 ? 'bg-discord-accent text-white' : 'bg-gray-100 text-gray-500'; ?>">
                                                <?php $attachments->commentsNum(); ?>
                                            </a>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <span class="mr-2 text-gray-400"><i class="fas fa-<?php echo 'image' == $mime ? 'image' : 'file'; ?>"></i></span>
                                                <a href="<?php $options->adminUrl('media.php?cid=' . $attachments->cid); ?>" class="text-discord-text font-medium hover:text-discord-accent transition-colors">
                                                    <?php $attachments->title(); ?>
                                                </a>
                                                <a href="<?php $attachments->permalink(); ?>" target="_blank" class="ml-2 text-gray-400 hover:text-discord-accent opacity-0 group-hover:opacity-100 transition-opacity" title="<?php _e('浏览 %s', $attachments->title); ?>"><i class="fas fa-external-link-alt text-xs"></i></a>
                                            </div>
                                        </td>
                                        <td class="py-3 hidden md:table-cell text-sm text-gray-600">
                                            <?php $attachments->author(); ?>
                                        </td>
                                        <td class="py-3 hidden md:table-cell text-sm text-gray-600">
                                            <?php if ($attachments->parentPost->cid): ?>
                                                <a href="<?php $options->adminUrl('write-' . (0 === strpos($attachments->parentPost->type, 'post') ? 'post' : 'page') . '.php?cid=' . $attachments->parentPost->cid); ?>" class="text-discord-accent hover:underline"><?php $attachments->parentPost->title(); ?></a>
                                            <?php else: ?>
                                                <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-500"><?php _e('未归档'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 pr-4 text-right text-sm text-gray-500">
                                            <?php $attachments->dateWord(); ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        <div class="mb-2 text-4xl text-gray-300"><i class="far fa-images"></i></div>
                                        <?php _e('没有找到任何文件'); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    </div>

                    <!-- Card View Container -->
                    <div class="card-view-container">
                        <?php if (!empty($attachmentsData)): ?>
                            <?php foreach ($attachmentsData as $attachment): ?>
                                <div class="content-card media-card" id="card-<?php echo $attachment['cid']; ?>">
                                    <input type="checkbox" value="<?php echo $attachment['cid']; ?>" name="cid[]" class="card-checkbox text-discord-accent focus:ring-discord-accent border-gray-300">
                                    
                                    <!-- Media Preview -->
                                    <div class="card-media-preview">
                                        <?php if ($attachment['isImage']): ?>
                                            <a href="<?php $options->adminUrl('media.php?cid=' . $attachment['cid']); ?>" class="media-preview-link">
                                                <img src="<?php echo $attachment['url']; ?>" alt="<?php echo htmlspecialchars($attachment['title']); ?>" class="media-preview-image">
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php $options->adminUrl('media.php?cid=' . $attachment['cid']); ?>" class="media-preview-link media-preview-icon">
                                                <i class="fas fa-file text-6xl text-gray-300"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?php $options->adminUrl('manage-comments.php?cid=' . $attachment['cid']); ?>" 
                                           class="card-comment-badge flex-shrink-0 <?php echo $attachment['commentsNum'] > 0 ? 'bg-discord-accent text-white' : 'bg-gray-100 text-gray-500'; ?>">
                                            <?php echo $attachment['commentsNum']; ?>
                                        </a>
                                    </div>
                                    
                                    <div class="card-header">
                                        <div class="flex-1">
                                            <a href="<?php $options->adminUrl('media.php?cid=' . $attachment['cid']); ?>" class="card-title">
                                                <i class="fas fa-<?php echo $attachment['isImage'] ? 'image' : 'file'; ?> mr-2 text-gray-400"></i>
                                                <?php echo htmlspecialchars($attachment['title']); ?>
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <div class="card-meta">
                                        <div class="card-meta-item">
                                            <i class="fas fa-user text-gray-400"></i>
                                            <span><?php echo htmlspecialchars($attachment['author']['screenName']); ?></span>
                                        </div>
                                        <div class="card-meta-item">
                                            <i class="fas fa-folder text-gray-400"></i>
                                            <span>
                                                <?php if ($attachment['parentPost']['cid']): ?>
                                                    <a href="<?php $options->adminUrl('write-' . (0 === strpos($attachment['parentPost']['type'], 'post') ? 'post' : 'page') . '.php?cid=' . $attachment['parentPost']['cid']); ?>" class="hover:text-discord-accent"><?php echo htmlspecialchars($attachment['parentPost']['title']); ?></a>
                                                <?php else: ?>
                                                    <span class="text-gray-400"><?php _e('未归档'); ?></span>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="card-meta-item">
                                            <i class="fas fa-clock text-gray-400"></i>
                                            <span><?php echo (new \Typecho\Date($attachment['created']))->word(); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="card-actions">
                                        <a href="<?php $options->adminUrl('media.php?cid=' . $attachment['cid']); ?>">
                                            <i class="fas fa-edit"></i> <?php _e('编辑'); ?>
                                        </a>
                                        <a href="<?php echo $attachment['url']; ?>" target="_blank">
                                            <i class="fas fa-external-link-alt"></i> <?php _e('查看'); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Empty state -->
                            <div class="col-span-full px-4 py-8 text-center text-gray-500">
                                <div class="mb-2 text-4xl text-gray-300"><i class="far fa-images"></i></div>
                                <?php _e('没有找到任何文件'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            
            <?php if ($attachments->have()): ?>
                <div class="mt-4 flex justify-end">
                     <?php $attachments->pageNav('&laquo;', '&raquo;', 1, '...', array(
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
    background-color: var(--booadmin-surface);
    color: var(--booadmin-muted);
    font-size: 0.875rem; /* text-sm */
    border: 1px solid var(--booadmin-border);
    transition: all 0.2s;
    text-decoration: none;
}

.typecho-pager li a:hover {
    background-color: var(--booadmin-surface-2);
    color: var(--booadmin-accent);
    border-color: var(--booadmin-border-strong);
}

.typecho-pager li.current span {
    background-color: var(--booadmin-accent);
    color: white;
    border-color: var(--booadmin-accent);
    font-weight: 600;
}

/* Media Card Styles */
.media-card {
    display: flex;
    flex-direction: column;
    min-height: 420px;
    padding: 0 !important;
    overflow: hidden;
}

.media-card .card-checkbox {
    z-index: 10;
}

.card-media-preview {
    position: relative;
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, var(--booadmin-surface-2) 0%, var(--booadmin-border) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-bottom: 1px solid var(--booadmin-border);
}

.media-preview-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    text-decoration: none;
}

.media-preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.media-card:hover .media-preview-image {
    transform: scale(1.05);
}

.media-preview-icon {
    background: linear-gradient(135deg, var(--booadmin-surface-2) 0%, var(--booadmin-highlight-soft) 100%);
}

.media-card .card-comment-badge {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    z-index: 5;
}

.media-card .card-header {
    flex: 1;
    padding: 1rem 1.25rem 0.5rem;
}

.media-card .card-title {
    font-size: 0.9375rem;
    line-height: 1.5;
    display: flex;
    align-items: start;
}

.media-card .card-meta {
    padding: 0.5rem 1.25rem 0.75rem;
    border-top: none;
}

.media-card .card-actions {
    margin-top: auto;
    padding: 0.75rem 1.25rem 1rem;
    border-top: 1px solid var(--booadmin-border);
}
</style>

<?php
include 'common-js.php';
include 'table-js.php';
?>
<!-- Operate Confirm Modal -->
<div id="operate-confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white border border-gray-200 max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-discord-text mb-4"><?php _e('确认操作'); ?></h3>
        <p id="operate-confirm-message" class="text-discord-muted mb-6"></p>
        <div class="flex justify-end space-x-3">
            <button id="cancel-operate" class="px-4 py-2 bg-gray-200 text-discord-text font-medium hover:bg-gray-300 transition-colors text-sm">
                <?php _e('取消'); ?>
            </button>
            <button id="confirm-operate" class="px-4 py-2 bg-discord-accent text-white font-medium hover:bg-blue-600 transition-colors text-sm">
                <?php _e('确认'); ?>
            </button>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function () {
    $('.typecho-list-table').tableSelectable({
        checkEl     :   'input[type=checkbox]',
        rowEl       :   'tr',
        selectAllEl :   '.typecho-table-select-all',
        actionEl    :   '.dropdown-menu a'
    });

    $('.btn-dropdown-toggle').dropdownMenu({
        btnEl       :   '.btn-dropdown-toggle',
        menuEl      :   '.dropdown-menu'
    });

    // Operate confirmation modal
    var operateHref = null;
    $('.btn-operate').click(function () {
        var t = $(this);
        operateHref = t.attr('href');
        $('#operate-confirm-message').text(t.attr('lang'));
        $('#operate-confirm-modal').removeClass('hidden');
        return false;
    });

    $('#cancel-operate').click(function () {
        $('#operate-confirm-modal').addClass('hidden');
        operateHref = null;
    });

    $('#confirm-operate').click(function () {
        if (operateHref) {
            window.location.href = operateHref;
        }
        $('#operate-confirm-modal').addClass('hidden');
    });

    // Close modal when clicking outside
    $('#operate-confirm-modal').click(function (e) {
        if (e.target === this) {
            $('#operate-confirm-modal').addClass('hidden');
            operateHref = null;
        }
    });
});
</script>
<script>
// Mobile-aware view mode for manage-medias.php
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
    
    // 保存视图模式
    function saveViewMode(mode) {
        try {
            localStorage.setItem(VIEW_MODE_KEY, mode);
        } catch(e) {
            // Ignore localStorage errors
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
        
        applyViewMode(finalMode);
    }
    
    // 监听用户手动切换视图（覆盖 common-js.php 的通用处理）
    $('.view-toggle button').off('click').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $btn = $(this);
        var newMode = $btn.hasClass('btn-table-view') ? 'table' : 'card';
        
        // 保存到独立的 localStorage
        saveViewMode(newMode);
        
        // 标记用户已手动设置
        try {
            localStorage.setItem(USER_PREFERENCE_KEY, 'true');
        } catch(e) {
            // Ignore localStorage errors
        }
        
        // 应用视图模式
        applyViewMode(newMode);
        
        return false;
    });
    
    // 执行初始化
    if ($('.view-toggle').length > 0) {
        initializeViewMode();
    }
    
    // 监听窗口大小变化
    // 只在用户没有手动设置偏好时，根据屏幕大小自动调整
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (!hasUserPreference() && $('.view-toggle').length > 0) {
                var mobile = isMobile();
                var currentMode = mobile ? 'card' : 'table';
                
                // 自动切换时也保存到 localStorage
                var savedMode = getSavedViewMode();
                if (savedMode !== currentMode) {
                    saveViewMode(currentMode);
                    applyViewMode(currentMode);
                }
            }
        }, 250); // 防抖，250ms 后才执行
    });
});
</script>
