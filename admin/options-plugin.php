<?php
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<div class="container-fluid">
    
    <!-- 顶部操作栏 -->
    <div class="row mb-4 fade-in-up">
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
    
    <div class="row fade-in-up">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="typecho-form-modern p-4 border">
                                <?php \Widget\Plugins\Config::alloc()->config()->render(); ?>
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
    // 插件设置页面的表单美化
    // 插件可能生成各种奇怪的表单结构，我们尽可能覆盖
    $('.typecho-option input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=hidden])').addClass('form-control');
    $('.typecho-option textarea').addClass('form-control');
    $('.typecho-option select').addClass('form-select');

    $('.typecho-option').addClass('list-unstyled mb-0');
    $('.typecho-option li').addClass('mb-4');
    $('.typecho-option label.typecho-label').addClass('form-label fw-bold text-dark mb-1 d-block');
    $('.typecho-option p.description').addClass('form-text text-muted small mt-1');
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 rounded-pill shadow-sm fw-bold');

    $('.typecho-option input[type=radio], .typecho-option input[type=checkbox]').addClass('form-check-input me-1');

    // 针对多选框组的优化
    $('.typecho-option .multiline').addClass('d-block mb-1 form-check');
    $('.typecho-option .multiline label').addClass('form-check-label');
});
</script>

<?php include 'footer.php'; ?>