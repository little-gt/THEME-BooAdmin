<?php
include 'common.php';
include 'header.php';
include 'menu.php';

\Widget\Metas\Category\Admin::alloc()->to($categories);
?>

<div class="container-fluid">

    <!-- 顶部标题与操作区 -->
    <div class="row mb-4 fade-in-up">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <p class="text-muted mb-0">
                        <i class="fa-regular fa-hand-pointer me-1"></i>
                        <?php _e('通过拖拽表格行来调整分类排序'); ?>
                    </p>
                    <a href="<?php $options->adminUrl('category.php'); ?>" class="btn btn-primary px-4 shadow-sm fw-bold">
                        <i class="fa-solid fa-plus me-2"></i><?php _e('新增分类'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 主要内容区 -->
    <div class="row fade-in-up" style="animation-delay: 0.1s;">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body">

                    <form method="post" name="manage_categories" class="operate-form">

                        <!-- 批量操作工具栏 -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
                            <div class="operate d-flex align-items-center gap-2">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-light border dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-check-double me-2 text-primary"></i><?php _e('选中项'); ?>
                                    </button>
                                    <ul class="dropdown-menu shadow border-0 p-2" style="border-radius: 12px; min-width: 240px;">
                                        <li>
                                            <a class="dropdown-item rounded-2 text-danger mb-1" lang="<?php _e('此分类下的所有内容将被删除, 你确认要删除这些分类吗?'); ?>" href="<?php $security->index('/action/metas-category-edit?do=delete'); ?>">
                                                <i class="fa-solid fa-trash me-2"></i><?php _e('删除'); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item rounded-2 mb-2" lang="<?php _e('刷新分类可能需要等待较长时间, 你确认要刷新这些分类吗?'); ?>" href="<?php $security->index('/action/metas-category-edit?do=refresh'); ?>">
                                                <i class="fa-solid fa-rotate me-2"></i><?php _e('刷新'); ?>
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>

                                        <!-- 合并分类操作区 -->
                                        <li class="px-3 py-2 bg-light rounded-3">
                                            <label class="form-label small text-muted mb-1"><?php _e('合并到'); ?></label>
                                            <div class="input-group input-group-sm">
                                                <select name="merge" class="form-select border-end-0">
                                                    <?php $categories->parse('<option value="{mid}">{name}</option>'); ?>
                                                </select>
                                                <button type="button" class="btn btn-outline-primary merge" rel="<?php $security->index('/action/metas-category-edit?do=merge'); ?>">
                                                    <?php _e('执行'); ?>
                                                </button>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- 搜索框 (如果需要的话，Typecho原生这里是backLink，但上面已经处理了) -->
                        </div>

                        <!-- 分类列表表格 -->
                        <!--
                             注意：class "typecho-list-table" 必须保留，供 table-js.php 识别
                             class "draggable" 提示样式
                        -->
                        <div class="table-responsive">
                            <table class="table modern-table table-hover typecho-list-table draggable">
                                <thead>
                                    <tr>
                                        <th width="40" class="text-center">
                                            <input type="checkbox" class="form-check-input typecho-table-select-all" />
                                        </th>
                                        <th width="50"></th> <!-- 拖拽手柄列 -->
                                        <th><?php _e('名称'); ?></th>
                                        <th><?php _e('子分类'); ?></th>
                                        <th><?php _e('缩略名'); ?></th>
                                        <th width="100" class="text-center"><?php _e('文章数'); ?></th>
                                        <th width="100" class="text-end"><?php _e('默认'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if($categories->have()): ?>
                                    <?php while($categories->next()): ?>
                                    <tr id="mid-<?php $categories->theId(); ?>" class="align-middle">
                                        <td class="text-center">
                                            <input type="checkbox" value="<?php $categories->mid(); ?>" name="mid[]" class="form-check-input" />
                                        </td>
                                        <td class="text-center text-muted" style="cursor: move;">
                                            <i class="fa-solid fa-grip-vertical opacity-25"></i>
                                        </td>
                                        <td>
                                            <a href="<?php $options->adminUrl('category.php?mid=' . $categories->mid); ?>" class="fw-bold text-dark text-decoration-none">
                                                <?php $categories->name(); ?>
                                            </a>
                                            <a href="<?php $categories->permalink(); ?>" class="ms-2 text-muted opacity-50 hover-opacity-100" title="<?php _e('浏览 %s', $categories->name); ?>" target="_blank">
                                                <i class="fa-solid fa-arrow-up-right-from-square small"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <?php if (count($categories->children) > 0): ?>
                                                <a href="<?php $options->adminUrl('manage-categories.php?parent=' . $categories->mid); ?>" class="badge bg-light text-primary border text-decoration-none">
                                                    <i class="fa-regular fa-folder-open me-1"></i>
                                                    <?php echo _n('一个分类', '%d个分类', count($categories->children)); ?>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php $options->adminUrl('category.php?parent=' . $categories->mid); ?>" class="badge bg-light text-secondary border text-decoration-none opacity-50 hover-opacity-100">
                                                    <i class="fa-solid fa-plus me-1"></i><?php echo _e('新增'); ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td class="font-monospace small text-muted"><?php $categories->slug(); ?></td>
                                        <td class="text-center">
                                            <a href="<?php $options->adminUrl('manage-posts.php?category=' . $categories->mid); ?>"
                                               class="badge rounded-pill <?php echo $categories->count > 0 ? 'bg-primary' : 'bg-secondary opacity-25'; ?> text-decoration-none"
                                               style="min-width: 30px;">
                                                <?php $categories->count(); ?>
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            <?php if ($options->defaultCategory == $categories->mid): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                                    <i class="fa-solid fa-check me-1"></i><?php _e('默认'); ?>
                                                </span>
                                            <?php else: ?>
                                                <a href="<?php $security->index('/action/metas-category-edit?do=default&mid=' . $categories->mid); ?>"
                                                   class="btn btn-sm btn-light text-muted opacity-0 hover-opacity-100 hidden-by-mouse"
                                                   title="<?php _e('设为默认'); ?>">
                                                    <i class="fa-regular fa-star"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i>
                                                <p><?php _e('没有任何分类'); ?></p>
                                                <a href="<?php $options->adminUrl('category.php'); ?>" class="btn btn-sm btn-primary rounded-pill mt-2">
                                                    <?php _e('创建第一个分类'); ?>
                                                </a>
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
/* 悬停显示“设为默认”按钮 */
tr:hover .hidden-by-mouse {
    opacity: 1 !important;
}

/* 拖拽时的样式 (由 table-js.php 添加) */
.tDnD_whileDrag {
    background-color: var(--primary-soft) !important;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transform: scale(1.01);
}

/* 适配 Typecho 的返回链接样式 */
.typecho-list-operate a {
    text-decoration: none;
    font-weight: bold;
    color: var(--primary-color);
}
</style>

<?php
include 'copyright.php';
include 'common-js.php';
?>

<script type="text/javascript">
(function () {
    $(document).ready(function () {
        // 初始化表格拖拽排序 (保留 Typecho 核心交互)
        // 依赖 jquery-ui 或 jquery.tablednd
        var table = $('.typecho-list-table').tableDnD({
            onDrop: function () {
                var ids = [];
                $('input[type=checkbox]', table).each(function () {
                    ids.push($(this).val());
                });
                // 发送排序请求
                $.post('<?php $security->index('/action/metas-category-edit?do=sort'); ?>',
                    $.param({mid: ids}));

                // 重置行样式
                $('tr', table).each(function (i) {
                    if (i % 2) $(this).addClass('even');
                    else $(this).removeClass('even');
                });
            }
        });

        // 重新实现表格全选逻辑，适配新 UI
        // 注意：table-js.php 可能会有冲突，这里手动绑定一次更保险
        $('.typecho-table-select-all').click(function() {
            var checked = $(this).prop('checked');
            $('input[name="mid[]"]').prop('checked', checked).trigger('change');
        });

        // 合并分类按钮逻辑
        $('.dropdown-menu button.merge').click(function (e) {
            e.stopPropagation(); // 防止下拉菜单关闭
            var btn = $(this);
            var form = btn.parents('form');
            form.attr('action', btn.attr('rel')).submit();
        });

        // 防止点击 select 导致下拉菜单关闭
        $('.dropdown-menu select').click(function(e) {
            e.stopPropagation();
        });

        <?php if (isset($request->mid)): ?>
        // 高亮刚刚操作的行
        $('#mid-<?php echo $request->mid; ?>').addClass('table-active');
        <?php endif; ?>
    });
})();
</script>

<?php include 'footer.php'; ?>