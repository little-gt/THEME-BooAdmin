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
                            <a class="nav-link text-muted" href="<?php $options->adminUrl('options-reading.php'); ?>">
                                <?php _e('阅读'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active fw-bold shadow-sm" href="<?php $options->adminUrl('options-permalink.php'); ?>">
                                <?php _e('永久链接'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card-modern">
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-10">
                            <h4 class="mb-4 fw-bold text-dark"><?php _e('永久链接设置'); ?></h4>

                            <div class="alert alert-warning border-0 shadow-sm mb-4">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                <?php _e('如果您不了解这些设置的作用，请勿随意修改。错误的设置可能导致网站无法访问。'); ?>
                            </div>

                            <div class="typecho-form-modern">
                                <?php \Widget\Options\Permalink::alloc()->form()->render(); ?>
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
    $('.typecho-option select').addClass('form-select');
    $('.typecho-option').addClass('list-unstyled mb-0');
    $('.typecho-option li').addClass('mb-4 p-3 border rounded bg-light'); // 给每个选项加边框，区分度更高
    $('.typecho-option label.typecho-label').addClass('form-label fw-bold text-dark mb-2 d-block');
    $('.typecho-option p.description').addClass('form-text text-muted small mt-2');
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 rounded-pill shadow-sm fw-bold');

    // 单选框组优化
    $('.typecho-option span').addClass('d-block mb-2 form-check');
    $('.typecho-option input[type=radio]').addClass('form-check-input');
    $('.typecho-option span label').addClass('form-check-label');

    // <code> 标签样式优化
    $('code').addClass('bg-white border rounded px-1 text-primary');
});
</script>

<?php include 'footer.php'; ?>