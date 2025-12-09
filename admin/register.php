<?php
include 'common.php';

// 如果已经登录，或系统关闭了注册，跳转回首页
if ($user->hasLogin() || !$options->allowRegister) {
    $response->redirect($options->siteUrl);
}

// 获取 Cookie 中记住的信息
$rememberName = htmlspecialchars(\Typecho\Cookie::get('__typecho_remember_name'));
$rememberMail = htmlspecialchars(\Typecho\Cookie::get('__typecho_remember_mail'));
\Typecho\Cookie::delete('__typecho_remember_name');
\Typecho\Cookie::delete('__typecho_remember_mail');

// 设置 Body 类名
$bodyClass = 'body-login';

include 'header.php';
?>

<style>
    /* 注册页专用样式 (与登录页保持一致) */
    body {
        background-color: #fff;
        overflow: hidden;
    }

    /* 隐藏全局布局元素 */
    .sidebar, .top-navbar, footer {
        display: none !important;
    }

    .login-wrapper {
        min-height: 100vh;
        width: 100%;
    }

    /* 左侧品牌区 - 使用略微不同的渐变以示区分 */
    .login-brand-side {
        background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%);
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #fff;
        overflow: hidden;
    }

    /* 装饰性背景圆 */
    .brand-circle {
        width: 500px;
        height: 500px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        position: absolute;
        top: -150px;
        left: -100px;
    }

    .brand-circle-2 {
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        position: absolute;
        bottom: 50px;
        right: -50px;
    }

    /* 右侧表单区 */
    .login-form-side {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 50px;
        background-color: #fff;
        position: relative;
    }

    .login-card {
        max-width: 400px;
        width: 100%;
        margin: 0 auto;
    }

    .form-control-lg {
        font-size: 1rem;
        padding: 0.8rem 1rem;
        border-color: #f1f2f6;
        background-color: #f8f9fa;
    }

    .form-control-lg:focus {
        background-color: #fff;
        border-color: var(--primary-light);
        box-shadow: 0 0 0 4px var(--primary-soft);
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-color: #f1f2f6;
        color: var(--text-muted);
    }

    .btn-register {
        padding: 0.8rem;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(108, 92, 231, 0.4);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(108, 92, 231, 0.5);
    }

    .back-home {
        position: absolute;
        top: 30px;
        right: 30px;
        z-index: 10;
    }

    /* 移动端适配 */
    @media (max-width: 768px) {
        .login-brand-side {
            display: none;
        }
        .login-form-side {
            padding: 30px;
        }
    }
</style>

<!-- 开启 main 标签，匹配 footer 闭合逻辑 -->
<main class="main-content p-0 m-0">

    <div class="row g-0 login-wrapper">

        <!-- 左侧：品牌展示 -->
        <div class="col-md-6 col-lg-7 d-none d-md-flex login-brand-side">
            <div class="brand-circle"></div>
            <div class="brand-circle-2"></div>

            <div class="text-center position-relative" style="z-index: 2;">
                <div class="mb-4">
                    <i class="fa-solid fa-user-plus fa-4x"></i>
                </div>
                <h1 class="fw-bold display-5 mb-3"><?php _e('加入我们'); ?></h1>
                <p class="lead opacity-75"><?php _e('开启您的创作之旅'); ?></p>
            </div>

            <div class="position-absolute bottom-0 mb-4 small opacity-50">
                &copy; <?php echo date('Y'); ?> Typecho Team.
            </div>
        </div>

        <!-- 右侧：注册表单 -->
        <div class="col-md-6 col-lg-5 login-form-side">

            <!-- 返回首页按钮 -->
            <a href="<?php $options->siteUrl(); ?>" class="btn btn-light rounded-circle shadow-sm back-home" title="<?php _e('返回首页'); ?>">
                <i class="fa-solid fa-xmark"></i>
            </a>

            <div class="login-card fade-in-up">
                <!-- 移动端 Logo -->
                <div class="text-center mb-4 d-md-none">
                    <i class="fa-solid fa-layer-group fa-3x text-primary"></i>
                </div>

                <div class="mb-4">
                    <h3 class="fw-bold text-dark"><?php _e('创建账号'); ?></h3>
                    <p class="text-muted small"><?php _e('请填写以下信息完成注册'); ?></p>
                </div>

                <form action="<?php $options->registerAction(); ?>" method="post" name="register" role="form">

                    <!-- 用户名 -->
                    <div class="mb-3">
                        <label for="name" class="form-label small text-muted fw-bold"><?php _e('用户名'); ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input type="text" id="name" name="name" placeholder="<?php _e('请输入用户名'); ?>" value="<?php echo $rememberName; ?>" class="form-control form-control-lg" required autofocus />
                        </div>
                        <div class="form-text small text-muted ms-1">
                            <i class="fa-solid fa-circle-info me-1"></i><?php _e('用户名将作为登录凭证'); ?>
                        </div>
                    </div>

                    <!-- 邮箱 -->
                    <div class="mb-4">
                        <label for="mail" class="form-label small text-muted fw-bold"><?php _e('Email'); ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" id="mail" name="mail" placeholder="<?php _e('请输入电子邮箱'); ?>" value="<?php echo $rememberMail; ?>" class="form-control form-control-lg" required />
                        </div>
                        <div class="form-text small text-muted ms-1">
                            <i class="fa-solid fa-shield-halved me-1"></i><?php _e('我们会向此邮箱发送初始密码'); ?>
                        </div>
                    </div>

                    <!-- 注册按钮 -->
                    <button type="submit" class="btn btn-primary w-100 btn-register rounded-3">
                        <?php _e('注 册'); ?>
                    </button>

                </form>

                <!-- 登录链接 -->
                <div class="text-center mt-4 pt-3 border-top">
                    <p class="text-muted small mb-0">
                        <?php _e('已有账号？'); ?>
                        <a href="<?php $options->adminUrl('login.php'); ?>" class="text-primary fw-bold text-decoration-none"><?php _e('立即登录'); ?></a>
                    </p>
                </div>

            </div>
        </div>
    </div>

<?php
include 'common-js.php';
?>
<script>
// 聚焦逻辑
$(document).ready(function () {
    $('#name').focus();
});
</script>
<?php
/* 执行 Typecho 底部插件钩子 */
\Typecho\Plugin::factory('admin/footer.php')->end();
?>
</main>
</body>
</html>