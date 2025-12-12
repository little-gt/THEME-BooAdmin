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
                            <a class="nav-link active fw-bold shadow-sm" href="<?php $options->adminUrl('options-discussion.php'); ?>">
                                <?php _e('评论'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-muted" href="<?php $options->adminUrl('options-reading.php'); ?>">
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
                        <div class="col-lg-9">
                            <h4 class="mb-4 fw-bold text-dark"><?php _e('评论设置'); ?></h4>
                            <div class="typecho-form-modern">
                                <?php \Widget\Options\Discussion::alloc()->form()->render(); ?>
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
    // 复用通用美化逻辑
    $('.typecho-option input[type=text], .typecho-option textarea').addClass('form-control');
    $('.typecho-option select').addClass('form-select w-auto d-inline-block mx-1'); // 针对行内 Select 优化
    $('.typecho-option').addClass('list-unstyled mb-0');
    $('.typecho-option li').addClass('mb-4 border-bottom pb-4 last-no-border');
    $('.typecho-option label.typecho-label').addClass('form-label fw-bold text-dark fs-6 mb-2 d-block');
    $('.typecho-option p.description').addClass('form-text text-muted small mt-1');
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 rounded-pill shadow-sm fw-bold');

    // 评论设置中有很多 Checkbox 列表，需要特殊处理
    $('.typecho-option input[type=checkbox]').addClass('form-check-input me-1');
    $('.typecho-option input[type=radio]').addClass('form-check-input me-1');

    // 处理多行结构 (span.multiline)
    $('.multiline').addClass('d-block mb-2');
});
</script>

<?php include 'footer.php'; ?>