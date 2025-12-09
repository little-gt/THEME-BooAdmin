<?php
include 'common.php';
include 'header.php';
include 'menu.php';

// 初始化用户编辑组件，如果 URL 中有 uid，则会加载该用户数据
\Widget\Users\Edit::alloc()->to($userEdit);
?>

<div class="container-fluid">
    
    <!-- 顶部说明与操作 -->
    <div class="row mb-4 fade-in-up">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <p class="text-muted mb-0">
                        <i class="fa-solid fa-shield-halved me-1"></i>
                        <?php if ($userEdit->have()): ?>
                            <?php _e('编辑用户: %s', $userEdit->name); ?>
                        <?php else: ?>
                            <?php _e('新增用户'); ?>
                        <?php endif; ?>
                        <?php if ($userEdit->have()): ?>
                            <small class="text-muted font-monospace">UID: <?php echo $userEdit->uid; ?></small>
                        <?php endif; ?>
                    </p>
                    <a href="<?php $options->adminUrl('manage-users.php'); ?>" class="btn btn-light shadow-sm fw-bold small">
                        <i class="fa-solid fa-arrow-left me-1"></i> <?php _e('返回列表'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row fade-in-up">
        
        <!-- 使用居中布局，适合表单填写 -->
        <div class="col-12">
            
            <div class="card-modern">
                <div class="card-body p-4 p-md-5">

                    <!-- 表单区域 -->
                    <div class="typecho-form-modern">
                        <?php $userEdit->form()->render(); ?>
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
    // 表单美化 Polyfill
    // =======================================================
    
    // 1. 输入框
    $('.typecho-option input[type=text], .typecho-option input[type=password], .typecho-option input[type=email], .typecho-option input[type=url]').addClass('form-control');
    $('.typecho-option select').addClass('form-select');
    
    // 2. 布局
    $('.typecho-option').addClass('list-unstyled mb-0');
    $('.typecho-option li').addClass('mb-3');
    
    // 3. 标签与描述
    $('.typecho-option label.typecho-label').addClass('form-label fw-bold small text-muted mb-1 d-block');
    $('.typecho-option p.description').addClass('form-text text-muted small mt-1');
    
    // 4. 必填项星号
    $('.typecho-option label.required').each(function() {
        $(this).html($(this).html().replace(' *', ' <span class="text-danger">*</span>'));
    });

    // 5. 提交按钮
    $('.typecho-option-submit').addClass('mt-4 pt-3 border-top d-flex justify-content-end');
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 rounded-pill fw-bold shadow-sm');
    
    // 6. 特殊布局：将密码和确认密码放在一行 (Bootstrap Grid)
    var passwordRow = $('input[name=password]').closest('li');
    var confirmRow = $('input[name=confirm]').closest('li');
    if (passwordRow.length && confirmRow.length) {
        var newRow = $('<div class="row g-3"></div>');
        var passCol = $('<div class="col-md-6"></div>').append(passwordRow.contents());
        var confirmCol = $('<div class="col-md-6"></div>').append(confirmRow.contents());
        newRow.append(passCol).append(confirmCol);
        passwordRow.empty().append(newRow);
        confirmRow.remove();
    }
    
    // 7. 隐藏 hidden input 的行
    $('.typecho-option li').each(function() {
        if ($(this).find('input[type=hidden]').length > 0 && $(this).children().length === 1) {
            $(this).hide();
        }
    });

    // 8. 自动聚焦
    $('.typecho-option input[type=text]:first').focus();
});
</script>

<?php include 'footer.php'; ?>