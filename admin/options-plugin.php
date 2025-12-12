<?php
// 引入通用配置、头部和菜单文件
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<div class="container-fluid">
    
    <!-- 顶部操作栏 - 页面标题及返回插件列表按钮 -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">
                            <i class="fa-solid fa-plug me-2 text-primary"></i><?php _e('插件管理'); ?>
                        </h4>
                        <p class="text-muted mb-0 small">管理系统的扩展功能</p>
                    </div>
                    <div>
                        <a href="<?php $options->adminUrl('plugins.php'); ?>" class="btn btn-outline-primary px-4 fw-bold">
                            <i class="fa-solid fa-arrow-left"></i> <?php _e('返回插件列表');?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 插件配置表单容器 -->
    <div class="row">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="typecho-form-modern">
                                <?php // 渲染当前插件的配置表单
                                \Widget\Plugins\Config::alloc()->config()->render(); ?>
                            </div>
                        </div>
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
    // 插件设置页面的表单美化 Polyfill
    // Typecho 插件生成的表单结构可能不统一，此处进行通用化 Bootstrap 样式适配。

    // 1. 输入框、文本域和选择框
    $('.typecho-option input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=hidden])').addClass('form-control');
    $('.typecho-option textarea').addClass('form-control');
    $('.typecho-option select').addClass('form-select');

    // 2. 列表和列表项布局
    $('.typecho-option').addClass('list-unstyled mb-0');
    $('.typecho-option li').addClass('mb-4');

    // 3. 标签和描述
    $('.typecho-option label.typecho-label').addClass('form-label fw-bold text-dark mb-1 d-block');
    $('.typecho-option p.description').addClass('form-text text-muted small mt-1');

    // 4. 提交按钮
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 shadow-sm fw-bold');

    // 5. 单选框和复选框
    $('.typecho-option input[type=radio], .typecho-option input[type=checkbox]').addClass('form-check-input me-1');

    // 6. 针对多选框组的优化，确保每个选项独占一行并应用 form-check 样式
    $('.typecho-option .multiline').addClass('d-block mb-1 form-check');
    $('.typecho-option .multiline label').addClass('form-check-label');
});
</script>

<?php include 'footer.php'; ?>