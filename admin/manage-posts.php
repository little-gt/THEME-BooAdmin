<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();
$posts = \Widget\Contents\Post\Admin::alloc();
$isAllPosts = ('on' == $request->get('__typecho_all_posts') || 'on' == \Typecho\Cookie::get('__typecho_all_posts'));
?>

<div class="container-fluid">

    <!-- 顶部状态栏 -->
    <div class="row mb-4 fade-in-up">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">

                    <!-- 左侧：状态筛选 Tabs -->
                    <ul class="nav nav-pills mb-3 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link <?php if (!isset($request->status) || 'all' == $request->get('status')): ?>active<?php endif; ?>"
                               href="<?php $options->adminUrl('manage-posts.php' . (isset($request->uid) ? '?uid=' . $request->filter('encode')->uid : '')); ?>">
                               <?php _e('可用'); ?>
                            </a>
                        </li>
                        <li class="nav-item position-relative">
                            <a class="nav-link <?php if ('waiting' == $request->get('status')): ?>active<?php endif; ?>"
                               href="<?php $options->adminUrl('manage-posts.php?status=waiting' . (isset($request->uid) ? '&uid=' . $request->filter('encode')->uid : '')); ?>">
                               <?php _e('待审核'); ?>
                               <?php if (!$isAllPosts && $stat->myWaitingPostsNum > 0 && !isset($request->uid)): ?>
                                   <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?php $stat->myWaitingPostsNum(); ?></span>
                               <?php elseif ($isAllPosts && $stat->waitingPostsNum > 0 && !isset($request->uid)): ?>
                                   <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?php $stat->waitingPostsNum(); ?></span>
                               <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item position-relative">
                            <a class="nav-link <?php if ('draft' == $request->get('status')): ?>active<?php endif; ?>"
                               href="<?php $options->adminUrl('manage-posts.php?status=draft' . (isset($request->uid) ? '&uid=' . $request->filter('encode')->uid : '')); ?>">
                               <?php _e('草稿'); ?>
                               <?php if (!$isAllPosts && $stat->myDraftPostsNum > 0 && !isset($request->uid)): ?>
                                   <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"><?php $stat->myDraftPostsNum(); ?></span>
                               <?php elseif ($isAllPosts && $stat->draftPostsNum > 0 && !isset($request->uid)): ?>
                                   <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"><?php $stat->draftPostsNum(); ?></span>
                               <?php endif; ?>
                            </a>
                        </li>
                    </ul>

                    <!-- 右侧：所有/我的 切换 -->
                    <?php if ($user->pass('editor', true) && !isset($request->uid)): ?>
                    <div class="btn-group shadow-sm" role="group">
                        <a href="<?php echo $request->makeUriByRequest('__typecho_all_posts=on&page=1'); ?>"
                           class="btn btn-sm <?php if ($isAllPosts): ?>btn-primary<?php else: ?>btn-outline-primary<?php endif; ?>">
                           <?php _e('所有'); ?>
                        </a>
                        <a href="<?php echo $request->makeUriByRequest('__typecho_all_posts=off&page=1'); ?>"
                           class="btn btn-sm <?php if (!$isAllPosts): ?>btn-primary<?php else: ?>btn-outline-primary<?php endif; ?>">
                           <?php _e('我的'); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 主要操作区与表格 -->
    <div class="row fade-in-up" style="animation-delay: 0.1s;">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body">

                    <!-- 工具栏：批量操作 & 搜索 -->
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4 gap-3">

                        <!-- 批量操作 -->
                        <div class="operate">
                            <div class="btn-group">
                                <button type="button" class="btn btn-light border dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-check-double me-2 text-primary"></i><?php _e('选中项'); ?>
                                </button>
                                <ul class="dropdown-menu shadow border-0" style="border-radius: 12px;">
                                    <li><a class="dropdown-item text-danger" lang="<?php _e('你确认要删除这些文章吗?'); ?>" href="<?php $security->index('/action/contents-post-edit?do=delete'); ?>"><i class="fa-solid fa-trash me-2"></i><?php _e('删除'); ?></a></li>
                                    <?php if ($user->pass('editor', true)): ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="<?php $security->index('/action/contents-post-edit?do=mark&status=publish'); ?>"><i class="fa-solid fa-eye me-2"></i><?php _e('标记为公开'); ?></a></li>
                                        <li><a class="dropdown-item" href="<?php $security->index('/action/contents-post-edit?do=mark&status=waiting'); ?>"><i class="fa-solid fa-clock me-2"></i><?php _e('标记为待审核'); ?></a></li>
                                        <li><a class="dropdown-item" href="<?php $security->index('/action/contents-post-edit?do=mark&status=hidden'); ?>"><i class="fa-solid fa-eye-slash me-2"></i><?php _e('标记为隐藏'); ?></a></li>
                                        <li><a class="dropdown-item" href="<?php $security->index('/action/contents-post-edit?do=mark&status=private'); ?>"><i class="fa-solid fa-lock me-2"></i><?php _e('标记为私密'); ?></a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- 搜索与筛选 -->
                        <form method="get" class="d-flex gap-2 flex-wrap">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" placeholder="<?php _e('请输入关键字'); ?>" value="<?php echo htmlspecialchars($request->keywords ?? ''); ?>" name="keywords">
                            </div>

                            <select name="category" class="form-select w-auto" style="min-width: 120px;">
                                <option value=""><?php _e('所有分类'); ?></option>
                                <?php \Widget\Metas\Category\Rows::alloc()->to($category); ?>
                                <?php while ($category->next()): ?>
                                    <option value="<?php $category->mid(); ?>"<?php if ($request->get('category') == $category->mid): ?> selected="true"<?php endif; ?>><?php $category->name(); ?></option>
                                <?php endwhile; ?>
                            </select>

                            <button type="submit" class="btn btn-primary fw-bold"><?php _e('筛选'); ?></button>

                            <?php if ('' != $request->keywords || '' != $request->category): ?>
                                <a href="<?php $options->adminUrl('manage-posts.php' . (isset($request->status) || isset($request->uid) ? '?' . (isset($request->status) ? 'status=' . $request->filter('encode')->status : '') . (isset($request->uid) ? (isset($request->status) ? '&' : '') . 'uid=' . $request->filter('encode')->uid : '') : '')); ?>"
                                   class="btn btn-outline-secondary" title="<?php _e('取消筛选'); ?>">
                                   <i class="fa-solid fa-xmark"></i>
                                </a>
                            <?php endif; ?>

                            <input type="hidden" value="<?php echo $request->filter('html')->uid; ?>" name="uid" />
                            <input type="hidden" value="<?php echo $request->filter('html')->status; ?>" name="status" />
                        </form>
                    </div>

                    <!-- 数据表格 -->
                    <form method="post" name="manage_posts" class="operate-form">
                        <div class="table-responsive">
                            <!-- 注意：保留 typecho-list-table 类名以兼容 table-js.php 的逻辑 -->
                            <table class="table modern-table table-hover typecho-list-table">
                                <thead>
                                    <tr>
                                        <th width="40" class="text-center">
                                            <input type="checkbox" class="form-check-input typecho-table-select-all" />
                                        </th>
                                        <th width="60" class="text-center"><i class="fa-solid fa-comments text-muted"></i></th>
                                        <th><?php _e('标题'); ?></th>
                                        <th><?php _e('作者'); ?></th>
                                        <th><?php _e('分类'); ?></th>
                                        <th><?php _e('日期'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if ($posts->have()): ?>
                                    <?php while ($posts->next()): ?>
                                    <tr id="<?php $posts->theId(); ?>" class="align-middle">
                                        <td class="text-center">
                                            <input type="checkbox" value="<?php $posts->cid(); ?>" name="cid[]" class="form-check-input" />
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php $options->adminUrl('manage-comments.php?cid=' . ($posts->parentId ? $posts->parentId : $posts->cid)); ?>"
                                               class="badge bg-light text-dark rounded-pill border position-relative text-decoration-none"
                                               title="<?php $posts->commentsNum(); ?> <?php _e('评论'); ?>">
                                                <?php $posts->commentsNum(); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?php $options->adminUrl('write-post.php?cid=' . $posts->cid); ?>" class="fw-bold text-dark text-decoration-none post-title-link">
                                                <?php $posts->title(); ?>
                                            </a>
                                            <!-- 状态徽章 -->
                                            <?php if ($posts->hasSaved || 'post_draft' == $posts->type): ?>
                                                <span class="badge bg-warning text-dark ms-1" style="font-size: 0.7rem;"><i class="fa-solid fa-file-pen me-1"></i><?php _e('草稿'); ?></span>
                                            <?php endif; ?>
                                            <?php if ('hidden' == $posts->status): ?>
                                                <span class="badge bg-secondary ms-1" style="font-size: 0.7rem;"><i class="fa-solid fa-eye-slash me-1"></i><?php _e('隐藏'); ?></span>
                                            <?php elseif ('waiting' == $posts->status): ?>
                                                <span class="badge bg-info text-dark ms-1" style="font-size: 0.7rem;"><i class="fa-solid fa-clock me-1"></i><?php _e('待审核'); ?></span>
                                            <?php elseif ('private' == $posts->status): ?>
                                                <span class="badge bg-dark ms-1" style="font-size: 0.7rem;"><i class="fa-solid fa-lock me-1"></i><?php _e('私密'); ?></span>
                                            <?php elseif ($posts->password): ?>
                                                <span class="badge bg-danger ms-1" style="font-size: 0.7rem;"><i class="fa-solid fa-key me-1"></i><?php _e('加密'); ?></span>
                                            <?php endif; ?>

                                            <!-- 快捷操作图标 -->
                                            <a href="<?php $options->adminUrl('write-post.php?cid=' . $posts->cid); ?>" title="<?php _e('编辑 %s', htmlspecialchars($posts->title)); ?>" class="text-muted ms-2 opacity-50 hover-opacity-100">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <?php if ('post_draft' != $posts->type): ?>
                                            <a href="<?php $posts->permalink(); ?>" title="<?php _e('浏览 %s', htmlspecialchars($posts->title)); ?>" target="_blank" class="text-muted ms-2 opacity-50 hover-opacity-100">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo \Typecho\Common::gravatarUrl($posts->author->mail, 24, 'X', 'mm', $request->isSecure()); ?>" class="rounded-circle me-2" width="24" height="24">
                                                <a href="<?php $options->adminUrl('manage-posts.php?__typecho_all_posts=off&uid=' . $posts->author->uid); ?>" class="text-secondary text-decoration-none small"><?php $posts->author(); ?></a>
                                            </div>
                                        </td>
                                        <td>
                                            <?php $categories = $posts->categories; $length = count($categories); ?>
                                            <?php foreach ($categories as $key => $val): ?>
                                                <a href="<?php $options->adminUrl('manage-posts.php?category=' . $val['mid'] . (isset($request->uid) ? '&uid=' . $request->filter('encode')->uid : '') . (isset($request->status) ? '&status=' . $request->filter('encode')->status : '')); ?>"
                                                   class="badge bg-light text-secondary border fw-normal text-decoration-none">
                                                   <?php echo $val['name']; ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </td>
                                        <td class="text-muted small">
                                            <?php if ($posts->hasSaved): ?>
                                                <span class="text-warning" data-bs-toggle="tooltip" title="<?php _e('上次保存时间'); ?>">
                                                    <i class="fa-solid fa-clock-rotate-left me-1"></i><?php $modifyDate = new \Typecho\Date($posts->modified); echo $modifyDate->word(); ?>
                                                </span>
                                            <?php else: ?>
                                                <?php $posts->dateWord(); ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fa-regular fa-folder-open fa-3x mb-3 opacity-50"></i>
                                                <p><?php _e('没有找到任何文章'); ?></p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <!-- 分页 -->
                    <?php if ($posts->have()): ?>
                    <div class="mt-4 d-flex justify-content-center">
                        <?php $posts->pageNav('&laquo;', '&raquo;', 3, '...', array('wrapTag' => 'ul', 'wrapClass' => 'pagination pagination-modern', 'itemTag' => 'li', 'textTag' => 'span', 'currentClass' => 'active', 'prevClass' => 'prev', 'nextClass' => 'next')); ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'copyright.php';
include 'common-js.php';
include 'table-js.php'; // 必须包含，用于处理全选等逻辑
include 'footer.php';
?>