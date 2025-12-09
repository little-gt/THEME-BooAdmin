<?php
include 'common.php';
include 'header.php';
include 'menu.php';

\Widget\Metas\Tag\Admin::alloc()->to($tags);
?>

<div class="container-fluid">
    
    <!-- 顶部标题 -->
    <div class="row mb-4 fade-in-up">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <p class="text-muted mb-0">
                        <i class="fa-regular fa-hand-pointer me-1"></i>
                        <?php _e('您可以点击标签来进行多选'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 fade-in-up" style="animation-delay: 0.1s;">
        
        <!-- 左侧：标签列表管理 -->
        <div class="col-lg-8">
            <div class="card-modern h-100">
                <div class="card-body">
                    
                    <form method="post" name="manage_tags" class="operate-form">
                        <!-- 工具栏 -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
                            <div class="operate d-flex align-items-center gap-2 flex-wrap">
                                <div class="form-check me-2 user-select-none">
                                    <input class="form-check-input typecho-table-select-all" type="checkbox" id="selectAll">
                                    <label class="form-check-label fw-bold" for="selectAll"><?php _e('全选'); ?></label>
                                </div>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-light border dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-check-double me-2 text-primary"></i><?php _e('选中项'); ?>
                                    </button>
                                    <ul class="dropdown-menu shadow border-0 p-2" style="border-radius: 12px; min-width: 260px;">
                                        <li>
                                            <button type="button" class="dropdown-item rounded-2 text-danger mb-1 btn-operate" lang="<?php _e('你确认要删除这些标签吗?'); ?>" rel="<?php $security->index('/action/metas-tag-edit?do=delete'); ?>">
                                                <i class="fa-solid fa-trash me-2"></i><?php _e('删除'); ?>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item rounded-2 mb-2 btn-operate" lang="<?php _e('刷新标签可能需要等待较长时间, 你确认要刷新这些标签吗?'); ?>" rel="<?php $security->index('/action/metas-tag-edit?do=refresh'); ?>">
                                                <i class="fa-solid fa-rotate me-2"></i><?php _e('刷新'); ?>
                                            </button>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        
                                        <!-- 合并操作 -->
                                        <li class="px-3 py-2 bg-light rounded-3">
                                            <label class="form-label small text-muted mb-1"><?php _e('合并到'); ?></label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="merge" class="form-control" placeholder="<?php _e('输入标签名'); ?>">
                                                <button type="button" class="btn btn-outline-primary merge-btn" rel="<?php $security->index('/action/metas-tag-edit?do=merge'); ?>">
                                                    <?php _e('合并'); ?>
                                                </button>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- 搜索框 (此处 Typecho 原生通常没有独立的标签搜索，但我们可以保留作为 UI 占位或未来扩展) -->
                            <!-- 若需要可以自行添加 JS 过滤当前列表 -->
                        </div>

                        <!-- 标签云列表 -->
                        <!-- 
                             typecho-list-notable: 原生类名，保留以防万一
                             tag-list: 原生类名
                        -->
                        <div class="tag-container">
                            <?php if($tags->have()): ?>
                                <div class="row g-2">
                                <?php while($tags->next()): ?>
                                    <div class="col-auto">
                                        <div class="tag-chip position-relative user-select-none" id="<?php $tags->theId(); ?>">
                                            
                                            <!-- 隐藏的 Checkbox -->
                                            <input type="checkbox" value="<?php $tags->mid(); ?>" name="mid[]" class="tag-checkbox d-none" />
                                            
                                            <div class="d-flex align-items-center px-3 py-2 border rounded-pill bg-white shadow-sm transition-all tag-inner">
                                                <span class="tag-name fw-bold text-dark me-2"><?php $tags->name(); ?></span>
                                                
                                                <!-- 文章数 -->
                                                <span class="badge bg-light text-secondary border rounded-pill small" title="<?php _e('%d 篇文章', $tags->count); ?>">
                                                    <?php $tags->count(); ?>
                                                </span>

                                                <!-- 编辑链接 (悬停显示) -->
                                                <a href="<?php echo $request->makeUriByRequest('mid=' . $tags->mid); ?>" class="tag-edit-btn ms-2 text-primary opacity-0" title="<?php _e('编辑'); ?>">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-tags fa-3x mb-3 opacity-25"></i>
                                    <p><?php _e('没有任何标签'); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <input type="hidden" name="do" value="delete" />
                    </form>
                </div>
            </div>
        </div>

        <!-- 右侧：编辑/新增表单 -->
        <div class="col-lg-4">
            <div class="card-modern sticky-top" style="top: 20px; z-index: 100;">
                <div class="card-header bg-transparent border-bottom px-4 py-3">
                    <h5 class="fw-bold mb-0 text-dark">
                        <?php if (isset($request->mid)): ?>
                            <i class="fa-solid fa-pen-to-square me-2 text-primary"></i><?php _e('编辑标签'); ?>
                        <?php else: ?>
                            <i class="fa-solid fa-plus-circle me-2 text-success"></i><?php _e('新增标签'); ?>
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="typecho-form-modern">
                        <?php \Widget\Metas\Tag\Edit::alloc()->form()->render(); ?>
                    </div>
                    
                    <?php if (isset($request->mid)): ?>
                        <div class="mt-3 text-center">
                            <a href="<?php $options->adminUrl('manage-tags.php'); ?>" class="btn btn-outline-secondary btn-sm rounded-pill">
                                <i class="fa-solid fa-xmark me-1"></i><?php _e('取消编辑'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
/* 标签 Chip 样式 */
.tag-inner {
    cursor: pointer;
    border: 1px solid #f1f2f6;
    transition: all 0.2s ease;
}

.tag-inner:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.05) !important;
}

