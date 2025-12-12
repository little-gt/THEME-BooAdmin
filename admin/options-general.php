<?php
// 引入通用配置、头部和菜单文件
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- 顶部导航 Tabs -->
            <div class="card-modern mb-4">
                <div class="card-body">
                    <ul class="nav nav-pills bg-light p-2 rounded-3 d-inline-flex">
                        <li class="nav-item">
                            <a class="nav-link active fw-bold shadow-sm" href="<?php $options->adminUrl('options-general.php'); ?>">
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
                        <div class="col-md-12 col-lg-8">
                            <h4 class="mb-4 fw-bold text-dark"><?php _e('基本设置'); ?></h4>

                            <!-- Typecho 表单渲染容器 -->
                            <div class="typecho-form-modern">
                                <?php \Widget\Options\General::alloc()->form()->render(); ?>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-4 mt-4 mt-lg-0">
                            <div class="alert alert-info border-0 shadow-sm rounded-3">
                                <h5 class="alert-heading"><i class="fa-solid fa-circle-info me-2"></i>说明</h5>
                                <p class="small mb-0 opacity-75">这里定义了站点的名称、描述、关键词等核心信息。这些信息将被用于 SEO 和页面头部展示。</p>
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

                                <!-- 核心：表单美化脚本 -->
                                <script>
                                $(document).ready(function() {
                                    // 1. 给输入框添加 Bootstrap 类
                                    $('.typecho-option input[type=text], .typecho-option input[type=password], .typecho-option input[type=email], .typecho-option input[type=url]').addClass('form-control');
                                    $('.typecho-option textarea').addClass('form-control');
                                    $('.typecho-option select').addClass('form-select');

                                    // 2. 优化布局结构
                                    $('.typecho-option').addClass('list-unstyled mb-0');
                                    $('.typecho-option li').addClass('mb-4');
                                    $('.typecho-option label.typecho-label').addClass('form-label fw-bold small text-muted text-uppercase mb-1 d-block');
                                    $('.typecho-option p.description').addClass('form-text text-muted small mt-1');

                                    // 3. 按钮美化
                                    $('.typecho-option-submit button').addClass('btn btn-primary px-4 rounded-pill shadow-sm fw-bold').removeClass('btn-s');

                                    // 4. 单选/多选框美化 (Typecho 原生是一个 span 包裹 input 和 label)
                                    $('.typecho-option span').addClass('form-check d-inline-block me-3');
                                    $('.typecho-option span input').addClass('form-check-input');
                                    $('.typecho-option span label').removeClass('form-label fw-bold small text-muted text-uppercase mb-1 d-block').addClass('form-check-label');
                                });
                                </script>

                                <?php include 'footer.php'; ?>