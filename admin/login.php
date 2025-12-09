<?php
include 'common.php';

// 如果已经登录，直接跳转到后台首页
if ($user->hasLogin()) {
    $response->redirect($options->adminUrl);
}

// 获取记住的用户名
$rememberName = htmlspecialchars(\Typecho\Cookie::get('__typecho_remember_name', ''));
\Typecho\Cookie::delete('__typecho_remember_name');

// 设置 Body 类名，方便 CSS 定位
$bodyClass = 'body-login';

include 'header.php';
?>

<style>
    /* 登录页专用样式覆盖 */
    body {
        background-color: #fff;
        overflow: hidden; /* 防止出现不必要的滚动条 */
    }

    /* 覆盖 header/menu 可能带来的干扰 */
    .sidebar, .top-navbar, footer {
        display: none !important;
    }

    .login-wrapper {
        min-height: 100vh;
        width: 100%;
    }

    /* 左侧品牌区 */
    .login-brand-side {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #fff;
        overflow: hidden;
    }

    .brand-circle {
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        position: absolute;
        top: -100px;
        right: -100px;
    }

    .brand-circle-2 {
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        position: absolute;
        bottom: -50px;
        left: -50px;
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

    .btn-login {
        padding: 0.8rem;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(108, 92, 231, 0.4);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-login:hover {
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

<main class="main-content p-0 m-0">

    <div class="row g-0 login-wrapper">

        <!-- 左侧：品牌展示 -->
        <div class="col-md-6 col-lg-7 d-none d-md-flex login-brand-side">
            <div class="brand-circle"></div>
            <div class="brand-circle-2"></div>

            <div class="text-center position-relative" style="z-index: 2;">
                <div class="mb-4">
                    <i class="fa-solid fa-layer-group fa-4x"></i>
                </div>
                <h1 class="fw-bold display-5 mb-3">Typecho</h1>
                <p class="lead opacity-75">轻量级、现代化的博客平台</p>
            </div>

            <div class="position-absolute bottom-0 mb-4 small opacity-50">
                &copy; <?php echo date('Y'); ?> Typecho Team.
            </div>
        </div>

        <!-- 右侧：登录表单 -->
        <div class="col-md-6 col-lg-5 login-form-side">

            <!-- 返回首页按钮 -->
            <a href="<?php $options->siteUrl(); ?>" class="btn btn-light shadow-sm back-home" title="<?php _e('返回首页'); ?>">
                <i class="fa-solid fa-home"></i> 首页
            </a>

            <div class="login-card fade-in-up">
                <!-- 移动端显示的 Logo -->
                <div class="text-center mb-4 d-md-none">
                    <i class="fa-solid fa-layer-group fa-3x text-primary"></i>
                    <h2 class="fw-bold mt-2 text-dark">Typecho</h2>
                </div>

                <div class="mb-4">
                    <h3 class="fw-bold text-dark"><?php _e('欢迎回来'); ?></h3>
                    <p class="text-muted small"><?php _e('请登录以管理您的网站'); ?></p>
                </div>

                <form action="<?php $options->loginAction(); ?>" method="post" name="login" role="form">

                    <!-- 用户名输入 -->
                    <div class="mb-3">
                        <label for="name" class="form-label small text-muted fw-bold"><?php _e('用户名'); ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input type="text" id="name" name="name" value="<?php echo $rememberName; ?>" placeholder="<?php _e('请输入用户名'); ?>" class="form-control form-control-lg" required autofocus />
                        </div>
                    </div>

                    <!-- 密码输入 -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label small text-muted fw-bold mb-0"><?php _e('密码'); ?></label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="<?php _e('请输入密码'); ?>" required />
                        </div>
                    </div>

                    <!-- 记住我 & 登录按钮 -->
                    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" value="1" id="remember" <?php if(\Typecho\Cookie::get('__typecho_remember_remember')): ?>checked<?php endif; ?> />
                            <label class="form-check-label text-muted small" for="remember">
                                <?php _e('下次自动登录'); ?>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-login rounded-3">
                        <?php _e('登 录'); ?> <i class="fa-solid fa-arrow-right ms-2"></i>
                    </button>

                    <!-- 安全参数 hidden -->
                    <input type="hidden" name="referer" value="<?php echo $request->filter('html')->get('referer'); ?>" />

                </form>

                <!-- 注册链接 -->
                <?php if($options->allowRegister): ?>
                <div class="text-center mt-4 pt-3 border-top">
                    <p class="text-muted small mb-0">
                        <?php _e('还没有账号？'); ?>
                        <a href="<?php $options->registerUrl(); ?>" class="text-primary fw-bold text-decoration-none"><?php _e('立即注册'); ?></a>
                    </p>
                </div>
                <?php endif; ?>

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