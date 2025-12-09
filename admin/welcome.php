<?php
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<style>
/* 欢迎页专用样式 */
.welcome-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 80vh;
}

.welcome-card {
    max-width: 650px;
    width: 100%;
    animation: fadeInUp 0.5s ease-out;
}

.welcome-step {
    display: flex;
    align-items: center;
    padding: 1rem 1.25rem;
    border-radius: 12px;
    transition: all 0.2s ease;
    cursor: pointer;
    border: 1px solid transparent;
}

.welcome-step:hover {
    background-color: var(--primary-soft);
    border-color: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.welcome-step-icon {
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

/* 高亮重要的安全步骤 */
.welcome-step.priority-high:hover {
    background-color: rgba(255, 118, 117, 0.1);
    border-color: rgba(255, 118, 117, 0.4);
}
.welcome-step.priority-high .welcome-step-icon {
    background-color: var(--danger);
    color: #fff;
}
</style>

<div class="container-fluid welcome-container">
    <div class="row">
        <div class="col-12">
            
            <div class="card-modern welcome-card shadow-lg">
                <div class="card-body p-4 p-md-5 text-center">
                    
                    <!-- 顶部 Logo 和标题 -->
                    <div class="mb-4">
                        <i class="fa-solid fa-layer-group fa-3x text-primary mb-3"></i>
                        <h2 class="fw-bold text-dark"><?php _e('欢迎使用 Typecho'); ?></h2>
                        <p class="text-muted">
                            <?php _e('很高兴与您一同开始创作之旅！在正式开始前，我们建议您完成以下几个简单步骤：'); ?>
                        </p>
                    </div>

                    <!-- 步骤引导列表 -->
                    <div class="list-group text-start">
                        
                        <!-- 1. 更改密码 (高优先级) -->
                        <a href="<?php $options->adminUrl('profile.php#change-password'); ?>" class="list-group-item list-group-item-action border-0 mb-2 p-0">
                            <div class="welcome-step priority-high">
                                <div class="welcome-step-icon me-3">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-danger mb-0"><?php _e('更改您的默认密码'); ?></h6>
                                    <small class="text-muted"><?php _e('为了保障您的账户安全，这是最重要的一步'); ?></small>
                                </div>
                                <div class="ms-auto text-danger">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </div>
                            </div>
                        </a>

                        <!-- 2. 撰写文章/查看站点 (根据权限不同) -->
                        <?php if($user->pass('contributor', true)): ?>
                            <a href="<?php $options->adminUrl('write-post.php'); ?>" class="list-group-item list-group-item-action border-0 mb-2 p-0">
                                <div class="welcome-step">
                                    <div class="welcome-step-icon bg-light-primary me-3">
                                        <i class="fa-solid fa-pen-nib"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0"><?php _e('撰写第一篇日志'); ?></h6>
                                        <small class="text-muted"><?php _e('开始分享您的想法和故事'); ?></small>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </div>
                                </div>
                            </a>
                            <a href="<?php $options->siteUrl(); ?>" target="_blank" class="list-group-item list-group-item-action border-0 mb-2 p-0">
                                <div class="welcome-step">
                                    <div class="welcome-step-icon bg-light-success me-3">
                                        <i class="fa-solid fa-globe"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0"><?php $user->pass('administrator', true) ? _e('查看我的站点') : _e('查看网站'); ?></h6>
                                        <small class="text-muted"><?php _e('看看您的网站在前台是什么样子'); ?></small>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </div>
                                </div>
                            </a>
                        <?php else: ?>
                            <a href="<?php $options->siteUrl(); ?>" target="_blank" class="list-group-item list-group-item-action border-0 mb-2 p-0">
                                <div class="welcome-step">
                                    <div class="welcome-step-icon bg-light-success me-3">
                                        <i class="fa-solid fa-globe"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0"><?php _e('查看网站'); ?></h6>
                                        <small class="text-muted"><?php _e('看看您的网站在前台是什么样子'); ?></small>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- 最终操作 -->
                    <div class="mt-5">
                        <form action="<?php $options->adminUrl(); ?>" method="get">
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm fw-bold">
                                <?php _e('直接进入控制台 &raquo;'); ?>
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?php
include 'copyright.php';
include 'common-js.php';
include 'footer.php';
?>