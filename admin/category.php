<?php
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<div class="container-fluid">
    <div class="row fade-in-up">
        
        <!-- 使用居中布局，适合表单填写 -->
        <div class="col-12">
            
            <div class="card-modern">
                <div class="card-body p-4">
                    
                    <!-- 顶部标题栏 -->
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="bg-light-primary p-2 rounded-circle me-3 text-primary">
                                <i class="<?php echo isset($request->mid) ? 'fa-solid fa-pen-to-square' : 'fa-solid fa-folder-plus'; ?> fs-4"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-0">
                                <?php echo isset($request->mid) ? _t('编辑分类') : _t('新增分类'); ?>
                            </h4>
                        </div>
                        <a href="<?php $options->adminUrl('manage-categories.php'); ?>" class="btn btn-light rounded-pill shadow-sm fw-bold small">
                            <i class="fa-solid fa-arrow-left me-1"></i> <?php _e('返回列表'); ?>
                        </a>
                    </div>

                    <!-- 表单区域 -->
                    <!-- 
                        typecho-form-modern: 这是一个标记类，用于 JS 识别并进行美化 
                    -->
                    <div class="typecho-form-modern">
                        <?php \Widget\Metas\Category\Edit::alloc()->form()->render(); ?>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?php
include 'copyright.php';
include 'common-js.php';
include 'form-js.php';
?>

<script>
$(document).ready(function() {
    // =======================================================
    // 表单美化 Polyfill (与 options-*.php 保持一致)
    // =======================================================
    
    // 1. 输入框
    $('.typecho-option input[type=text], .typecho-option textarea').addClass('form-control');
    $('.typecho-option select').addClass('form-select');
    
    // 2. 列表布局
    $('.typecho-option').addClass('list-unstyled mb-0');
    $('.typecho-option li').addClass('mb-3');
    
    // 3. 标签与描述
    $('.typecho-option label.typecho-label').addClass('form-label fw-bold text-dark small text-uppercase mb-1 d-block');
    $('.typecho-option p.description').addClass('form-text text-muted small mt-1');
    
    // 4. 必填项星号
    $('.typecho-option label.required').each(function() {
        $(this).html($(this).html() + ' <span class="text-danger">*</span>');
    });

    // 5. 提交按钮容器
    $('.typecho-option-submit').addClass('mt-4 pt-3 border-top d-flex justify-content-end');
    
    // 6. 提交按钮本身
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 rounded-pill fw-bold shadow-sm');
    
    // 7. 特殊处理：如果有 input type=hidden 的行，隐藏它 (Typecho 有时会输出空 li)
    $('.typecho-option li').each(function() {
        if ($(this).find('input[type=hidden]').length > 0 && $(this).children().length === 1) {
            $(this).hide();
        }
    });

    // 8. 自动聚焦第一个输入框
    $('.typecho-option input[type=text]:first').focus();
});
</script>

<?php include 'footer.php'; ?>