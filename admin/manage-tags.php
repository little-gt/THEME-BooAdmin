<?php
// 引入通用配置、头部和菜单文件
include 'common.php';
include 'header.php';
include 'menu.php';

// 初始化标签管理组件，用于获取和操作标签数据
\Widget\Metas\Tag\Admin::alloc()->to($tags);
?>

<div class="container-fluid">
    
    <!-- 顶部操作栏 - 页面标题、操作提示与新增标签按钮 -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0 small">
                            <i class="fa-regular fa-hand-pointer me-1"></i><?php _e('您可以点击标签进行多选，或拖拽标签排序。'); ?>
                        </p>
                    </div>
                    <div>
                        <a href="<?php $options->adminUrl('manage-tags.php?add'); ?>" class="btn btn-primary px-4 shadow-sm fw-bold">
                            <i class="fa-solid fa-plus me-2"></i><?php _e('新增标签'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4" style="animation-delay: 0.1s;">
        
        <!-- 左侧：标签列表管理区 -->
        <div class="col-lg-8">
            <div class="card-modern h-100">
                <div class="card-body">
                    
                    <form method="post" name="manage_tags" class="operate-form">
                        <!-- 标签列表工具栏 - 批量操作 -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
                            <div class="operate d-flex align-items-center gap-2 flex-wrap">
                                <!-- 全选复选框 -->
                                <div class="form-check me-2 user-select-none">
                                    <input class="form-check-input typecho-table-select-all" type="checkbox" id="selectAll">
                                    <label class="form-check-label fw-bold" for="selectAll"><?php _e('全选'); ?></label>
                                </div>

                                <!-- 批量操作下拉菜单 (删除、刷新、合并) -->
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
                                        
                                        <!-- 合并标签操作 -->
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
                        </div>

                        <!-- 标签云列表 - 显示所有标签 -->
                        <div class="tag-container">
                            <?php if($tags->have()): // 遍历标签 ?>
                                <div class="row g-2">
                                <?php while($tags->next()): ?>
                                    <div class="col-auto">
                                        <div class="tag-chip position-relative user-select-none" id="mid-<?php $tags->theId(); // 使用 mid-ID 方便JS高亮和定位 ?>">
                                            
                                            <!-- 隐藏的 Checkbox，用于实际的选中状态管理 -->
                                            <input type="checkbox" value="<?php $tags->mid(); ?>" name="mid[]" class="tag-checkbox d-none" />
                                            
                                            <div class="d-flex align-items-center px-3 py-2 border bg-white shadow-sm transition-all tag-inner">
                                                <span class="tag-name fw-bold text-dark me-2"><?php $tags->name(); ?></span>
                                                
                                                <!-- 标签关联的文章数 -->
                                                <span class="badge bg-light text-secondary border small" title="<?php _e('%d 篇文章', $tags->count); ?>">
                                                    <?php $tags->count(); ?>
                                                </span>

                                                <!-- 编辑链接 (悬停时显示) -->
                                                <a href="<?php echo $request->makeUriByRequest('mid=' . $tags->mid); ?>" class="tag-edit-btn ms-2 text-primary opacity-0" title="<?php _e('编辑'); ?>">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                                </div>
                            <?php else: // 没有标签时显示提示信息 ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-tags fa-3x mb-3 opacity-25"></i>
                                    <p><?php _e('没有任何标签'); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <input type="hidden" name="do" value="delete" /> <!-- 默认操作为删除，可被其他按钮覆盖 -->
                    </form>
                </div>
            </div>
        </div>

        <!-- 右侧：编辑/新增标签表单区 -->
        <div class="col-lg-4">
            <div class="card-modern sticky-top" style="top: 20px; z-index: 100;">
                <div class="card-header bg-transparent border-bottom px-4 py-3">
                    <h5 class="fw-bold mb-0 text-dark">
                        <?php if (isset($request->mid)): // 根据 mid 参数判断是编辑还是新增模式 ?>
                            <i class="fa-solid fa-pen-to-square me-2 text-primary"></i><?php _e('编辑标签'); ?>
                        <?php else: ?>
                            <i class="fa-solid fa-plus-circle me-2 text-success"></i><?php _e('新增标签'); ?>
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="typecho-form-modern">
                        <?php // 渲染标签编辑或新增表单
                        \Widget\Metas\Tag\Edit::alloc()->form()->render(); ?>
                    </div>
                    
                    <?php if (isset($request->mid)): // 编辑模式下显示取消编辑按钮 ?>
                        <div class="mt-3" style="text-align: right;">
                            <a href="<?php $options->adminUrl('manage-tags.php'); ?>" class="btn btn-outline-secondary btn-sm">
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

/* 标签选中状态 */
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

/* 选中项高亮（兼容 Typecho 旧样式） */
.typecho-mini-panel { 
    /* 覆盖可能的旧样式 */
}
</style>

<?php
// 引入版权信息、通用JS和表单JS
include 'copyright.php';
include 'common-js.php';
include 'form-js.php';
?>

<script type="text/javascript">
(function () {
    $(document).ready(function () {
        // --- 标签列表交互逻辑 ---
        // 1. 标签选择逻辑: 点击标签 Chip 切换对应 Checkbox 的选中状态
        $('.tag-inner').click(function(e) {
            // 如果点击的是编辑按钮，不触发选中逻辑
            if ($(e.target).closest('.tag-edit-btn').length > 0) return;

            var parent = $(this).parent('.tag-chip');
            var checkbox = parent.find('input[type="checkbox"]');
            var checked = !checkbox.prop('checked');
            
            checkbox.prop('checked', checked).trigger('change'); // 触发 change 事件更新视觉状态
        });

        // 2. 选中视觉反馈: Checkbox 状态变化时，更新标签 Chip 的样式
        $('input[name="mid[]"]').change(function() {
            var chip = $(this).closest('.tag-chip');
            if ($(this).prop('checked')) {
                chip.addClass('selected');
            } else {
                chip.removeClass('selected');
            }
        });

        // 3. 全选逻辑: 全选复选框控制所有标签 Checkbox 状态
        $('.typecho-table-select-all').click(function() {
            var checked = $(this).prop('checked');
            $('input[name="mid[]"]').prop('checked', checked).trigger('change');
        });

        // 4. 批量操作按钮逻辑 (删除、刷新、合并)
        $('.dropdown-menu .btn-operate').click(function (e) {
            e.preventDefault(); // 阻止默认的链接跳转
            var btn = $(this);
            var msg = btn.attr('lang'); // 获取确认信息
            var href = btn.attr('rel'); // 获取实际的操作URL

            if (confirm(msg)) {
                var form = btn.parents('form');
                form.attr('action', href).submit(); // 提交表单执行操作
            }
        });

        // 5. 合并按钮逻辑
        $('.merge-btn').click(function(e) {
            e.stopPropagation(); // 阻止下拉菜单关闭
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

        // 防止点击合并输入框或其标签导致下拉菜单关闭
        $('.dropdown-menu input, .dropdown-menu label').click(function(e) {
            e.stopPropagation();
        });

        // 6. 表单美化 Polyfill (复用 options 页面的通用逻辑)
        $('.typecho-option input[type=text], .typecho-option textarea').addClass('form-control');
        $('.typecho-option select').addClass('form-select');
        $('.typecho-option').addClass('list-unstyled mb-0');
        $('.typecho-option li').addClass('mb-3');
        $('.typecho-option label.typecho-label').addClass('form-label fw-bold text-dark small text-uppercase mb-1 d-block');
        $('.typecho-option p.description').addClass('form-text text-muted small mt-1');
        $('.typecho-option-submit').addClass('mt-4 pt-3 border-top d-flex justify-content-end');
        $('.typecho-option-submit button').addClass('btn btn-primary px-4 fw-bold shadow-sm');
        
        // 自动聚焦到第一个输入框
        $('.typecho-option input[type=text]:first').focus();

        <?php if (isset($request->mid)): // 如果是编辑模式，高亮当前编辑的标签 ?>
        $('#mid-<?php echo $request->mid; ?>').addClass('selected');
        <?php endif; ?>
    });
})();
</script>

<?php include 'footer.php'; ?>