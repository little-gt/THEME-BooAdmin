<?php
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<div class="container-fluid">
    
    <div class="row mb-4 fade-in-up">
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
    
    <div class="row fade-in-up">
        <div class="col-12">

            <div class="card-modern">
                <div class="card-body">
                    <!-- 外观管理的顶部 Tabs -->
                    <ul class="nav nav-pills mb-4 bg-light p-2 rounded-3" style="width: fit-content;">
                        <li class="nav-item">
                            <a class="nav-link text-muted" href="<?php $options->adminUrl('themes.php'); ?>"><?php _e('可以使用的外观'); ?></a>
                        </li>
                        <?php if (!defined('__TYPECHO_THEME_WRITEABLE__') || __TYPECHO_THEME_WRITEABLE__): ?>
                        <li class="nav-item">
                            <a class="nav-link text-muted" href="<?php $options->adminUrl('theme-editor.php'); ?>"><?php _e('编辑当前外观'); ?></a>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link active fw-bold" href="<?php $options->adminUrl('options-theme.php'); ?>"><?php _e('设置外观'); ?></a>
                        </li>
                    </ul>

                </div>
            </div>

        </div>
    </div>
</div>

<br>

<div class="container-fluid">
    <div class="row fade-in-up">
        <div class="col-12">

            <div class="card-modern">
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="typecho-form-modern">
                                <?php \Widget\Themes\Config::alloc()->config()->render(); ?>
                            </div>
                        </div>
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
    // 通用美化
    $('.typecho-option input[type=text], .typecho-option textarea').addClass('form-control');
    $('.typecho-option select').addClass('form-select');
    $('.typecho-option').addClass('list-unstyled mb-0');
    $('.typecho-option li').addClass('mb-4');
    $('.typecho-option label.typecho-label').addClass('form-label fw-bold small text-muted text-uppercase mb-1 d-block');
    $('.typecho-option p.description').addClass('form-text text-muted small mt-1');
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 rounded-pill shadow-sm fw-bold');

    // 很多主题的设置页会有图片上传或复杂的 Radio 组，做通用处理
    $('.typecho-option input[type=radio], .typecho-option input[type=checkbox]').addClass('form-check-input me-1');
});
</script>

<?php include 'footer.php'; ?>