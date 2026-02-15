<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();
$posts = \Widget\Contents\Post\Admin::alloc();
$isAllPosts = ('on' == $request->get('__typecho_all_posts') || 'on' == \Typecho\Cookie::get('__typecho_all_posts'));
?>
<main class="flex-1 flex flex-col overflow-hidden bg-discord-light">
    <!-- Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-10">
        <div class="flex items-center text-discord-muted">
             <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-layer-group mr-2 hidden md:inline"></i>
            <span class="font-medium text-discord-text"><?php _e('管理文章'); ?></span>
        </div>
        
        <div class="flex items-center space-x-4">
             <a href="<?php $options->adminUrl('write-post.php'); ?>" class="px-3 py-1.5 bg-discord-accent text-white rounded text-sm font-medium hover:bg-blue-600 transition-colors shadow-sm">
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
            
            <!-- Tabs & Filters -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                <div class="flex items-center bg-gray-100 p-1 rounded-lg select-none self-start">
                     <a href="<?php $options->adminUrl('manage-posts.php' . (isset($request->uid) ? '?uid=' . $request->filter('encode')->uid : '')); ?>" 
                        class="px-4 py-1.5 text-sm font-medium rounded-md transition-all duration-200 <?php if (!isset($request->status) || 'all' == $request->get('status')): ?>bg-white text-discord-text shadow-sm<?php else: ?>text-gray-500 hover:text-discord-text<?php endif; ?>">
                        <?php _e('可用'); ?>
                     </a>
                     <a href="<?php $options->adminUrl('manage-posts.php?status=waiting' . (isset($request->uid) ? '&uid=' . $request->filter('encode')->uid : '')); ?>" 
                        class="px-4 py-1.5 text-sm font-medium rounded-md transition-all duration-200 <?php if ('waiting' == $request->get('status')): ?>bg-white text-discord-text shadow-sm<?php else: ?>text-gray-500 hover:text-discord-text<?php endif; ?>">
                        <?php _e('待审核'); ?>
                        <?php if (!$isAllPosts && $stat->myWaitingPostsNum > 0 && !isset($request->uid)): ?>
                            <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 rounded-full text-xs"><?php $stat->myWaitingPostsNum(); ?></span>
                        <?php elseif ($isAllPosts && $stat->waitingPostsNum > 0 && !isset($request->uid)): ?>
                            <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 rounded-full text-xs"><?php $stat->waitingPostsNum(); ?></span>
                        <?php elseif (isset($request->uid) && $stat->currentWaitingPostsNum > 0): ?>
                            <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 rounded-full text-xs"><?php $stat->currentWaitingPostsNum(); ?></span>
                        <?php endif; ?>
                     </a>
                     <a href="<?php $options->adminUrl('manage-posts.php?status=draft' . (isset($request->uid) ? '&uid=' . $request->filter('encode')->uid : '')); ?>" 
                        class="px-4 py-1.5 text-sm font-medium rounded-md transition-all duration-200 <?php if ('draft' == $request->get('status')): ?>bg-white text-discord-text shadow-sm<?php else: ?>text-gray-500 hover:text-discord-text<?php endif; ?>">
                        <?php _e('草稿'); ?>
                        <?php if (!$isAllPosts && $stat->myDraftPostsNum > 0 && !isset($request->uid)): ?>
                            <span class="ml-1 px-1.5 py-0.5 bg-yellow-100 text-yellow-600 rounded-full text-xs"><?php $stat->myDraftPostsNum(); ?></span>
                        <?php elseif ($isAllPosts && $stat->draftPostsNum > 0 && !isset($request->uid)): ?>
                            <span class="ml-1 px-1.5 py-0.5 bg-yellow-100 text-yellow-600 rounded-full text-xs"><?php $stat->draftPostsNum(); ?></span>
                        <?php elseif (isset($request->uid) && $stat->currentDraftPostsNum > 0): ?>
                            <span class="ml-1 px-1.5 py-0.5 bg-yellow-100 text-yellow-600 rounded-full text-xs"><?php $stat->currentDraftPostsNum(); ?></span>
                        <?php endif; ?>
                     </a>
                </div>

                <form method="get" class="flex flex-wrap items-center gap-2">
                     <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="keywords" value="<?php echo htmlspecialchars($request->keywords ?? ''); ?>" placeholder="<?php _e('请输入关键字'); ?>" class="pl-9 pr-3 py-1.5 bg-white border border-gray-300 rounded text-sm focus:outline-none focus:border-discord-accent shadow-sm w-48 md:w-64">
                    </div>
                    <select name="category" class="pl-3 pr-8 py-1.5 bg-white border border-gray-300 rounded text-sm focus:outline-none focus:border-discord-accent shadow-sm">
                        <option value=""><?php _e('所有分类'); ?></option>
                        <?php \Widget\Metas\Category\Rows::alloc()->to($category); ?>
                        <?php while ($category->next()): ?>
                            <option value="<?php $category->mid(); ?>"<?php if ($request->get('category') == $category->mid): ?> selected="true"<?php endif; ?>><?php $category->name(); ?></option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 transition-colors text-sm font-medium"><?php _e('筛选'); ?></button>
                    
                    <?php if (isset($request->uid)): ?>
                        <input type="hidden" value="<?php echo htmlspecialchars($request->uid); ?>" name="uid"/>
                    <?php endif; ?>
                    <?php if (isset($request->status)): ?>
                        <input type="hidden" value="<?php echo htmlspecialchars($request->status); ?>" name="status"/>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Post List -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <form method="post" name="manage_posts" class="operate-form">
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
                                    <a href="<?php $security->index('/action/contents-post-edit?do=delete'); ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"><?php _e('删除'); ?></a>
                                    <?php if ($user->pass('editor', true)): ?>
                                        <div class="border-t border-gray-100 my-1"></div>
                                        <a href="<?php $security->index('/action/contents-post-edit?do=mark&status=publish'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><?php _e('标记为公开'); ?></a>
                                        <a href="<?php $security->index('/action/contents-post-edit?do=mark&status=waiting'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><?php _e('标记为待审核'); ?></a>
                                        <a href="<?php $security->index('/action/contents-post-edit?do=mark&status=hidden'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><?php _e('标记为隐藏'); ?></a>
                                    <?php endif; ?>
                                </div>
                             </div>
                         </div>
                         <?php if ($user->pass('editor', true) && !isset($request->uid)): ?>
                            <div class="flex items-center space-x-2 text-xs">
                                <a href="<?php echo $request->makeUriByRequest('__typecho_all_posts=on&page=1'); ?>" class="<?php if ($isAllPosts): ?>text-discord-accent font-bold<?php else: ?>text-gray-500 hover:text-discord-text<?php endif; ?>"><?php _e('所有'); ?></a>
                                <span class="text-gray-300">|</span>
                                <a href="<?php echo $request->makeUriByRequest('__typecho_all_posts=off&page=1'); ?>" class="<?php if (!$isAllPosts): ?>text-discord-accent font-bold<?php else: ?>text-gray-500 hover:text-discord-text<?php endif; ?>"><?php _e('我的'); ?></a>
                            </div>
                         <?php endif; ?>
                    </div>

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-bold text-gray-500 uppercase border-b border-gray-100 bg-gray-50/50">
                                <th class="w-10 pl-4 py-3"></th>
                                <th class="w-16 py-3 text-center"><i class="fas fa-comment-alt"></i></th>
                                <th class="py-3"><?php _e('标题'); ?></th>
                                <th class="py-3 hidden md:table-cell"><?php _e('作者'); ?></th>
                                <th class="py-3 hidden md:table-cell"><?php _e('分类'); ?></th>
                                <th class="py-3 pr-4 text-right"><?php _e('日期'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if ($posts->have()): ?>
                                <?php while ($posts->next()): ?>
                                    <tr id="<?php $posts->theId(); ?>" class="group hover:bg-gray-50 transition-colors">
                                        <td class="pl-4 py-3">
                                            <input type="checkbox" value="<?php $posts->cid(); ?>" name="cid[]" class="rounded text-discord-accent focus:ring-discord-accent border-gray-300">
                                        </td>
                                        <td class="py-3 text-center">
                                            <a href="<?php $options->adminUrl('manage-comments.php?cid=' . ($posts->parentId ? $posts->parentId : $posts->cid)); ?>" 
                                               class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium <?php echo $posts->commentsNum > 0 ? 'bg-discord-accent text-white' : 'bg-gray-100 text-gray-500'; ?>">
                                                <?php $posts->commentsNum(); ?>
                                            </a>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <a href="<?php $options->adminUrl('write-post.php?cid=' . $posts->cid); ?>" class="text-discord-text font-medium hover:text-discord-accent transition-colors">
                                                    <?php $posts->title(); ?>
                                                </a>
                                                <?php
                                                if ('post_draft' == $posts->type) echo '<span class="ml-2 px-1.5 py-0.5 rounded text-xs bg-yellow-100 text-yellow-700">' . _t('草稿') . '</span>';
                                                elseif ($posts->revision) echo '<span class="ml-2 px-1.5 py-0.5 rounded text-xs bg-green-100 text-green-700">' . _t('有修订') . '</span>';
                                                
                                                if ('hidden' == $posts->status) echo '<span class="ml-2 px-1.5 py-0.5 rounded text-xs bg-gray-200 text-gray-600">' . _t('隐藏') . '</span>';
                                                elseif ('waiting' == $posts->status) echo '<span class="ml-2 px-1.5 py-0.5 rounded text-xs bg-red-100 text-red-700">' . _t('待审') . '</span>';
                                                elseif ('private' == $posts->status) echo '<span class="ml-2 px-1.5 py-0.5 rounded text-xs bg-purple-100 text-purple-700">' . _t('私密') . '</span>';
                                                elseif ($posts->password) echo '<span class="ml-2 px-1.5 py-0.5 rounded text-xs bg-gray-600 text-white">' . _t('加密') . '</span>';
                                                ?>
                                            </div>
                                            <div class="mt-1 flex items-center space-x-3 text-xs text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="<?php $options->adminUrl('write-post.php?cid=' . $posts->cid); ?>" class="hover:text-discord-accent"><i class="fas fa-edit mr-1"></i><?php _e('编辑'); ?></a>
                                                <?php if ('post_draft' != $posts->type): ?>
                                                    <a href="<?php $posts->permalink(); ?>" target="_blank" class="hover:text-discord-accent"><i class="fas fa-external-link-alt mr-1"></i><?php _e('查看'); ?></a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="py-3 hidden md:table-cell text-sm text-gray-600">
                                            <a href="<?php $options->adminUrl('manage-posts.php?__typecho_all_posts=off&uid=' . $posts->author->uid); ?>" class="hover:underline"><?php $posts->author(); ?></a>
                                        </td>
                                        <td class="py-3 hidden md:table-cell text-sm text-gray-600">
                                            <?php foreach($posts->categories as $index => $category): ?>
                                                <?php echo ($index > 0 ? ', ' : '') . '<a href="';
                                                $options->adminUrl('manage-posts.php?category=' . $category['mid'] . (isset($request->uid) ? '&uid=' . $request->filter('encode')->uid : '') . (isset($request->status) ? '&status=' . $request->filter('encode')->status : ''));
                                                echo '" class="hover:underline hover:text-discord-accent">' . $category['name'] . '</a>'; ?>
                                            <?php endforeach; ?>
                                        </td>
                                        <td class="py-3 pr-4 text-right text-sm text-gray-500">
                                            <?php if ('post_draft' == $posts->type || $posts->revision): ?>
                                                <span class="block text-xs text-green-600"><?php $modifyDate = new \Typecho\Date($posts->revision ? $posts->revision['modified'] : $posts->modified); _e('保存于 %s', $modifyDate->word()); ?></span>
                                            <?php else: ?>
                                                <?php $posts->dateWord(); ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        <div class="mb-2 text-4xl text-gray-300"><i class="far fa-folder-open"></i></div>
                                        <?php _e('没有找到任何文章'); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </form>
            </div>
            
            <?php if ($posts->have()): ?>
                <div class="mt-4 flex justify-end">
                     <?php $posts->pageNav('&laquo;', '&raquo;', 1, '...', array(
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
include 'footer.php';
?>
