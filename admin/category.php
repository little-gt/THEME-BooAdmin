<?php
// 引入通用配置、头部和菜单文件
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<div class="container-fluid">

    <!-- 顶部标题栏 - 显示“编辑分类”或“新增分类”及返回列表按钮 -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <p class="text-muted mb-0">
                        <?php // 根据请求中是否存在 mid 参数判断是编辑还是新增操作，并显示相应图标
                            $iconClass = isset($request->mid) ? 'fa-solid fa-pen-to-square' : 'fa-solid fa-folder-plus';
                        ?>
                        <i class="fa-regular <?php echo $iconClass; ?> me-1"></i>
                        <?php echo isset($request->mid) ? _t('编辑分类') : _t('新增分类'); ?>
                    </p>
                    <a href="<?php $options->adminUrl('manage-categories.php'); ?>" class="btn btn-light shadow-sm fw-bold small">
                        <i class="fa-solid fa-arrow-left me-1"></i> <?php _e('返回列表'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 表单区域 -->
    <div class="row">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body p-4">
                    <div class="typecho-form-modern">
                        <?php // 渲染分类编辑或新增表单
                        \Widget\Metas\Category\Edit::alloc()->form()->render(); ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<?php
// 引入版权信息、通用JS和表单JS
include 'copyright.php';
include 'common-js.php';
include 'form-js.php';
?>

<script>
$(document).ready(function() {
    // --- 表单美化 Polyfill ---
    
    // 1. 输入框和文本域统一添加 Bootstrap 的 form-control 类
    $('.typecho-option input[type=text], .typecho-option textarea').addClass('form-control');

    // 2. 选择框统一添加 Bootstrap 的 form-select 类
    $('.typecho-option select').addClass('form-select');
    
    // 3. 列表布局优化：移除默认列表样式并添加间距
    $('.typecho-option').addClass('list-unstyled mb-0');
    $('.typecho-option li').addClass('mb-3');
    
    // 4. 标签和描述文本样式化
    $('.typecho-option label.typecho-label').addClass('form-label fw-bold text-dark small text-uppercase mb-1 d-block');
    $('.typecho-option p.description').addClass('form-text text-muted small mt-1');
    
    // 5. 必填项添加红色星号标记
    $('.typecho-option label.required').each(function() {
        $(this).html($(this).html() + ' <span class="text-danger">*</span>');
    });

    // 6. 提交按钮容器布局和按钮样式
    $('.typecho-option-submit').addClass('mt-4 pt-3 border-top d-flex justify-content-end');
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 fw-bold shadow-sm');
    
    // 7. 特殊处理：隐藏包含单个 hidden input 的列表项 (Typecho 有时会输出空的 li)
    $('.typecho-option li').each(function() {
        if ($(this).find('input[type=hidden]').length > 0 && $(this).children().length === 1) {
            $(this).hide();
        }
    });

    // 8. 自动聚焦到第一个文本输入框
    $('.typecho-option input[type=text]:first').focus();
});
</script>

<?php include 'footer.php'; ?>