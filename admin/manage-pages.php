<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();
$pages = \Widget\Contents\Page\Admin::alloc();
?>
<main class="flex-1 flex flex-col overflow-hidden bg-discord-light">
    <!-- Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-10">
        <div class="flex items-center text-discord-muted">
             <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-file-alt mr-2 hidden md:inline"></i>
            <span class="font-medium text-discord-text"><?php _e('管理独立页面'); ?></span>
        </div>
        
        <div class="flex items-center space-x-4">
             <a href="<?php $options->adminUrl('write-page.php'); ?>" class="px-3 py-1.5 bg-discord-accent text-white rounded text-sm font-medium hover:bg-blue-600 transition-colors shadow-sm">
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
                        <a href="<?php $options->adminUrl('manage-pages.php'); ?>" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 transition-colors"><?php _e('&laquo; 取消筛选'); ?></a>
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

            <!-- Page List -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <form method="post" name="manage_pages" class="operate-form">
                    <div class="p-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
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
                                    <a href="<?php $security->index('/action/contents-page-edit?do=delete'); ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"><?php _e('删除'); ?></a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <a href="<?php $security->index('/action/contents-page-edit?do=mark&status=publish'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><?php _e('标记为公开'); ?></a>
                                    <a href="<?php $security->index('/action/contents-page-edit?do=mark&status=hidden'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><?php _e('标记为隐藏'); ?></a>
                                </div>
                             </div>
                         </div>
                    </div>

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
                                <?php while ($pages->next()): ?>
                                    <tr id="<?php $pages->theId(); ?>" class="group hover:bg-gray-50 transition-colors">
                                        <td class="pl-4 py-3">
                                            <input type="checkbox" value="<?php $pages->cid(); ?>" name="cid[]" class="rounded text-discord-accent focus:ring-discord-accent border-gray-300">
                                        </td>
                                        <td class="py-3 text-center">
                                            <a href="<?php $options->adminUrl('manage-comments.php?cid=' . $pages->cid); ?>" 
                                               class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium <?php echo $pages->commentsNum > 0 ? 'bg-discord-accent text-white' : 'bg-gray-100 text-gray-500'; ?>">
                                                <?php $pages->commentsNum(); ?>
                                            </a>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <a href="<?php $options->adminUrl('write-page.php?cid=' . $pages->cid); ?>" class="text-discord-text font-medium hover:text-discord-accent transition-colors">
                                                    <?php $pages->title(); ?>
                                                </a>
                                                <?php
                                                if ('page_draft' == $pages->type) echo '<span class="ml-2 px-1.5 py-0.5 rounded text-xs bg-yellow-100 text-yellow-700">' . _t('草稿') . '</span>';
                                                elseif ($pages->revision) echo '<span class="ml-2 px-1.5 py-0.5 rounded text-xs bg-green-100 text-green-700">' . _t('有修订') . '</span>';
                                                
                                                if ('hidden' == $pages->status) echo '<span class="ml-2 px-1.5 py-0.5 rounded text-xs bg-gray-200 text-gray-600">' . _t('隐藏') . '</span>';
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
                                                <a href="<?php $options->adminUrl('manage-pages.php?parent=' . $pages->cid); ?>" class="text-discord-accent hover:underline bg-discord-light px-2 py-0.5 rounded-full text-xs font-medium"><?php echo _n('1', '%d', count($pages->children)); ?></a>
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


