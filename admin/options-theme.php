<?php
// 引入通用配置、头部和菜单文件
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<div class="container-fluid">
    
    <!-- 顶部操作栏 - 页面标题与获取更多主题按钮 -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">
                            <i class="fa-solid fa-paintbrush me-2 text-primary"></i><?php _e('配置外观: %s', $options->theme); ?>
                        </h4>
                        <p class="text-muted mb-0 small">设置系统的主题配置</p>
                    </div>
                    <div>
                        <a href="https://forum.typecho.org/" target="_blank" class="btn btn-outline-primary px-4 fw-bold">
                            <i class="fa-solid fa-cart-plus me-2"></i><?php _e('获取更多主题'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">

            <!-- 顶部导航 Tabs - 切换主题管理、主题编辑和主题设置 -->
            <div class="card-modern mb-4">
                <div class="card-body">
                    <ul class="nav nav-pills bg-light p-2 rounded-3 d-inline-flex">
                        <li class="nav-item">
                            <a class="nav-link text-muted" href="<?php $options->adminUrl('themes.php'); ?>"><?php _e('可使用的外观'); ?></a>
                        </li>
                        <?php if (\Widget\Themes\Files::isWriteable()): // 仅当主题文件可写入时显示编辑入口 ?>
                        <li class="nav-item">
                            <a class="nav-link text-muted" href="<?php $options->adminUrl('theme-editor.php'); ?>"><?php _e('编辑当前外观'); ?></a>
                        </li>
                        <?php endif; ?>
                        <?php if (\Widget\Themes\Config::isExists()): // 仅当主题有设置项时显示设置入口 ?>
                        <li class="nav-item">
                            <a class="nav-link active fw-bold shadow-sm" href="<?php $options->adminUrl('options-theme.php'); ?>"><?php _e('设置外观'); ?></a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- 主题配置表单容器 -->
            <div class="row">
                <div class="col-12">
                    <div class="card-modern">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="typecho-form-modern">
                                        <?php // 渲染当前主题的配置表单
                                        \Widget\Themes\Config::alloc()->config()->render(); ?>
                                    </div>
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
    // 主题设置页面的表单美化 Polyfill
    // Typecho 主题设置可能包含各种复杂的表单元素，此处进行通用化 Bootstrap 样式适配。

    // 1. 输入框和文本域
    $('.typecho-option input[type=text], .typecho-option textarea').addClass('form-control');

    // 2. 选择框
    $('.typecho-option select').addClass('form-select');

    // 3. 列表和列表项布局
    $('.typecho-option').addClass('list-unstyled mb-0');
    $('.typecho-option li').addClass('mb-4');

    // 4. 标签和描述
    $('.typecho-option label.typecho-label').addClass('form-label fw-bold small text-muted text-uppercase mb-1 d-block');
    $('.typecho-option p.description').addClass('form-text text-muted small mt-1');

    // 5. 提交按钮
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 shadow-sm fw-bold');

    // 6. 单选框和复选框
    $('.typecho-option input[type=radio], .typecho-option input[type=checkbox]').addClass('form-check-input me-1');
});
</script>

<?php include 'footer.php'; ?>