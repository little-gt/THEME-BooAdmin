<?php
// 引入通用配置、头部和菜单文件
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <div class="card-modern mb-4">
                <div class="card-body">
                    <ul class="nav nav-pills bg-light p-2 rounded-3 d-inline-flex">
                        <li class="nav-item">
                            <a class="nav-link text-muted" href="<?php $options->adminUrl('options-general.php'); ?>">
                                <?php _e('基本'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-muted" href="<?php $options->adminUrl('options-discussion.php'); ?>">
                                <?php _e('评论'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active fw-bold shadow-sm" href="<?php $options->adminUrl('options-reading.php'); ?>">
                                <?php _e('阅读'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-muted" href="<?php $options->adminUrl('options-permalink.php'); ?>">
                                <?php _e('永久链接'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card-modern">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <h4 class="mb-4 fw-bold text-dark"><?php _e('阅读设置'); ?></h4>
                            <div class="typecho-form-modern">
                                <?php \Widget\Options\Reading::alloc()->form()->render(); ?>
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
    // 基础美化
    $('.typecho-option input[type=text]').addClass('form-control');
    $('.typecho-option select').addClass('form-select w-auto d-inline-block mx-1');
    $('.typecho-option').addClass('list-unstyled mb-0');
    $('.typecho-option li').addClass('mb-4');
    $('.typecho-option label.typecho-label').addClass('form-label fw-bold small text-muted text-uppercase mb-1 d-block');
    $('.typecho-option p.description').addClass('form-text text-muted small mt-1');
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 rounded-pill shadow-sm fw-bold');
    $('.typecho-option input[type=checkbox], .typecho-option input[type=radio]').addClass('form-check-input me-1');

    // 针对 "站点首页" 的特殊布局处理
    $('.front-archive').addClass('mt-2 ms-4 p-3 bg-light rounded border');

    // 修复原生 JS (options-reading.php 中自带的 JS) 依赖的 DOM 结构
    $('#frontPage-recent, #frontPage-page, #frontPage-file').change(function () {
        var t = $(this);
        if (t.prop('checked')) {
            if ('frontPage-recent' == t.attr('id')) {
                $('.front-archive').addClass('d-none'); // 使用 Bootstrap 的 d-none 替代 hidden
            } else {
                $('.front-archive').removeClass('d-none hidden');
            }
        }
    });
    // 触发一次 change 以初始化状态
    $('input[name=frontPage]:checked').trigger('change');
});
</script>

<?php include 'footer.php'; ?>