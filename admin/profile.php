<?php
// 引入通用配置、头部和菜单文件
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();
\Widget\Users\Profile::alloc()->to($profile);
?>

<div class="container-fluid">
    

    <div class="row g-4" style="animation-delay: 0.1s;">
        
        <!-- 左侧：个人概览卡片 -->
        <div class="col-lg-4 col-xl-3">
            <div class="card-modern text-center position-relative overflow-hidden h-100">
                <!-- 背景装饰 -->
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-light" style="z-index: 0;"></div>
                <div class="position-absolute top-0 start-0 w-100" style="height: 120px; background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); z-index: 1;"></div>
                
                <div class="card-body position-relative" style="z-index: 2; padding-top: 60px;">
                    <!-- 头像 -->
                    <div class="position-relative d-inline-block mb-3">
                        <img src="<?php echo \Typecho\Common::gravatarUrl($user->mail, 220, 'X', 'mm', $request->isSecure()); ?>" 
                             alt="<?php $user->screenName(); ?>" 
                             class="rounded-circle border border-4 border-white shadow-sm"
                             style="width: 120px; height: 120px; object-fit: cover;">
                        <a href="https://gravatar.com/emails/" target="_blank" class="position-absolute bottom-0 end-0 btn btn-sm btn-light border shadow-sm" title="<?php _e('在 Gravatar 上修改头像'); ?>" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-camera text-muted small"></i>
                        </a>
                    </div>

                    <h4 class="fw-bold text-dark mb-1"><?php $user->screenName(); ?></h4>
                    <p class="text-muted mb-3">@<?php $user->name(); ?></p>
                    
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 mb-4">
                        <?php 
                        switch ($user->group) {
                            case 'administrator': _e('管理员'); break;
                            case 'editor': _e('编辑'); break;
                            case 'contributor': _e('贡献者'); break;
                            case 'subscriber': _e('关注者'); break;
                            default: _e('访客'); break;
                        } 
                        ?>
                    </span>

                    <div class="row g-2 border-top pt-4 mt-2">
                        <div class="col-6 border-end">
                            <h5 class="fw-bold mb-0 text-dark"><?php echo $stat->myPublishedPostsNum; ?></h5>
                            <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;"><?php _e('文章'); ?></small>
                        </div>
                        <div class="col-6">
                            <h5 class="fw-bold mb-0 text-dark"><?php echo $stat->myPublishedCommentsNum; ?></h5>
                            <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;"><?php _e('评论'); ?></small>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-start">
                        <small class="text-muted d-block mb-2">
                            <i class="fa-regular fa-envelope me-2"></i> <?php $user->mail(); ?>
                        </small>
                        <?php if($user->url): ?>
                        <small class="text-muted d-block mb-2 text-truncate">
                            <i class="fa-solid fa-link me-2"></i> <a href="<?php $user->url(); ?>" target="_blank" class="text-muted text-decoration-none"><?php $user->url(); ?></a>
                        </small>
                        <?php endif; ?>
                        <small class="text-muted d-block">
                            <i class="fa-regular fa-clock me-2"></i> 
                            <?php 
                            if ($user->logged > 0) {
                                $logged = new \Typecho\Date($user->logged);
                                _e('最后登录: %s', $logged->word());
                            }
                            ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- 右侧：设置表单 -->
        <div class="col-lg-8 col-xl-9">
            <div class="card-modern h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-4 px-4 pb-0">
                    <ul class="nav nav-pills card-header-pills bg-light p-1 rounded-3 d-inline-flex" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold small px-3" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-selected="true"><?php _e('基本资料'); ?></button>
                        </li>
                        <?php if ($user->pass('contributor', true)): ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold small px-3" id="pills-writing-tab" data-bs-toggle="pill" data-bs-target="#pills-writing" type="button" role="tab" aria-selected="false"><?php _e('撰写设置'); ?></button>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold small px-3" id="pills-password-tab" data-bs-toggle="pill" data-bs-target="#pills-password" type="button" role="tab" aria-selected="false"><?php _e('密码修改'); ?></button>
                        </li>
                        <!-- 插件设置占位符检测 -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold small px-3" id="pills-other-tab" data-bs-toggle="pill" data-bs-target="#pills-other" type="button" role="tab" aria-selected="false"><?php _e('更多设置'); ?></button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="pills-tabContent">
                        
                        <!-- 1. 基本资料 -->
                        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel">
                            <h5 class="fw-bold mb-4 text-dark"><?php _e('编辑个人资料'); ?></h5>
                            <div class="typecho-form-modern">
                                <?php $profile->profileForm()->render(); ?>
                            </div>
                        </div>

                        <!-- 2. 撰写设置 -->
                        <?php if ($user->pass('contributor', true)): ?>
                        <div class="tab-pane fade" id="pills-writing" role="tabpanel">
                            <h5 class="fw-bold mb-4 text-dark"><?php _e('撰写偏好设置'); ?></h5>
                            <div class="typecho-form-modern">
                                <?php $profile->optionsForm()->render(); ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- 3. 密码修改 -->
                        <div class="tab-pane fade" id="pills-password" role="tabpanel">
                            <div class="alert alert-warning border-0 shadow-sm mb-4">
                                <i class="fa-solid fa-shield-halved me-2"></i><?php _e('为了账号安全，建议您使用强密码（包含字母、数字和符号）。'); ?>
                            </div>
                            <h5 class="fw-bold mb-4 text-dark"><?php _e('修改登录密码'); ?></h5>
                            <div class="typecho-form-modern">
                                <?php $profile->passwordForm()->render(); ?>
                            </div>
                        </div>

                        <!-- 4. 更多设置 (插件挂载点) -->
                        <div class="tab-pane fade" id="pills-other" role="tabpanel">
                            <h5 class="fw-bold mb-4 text-dark"><?php _e('其他个性化设置'); ?></h5>
                            <div class="typecho-form-modern personal-plugin-config">
                                <?php $profile->personalFormList(); ?>
                            </div>
                            <p class="text-muted small mt-4 text-center" id="no-plugin-settings">
                                <?php _e('暂无更多设置项'); ?>
                            </p>
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
    // 1. 表单通用美化 (复用 options 页面的逻辑)
    $('.typecho-form-modern input[type=text], .typecho-form-modern input[type=password], .typecho-form-modern input[type=email], .typecho-form-modern input[type=url]').addClass('form-control');
    $('.typecho-form-modern textarea').addClass('form-control');
    $('.typecho-form-modern select').addClass('form-select');
    
    $('.typecho-option').addClass('list-unstyled mb-0');
    $('.typecho-option li').addClass('mb-4');
    $('.typecho-option label.typecho-label').addClass('form-label fw-bold small text-muted text-uppercase mb-1 d-block');
    $('.typecho-option p.description').addClass('form-text text-muted small mt-1');
    
    // 按钮美化
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 shadow-sm fw-bold');
    
    // 复选框/单选框美化
    $('.typecho-option input[type=radio], .typecho-option input[type=checkbox]').addClass('form-check-input me-1');
    $('.typecho-option span').addClass('d-inline-block me-3 form-check');
    $('.typecho-option span label').addClass('form-check-label');

    // 2. 检查是否有插件设置
    // 如果 personal-plugin-config 为空，显示“暂无设置”，否则隐藏提示
    // 注意：Typecho 输出 personalFormList 可能会直接 echo HTML，我们需要检测内容
    var pluginContent = $('.personal-plugin-config').html();
    if (pluginContent && pluginContent.trim().length > 0) {
        $('#no-plugin-settings').hide();
        // 插件输出的 section 标题美化
        $('.personal-plugin-config h3').addClass('fw-bold text-dark border-bottom pb-2 mb-3 mt-4 fs-6');
    } else {
        // 如果没有内容，也可以选择隐藏“更多设置”这个 Tab
        // $('#pills-other-tab').parent().hide();
    }
    
    // 3. 密码确认框的特殊处理
    // Typecho 的密码确认框通常没有 name 属性匹配，这里通用处理一下 w-60 类
    $('.w-60').removeClass('w-60').addClass('w-100').css('max-width', '400px');
});
</script>

<?php 
// 插件钩子
\Typecho\Plugin::factory('admin/profile.php')->bottom();
include 'footer.php'; 
?>