/* 选中状态 */
.tag-chip.selected .tag-inner {
    background-color: var(--primary-soft) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-color);
}

.tag-chip.selected .tag-name {
    color: var(--primary-color) !important;
}

.tag-chip.selected .badge {
    background-color: var(--primary-color) !important;
    color: #fff !important;
    border-color: transparent !important;
}

/* 编辑按钮悬停显示 */
.tag-inner:hover .tag-edit-btn {
    opacity: 1 !important;
}

/* 选中项高亮 */
.typecho-mini-panel { 
    /* 覆盖可能的旧样式 */
}
</style>

<?php
include 'copyright.php';
include 'common-js.php';
include 'form-js.php';
?>

<script type="text/javascript">
(function () {
    $(document).ready(function () {
        
        // 1. 标签选择逻辑 (点击 Chip 选中 Checkbox)
        $('.tag-inner').click(function(e) {
            // 如果点击的是编辑按钮，不触发选中
            if ($(e.target).closest('.tag-edit-btn').length > 0) return;

            var parent = $(this).parent('.tag-chip');
            var checkbox = parent.find('input[type="checkbox"]');
            var checked = !checkbox.prop('checked');
            
            checkbox.prop('checked', checked).trigger('change');
        });

        // 2. 选中视觉反馈
        $('input[name="mid[]"]').change(function() {
            var chip = $(this).closest('.tag-chip');
            if ($(this).prop('checked')) {
                chip.addClass('selected');
            } else {
                chip.removeClass('selected');
            }
        });

        // 3. 全选逻辑
        $('.typecho-table-select-all').click(function() {
            var checked = $(this).prop('checked');
            $('input[name="mid[]"]').prop('checked', checked).trigger('change');
        });

        // 4. 下拉菜单操作 (删除、刷新、合并)
        $('.dropdown-menu .btn-operate').click(function (e) {
            e.preventDefault();
            var btn = $(this);
            var msg = btn.attr('lang');
            var href = btn.attr('rel');

            if (confirm(msg)) {
                var form = btn.parents('form');
                form.attr('action', href).submit();
            }
        });

        // 5. 合并按钮逻辑
        $('.merge-btn').click(function(e) {
            e.stopPropagation();
            var btn = $(this);
            var form = btn.parents('form');
            var mergeInput = btn.siblings('input[name="merge"]');
            
            if (mergeInput.val() === '') {
                alert('<?php _e("请输入要合并到的标签名"); ?>');
                mergeInput.focus();
                return;
            }

            if (confirm('<?php _e("确认要合并选中标签吗?"); ?>')) {
                form.attr('action', btn.attr('rel')).submit();
            }
        });

        // 防止合并输入框点击关闭下拉
        $('.dropdown-menu input, .dropdown-menu label').click(function(e) {
            e.stopPropagation();
        });

        // 6. 表单美化 Polyfill (复用之前的逻辑)
        $('.typecho-option input[type=text], .typecho-option textarea').addClass('form-control');
        $('.typecho-option select').addClass('form-select');
        $('.typecho-option').addClass('list-unstyled mb-0');
        $('.typecho-option li').addClass('mb-3');
        $('.typecho-option label.typecho-label').addClass('form-label fw-bold text-dark small text-uppercase mb-1 d-block');
        $('.typecho-option p.description').addClass('form-text text-muted small mt-1');
        $('.typecho-option-submit').addClass('mt-4 pt-3 border-top d-flex justify-content-end');
        $('.typecho-option-submit button').addClass('btn btn-primary px-4 rounded-pill fw-bold shadow-sm');
        
        // 自动聚焦第一个输入框
        $('.typecho-option input[type=text]:first').focus();

        <?php if (isset($request->mid)): ?>
        // 高亮当前编辑的标签
        $('#<?php echo $tags->theId(); ?>').addClass('selected');
        <?php endif; ?>
    });
})();
</script>

<?php include 'footer.php'; ?>