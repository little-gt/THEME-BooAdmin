<?php
// 引入通用配置、头部和菜单文件
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();
$pages = \Widget\Contents\Page\Admin::alloc();
?>

<div class="container-fluid">
    
    <!-- 顶部说明与操作 -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <p class="text-muted mb-0">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        <?php _e('你可以通过设置页面排序来改变展示顺序'); ?>
                    </p>
                    <a href="<?php $options->adminUrl('write-page.php'); ?>" class="btn btn-primary px-4 shadow-sm fw-bold">
                        <i class="fa-solid fa-plus me-2"></i><?php _e('创建新的独立页面'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 主要内容区 -->
    <div class="row" style="animation-delay: 0.1s;">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body">
                    
                    <!-- 顶部工具栏 -->
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4 gap-3">
                        
                        <!-- 批量操作 (左侧) -->
                        <div class="operate">
                            <div class="btn-group">
                                <button type="button" class="btn btn-light border dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-check-double me-2 text-primary"></i><?php _e('选中项'); ?>
                                </button>
                                <ul class="dropdown-menu shadow border-0 p-2" style="border-radius: 12px;">
                                    <li>
                                        <a class="dropdown-item rounded-2 text-danger" lang="<?php _e('你确认要删除这些页面吗?'); ?>" href="<?php $security->index('/action/contents-page-edit?do=delete'); ?>">
                                            <i class="fa-solid fa-trash me-2"></i><?php _e('删除'); ?>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item rounded-2" href="<?php $security->index('/action/contents-page-edit?do=mark&status=publish'); ?>">
                                            <i class="fa-solid fa-eye me-2 text-success"></i><?php _e('标记为公开'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item rounded-2" href="<?php $security->index('/action/contents-page-edit?do=mark&status=hidden'); ?>">
                                            <i class="fa-solid fa-eye-slash me-2 text-secondary"></i><?php _e('标记为隐藏'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- 搜索与筛选 (右侧) -->
                        <form method="get" class="d-flex gap-2 w-100 w-lg-auto" style="max-width: 400px;">
                            <div class="input-group shadow-sm">
                                <!-- 取消筛选按钮 (仅在有搜索词时显示) -->
                                <?php if ('' != $request->keywords): ?>
                                    <a href="<?php $options->adminUrl('manage-pages.php'); ?>"
                                       class="btn btn-outline-secondary bg-white border-end-0 text-muted"
                                       title="<?php _e('取消筛选'); ?>">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                <?php endif; ?>

                                <!-- 搜索图标 -->
                                <span class="input-group-text bg-white border-end-0 text-muted <?php echo '' != $request->keywords ? 'border-start-0' : ''; ?>">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>

                                <!-- 输入框 -->
                                <input type="text" class="form-control border-start-0 ps-0"
                                       placeholder="<?php _e('请输入关键字'); ?>"
                                       value="<?php echo htmlspecialchars($request->keywords ?? ''); ?>"
                                       name="keywords">

                                <!-- 提交按钮 -->
                                <button type="submit" class="btn btn-primary fw-bold">
                                    <?php _e('筛选'); ?>
                                </button>
                            </div>
                        </form>

                    </div>

                    <!-- 数据表格 -->
                    <form method="post" name="manage_pages" class="operate-form">
                        <div class="table-responsive">
                            <!-- 
                                关键类名说明：
                                typecho-list-table: table-js.php 依赖，用于全选
                            -->
                            <table class="table modern-table table-hover typecho-list-table draggable">
                                <thead>
                                    <tr>
                                        <th width="40" class="text-center">
                                            <input type="checkbox" class="form-check-input typecho-table-select-all" />
                                        </th>
                                        <th width="60" class="text-center"><i class="fa-solid fa-comments text-muted"></i></th>
                                        <th><?php _e('标题'); ?></th>
                                        <th><?php _e('缩略名'); ?></th>
                                        <th><?php _e('日期'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if($pages->have()): ?>
                                    <?php while($pages->next()): ?>
                                    <tr id="<?php $pages->theId(); ?>" class="align-middle">
                                        <td class="text-center">
                                            <input type="checkbox" value="<?php $pages->cid(); ?>" name="cid[]" class="form-check-input" />
                                        </td>
                                        <!-- 评论数 -->
                                        <td class="text-center">
                                            <a href="<?php $options->adminUrl('manage-comments.php?cid=' . $pages->cid); ?>" 
                                               class="badge bg-light text-dark border position-relative text-decoration-none"
                                               title="<?php $pages->commentsNum(); ?> <?php _e('评论'); ?>">
                                                <?php $pages->commentsNum(); ?>
                                            </a>
                                        </td>
                                        <!-- 标题 -->
                                        <td>
                                            <a href="<?php $options->adminUrl('write-page.php?cid=' . $pages->cid); ?>" class="fw-bold text-dark text-decoration-none post-title-link">
                                                <?php $pages->title(); ?>
                                            </a>
                                            
                                            <!-- 状态徽章 -->
                                            <?php 
                                            if ($pages->hasSaved || 'page_draft' == $pages->type) {
                                                echo '<span class="badge bg-warning text-dark ms-1" style="font-size: 0.7rem;"><i class="fa-solid fa-file-pen me-1"></i>' . _t('草稿') . '</span>';
                                            }
                                            if ('hidden' == $pages->status) {
                                                echo '<span class="badge bg-secondary ms-1" style="font-size: 0.7rem;"><i class="fa-solid fa-eye-slash me-1"></i>' . _t('隐藏') . '</span>';
                                            }
                                            ?>

                                            <!-- 快捷操作图标 -->
                                            <a href="<?php $options->adminUrl('write-page.php?cid=' . $pages->cid); ?>" title="<?php _e('编辑 %s', htmlspecialchars($pages->title)); ?>" class="text-muted ms-2 opacity-50 hover-opacity-100">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <?php if ('page_draft' != $pages->type): ?>
                                                <a href="<?php $pages->permalink(); ?>" title="<?php _e('浏览 %s', htmlspecialchars($pages->title)); ?>" target="_blank" class="text-muted ms-2 opacity-50 hover-opacity-100">
                                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <!-- 缩略名 -->
                                        <td class="font-monospace text-muted small"><?php $pages->slug(); ?></td>
                                        <!-- 日期 -->
                                        <td class="text-muted small">
                                            <?php if ($pages->hasSaved): ?>
                                                <span class="text-warning" data-bs-toggle="tooltip" title="<?php _e('上次保存时间'); ?>">
                                                    <i class="fa-solid fa-clock-rotate-left me-1"></i><?php $modifyDate = new \Typecho\Date($pages->modified); _e('保存于 %s', $modifyDate->word()); ?>
                                                </span>
                                            <?php else: ?>
                                                <?php $pages->dateWord(); ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fa-regular fa-file fa-3x mb-3 opacity-25"></i>
                                                <p><?php _e('没有任何页面'); ?></p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* 标题链接悬停效果 */
.post-title-link:hover {
    color: var(--primary-color) !important;
}

/* 快捷图标透明度过渡 */
.hover-opacity-100 {
    transition: opacity 0.2s;
}
.hover-opacity-100:hover {
    opacity: 1 !important;
    color: var(--primary-color) !important;
}
</style>

<?php
include 'copyright.php';
include 'common-js.php';
include 'table-js.php';
?>

<?php include 'footer.php'; ?>