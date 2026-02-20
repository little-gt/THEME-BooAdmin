<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();
$attachments = \Widget\Contents\Attachment\Admin::alloc();
?>
<main class="flex-1 flex flex-col overflow-hidden bg-discord-light">
    <!-- Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-10">
        <div class="flex items-center text-discord-muted">
             <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-images mr-2 hidden md:inline"></i>
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
                        <a href="<?php $options->adminUrl('manage-medias.php'); ?>" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 transition-colors"><?php _e('&laquo; 取消筛选'); ?></a>
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

            <!-- Media List -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <form method="post" name="manage_medias" class="operate-form">
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
                                    <a lang="<?php _e('你确认要删除这些文件吗?'); ?>" href="<?php $security->index('/action/contents-attachment-edit?do=delete'); ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"><?php _e('删除'); ?></a>
                                </div>
                             </div>
                             <button lang="<?php _e('您确认要清理未归档的文件吗?'); ?>" class="px-3 py-1 text-xs font-medium bg-red-50 border border-red-200 rounded hover:bg-red-100 text-red-600 shadow-sm btn-operate" href="<?php $security->index('/action/contents-attachment-edit?do=clear'); ?>"><?php _e('清理未归档'); ?></button>
                         </div>
                    </div>

                    <div class="table-wrapper" data-table-scroll>
                    <table class="w-full text-left border-collapse typecho-list-table draggable">
                        <thead>
                            <tr class="text-xs font-bold text-gray-500 uppercase border-b border-gray-100 bg-gray-50/50 nodrag">
                                <th class="w-10 pl-4 py-3"></th>
                                <th class="w-16 py-3 text-center"><i class="fas fa-comment-alt"></i></th>
                                <th class="py-3"><?php _e('文件名'); ?></th>
                                <th class="py-3 hidden md:table-cell"><?php _e('上传者'); ?></th>
                                <th class="py-3 hidden md:table-cell"><?php _e('所属文章'); ?></th>
                                <th class="py-3 pr-4 text-right"><?php _e('发布日期'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if ($attachments->have()): ?>
                                <?php while ($attachments->next()): ?>
                                    <?php $mime = \Typecho\Common::mimeIconType($attachments->attachment->mime); ?>
                                    <tr id="<?php $attachments->theId(); ?>" class="group hover:bg-gray-50 transition-colors">
                                        <td class="pl-4 py-3">
                                            <input type="checkbox" value="<?php $attachments->cid(); ?>" name="cid[]" class="rounded text-discord-accent focus:ring-discord-accent border-gray-300">
                                        </td>
                                        <td class="py-3 text-center">
                                            <a href="<?php $options->adminUrl('manage-comments.php?cid=' . $attachments->cid); ?>" 
                                               class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium <?php echo $attachments->commentsNum > 0 ? 'bg-discord-accent text-white' : 'bg-gray-100 text-gray-500'; ?>">
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
                                                <span class="px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-500"><?php _e('未归档'); ?></span>
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
    border-radius: 6px;
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
<script type="text/javascript">
$(document).ready(function () {
    $('.typecho-list-table').tableSelectable({
        checkEl     :   'input[type=checkbox]',
        rowEl       :   'tr',
        selectAllEl :   '.typecho-table-select-all',
        actionEl    :   '.dropdown-menu a,button.btn-operate'
    });

    $('.btn-dropdown-toggle').dropdownMenu({
        btnEl       :   '.btn-dropdown-toggle',
        menuEl      :   '.dropdown-menu'
    });

    $('.btn-operate').click(function () {
        var t = $(this), href = t.attr('href');
        if (confirm(t.attr('lang'))) {
            window.location.href = href;
        }
        return false;
    });
});
</script>

