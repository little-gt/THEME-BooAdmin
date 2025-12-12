<?php
// 引入通用配置、头部和菜单文件
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<div class="container-fluid">
    
    <!-- 顶部操作栏 - 页面标题与获取更多主题按钮 -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">
                            <i class="fa-solid fa-paintbrush me-2 text-primary"></i><?php _e('管理外观'); ?>
                        </h4>
                        <p class="text-muted mb-0 small">管理系统的主题外观</p>
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
    
    <div class="row">
        <div class="col-12">

            <!-- 顶部导航 Tabs - 切换主题管理、主题编辑和主题设置 -->
            <div class="card-modern mb-4">
                <div class="card-body">
                    <ul class="nav nav-pills bg-light p-2 rounded-3 d-inline-flex">
                        <li class="nav-item">
                            <a class="nav-link active fw-bold shadow-sm" href="<?php $options->adminUrl('themes.php'); ?>"><?php _e('可使用的外观'); ?></a>
                        </li>
                        <?php if (\Widget\Themes\Files::isWriteable()): // 仅当主题文件可写入时显示编辑入口 ?>
                        <li class="nav-item">
                            <a class="nav-link text-muted" href="<?php $options->adminUrl('theme-editor.php'); ?>"><?php _e('编辑当前外观'); ?></a>
                        </li>
                        <?php endif; ?>
                        <?php if (\Widget\Themes\Config::isExists()): // 仅当主题有设置项时显示设置入口 ?>
                        <li class="nav-item">
                            <a class="nav-link text-muted" href="<?php $options->adminUrl('options-theme.php'); ?>"><?php _e('设置外观'); ?></a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- 错误提示：主题文件丢失 -->
            <?php if ($options->missingTheme): ?>
                <div class="alert alert-danger shadow-sm border-0 mb-4 rounded-3 d-flex align-items-center" role="alert">
                    <i class="fa-solid fa-triangle-exclamation fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading fw-bold"><?php _e('检测到主题丢失'); ?></h5>
                        <p class="mb-0">
                            <?php _e('您之前使用的 "%s" 外观文件不存在，您可以重新上传此外观或者启用其他外观。', $options->missingTheme); ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>

            <?php // 获取所有主题列表
            \Widget\Themes\Rows::alloc()->to($themes); ?>

            <!-- 当前使用的主题 - 突出显示活动主题 -->
            <h5 class="fw-bold text-dark mb-3 px-1"><i class="fa-solid fa-circle-check text-success me-2"></i><?php _e('当前外观'); ?></h5>
            <div class="row mb-5">
                <div class="col-12">
                    <?php while($themes->next()): ?>
                        <?php if ($themes->activated): // 仅显示已激活的主题 ?>
                        <div class="card-modern overflow-hidden p-0">
                            <div class="row g-0">
                                <div class="col-md-5 col-lg-4 position-relative">
                                    <div class="theme-cover-hero h-100">
                                        <img src="<?php $themes->screen(); ?>" alt="<?php $themes->name(); ?>" class="img-fluid w-100 h-100 object-fit-cover">
                                        <div class="theme-overlay d-flex align-items-center justify-content-center">
                                            <span class="badge bg-success rounded-pill px-3 py-2 fs-6 shadow">
                                                <i class="fa-solid fa-check me-1"></i> <?php _e('正在使用'); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7 col-lg-8">
                                    <div class="card-body p-4 d-flex flex-column h-100 justify-content-center">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h2 class="fw-bold text-dark mb-1"><?php '' != $themes->title ? $themes->title() : $themes->name(); ?></h2>
                                                <div class="text-muted small">
                                                    <?php if ($themes->version): ?>
                                                        <span class="badge bg-light text-dark border me-2">v<?php $themes->version(); ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($themes->author): ?>
                                                        <span><?php _e('作者'); ?>:
                                                            <?php if ($themes->homepage): ?><a href="<?php $themes->homepage() ?>" target="_blank" class="fw-bold"><?php endif; ?>
                                                            <?php $themes->author(); ?>
                                                            <?php if ($themes->homepage): ?></a><?php endif; ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="text-secondary mt-3 mb-4" style="line-height: 1.6;">
                                            <?php echo nl2br($themes->description); ?>
                                        </p>

                                        <div class="mt-auto pt-3 border-top d-flex gap-2">
                                            <?php if (\Widget\Themes\Config::isExists()): // 如果当前主题有设置项 ?>
                                                <a href="<?php $options->adminUrl('options-theme.php'); ?>" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                                    <i class="fa-solid fa-sliders me-2"></i><?php _e('设置外观'); ?>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (\Widget\Themes\Files::isWriteable()): // 如果当前主题文件可写入 ?>
                                                <a href="<?php $options->adminUrl('theme-editor.php?theme=' . $themes->name); ?>" class="btn btn-outline-secondary rounded-pill px-4">
                                                    <i class="fa-solid fa-code me-2"></i><?php _e('编辑代码'); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- 可用主题列表 - 未激活的主题 -->
            <h5 class="fw-bold text-dark mb-3 px-1"><i class="fa-solid fa-box-open text-primary me-2"></i><?php _e('可用外观'); ?></h5>
            <div class="row g-4 pb-5">
                <?php
                // 重新实例化 Widget，以遍历所有主题（已激活主题在上方已显示，这里只显示未激活的）
                \Widget\Themes\Rows::alloc()->to($availableThemes);
                ?>

                <?php while($availableThemes->next()): ?>
                    <?php if (!$availableThemes->activated): // 仅显示未激活的主题 ?>
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="card-modern h-100 p-0 overflow-hidden theme-card">
                            <div class="theme-cover position-relative">
                                <img src="<?php $availableThemes->screen(); ?>" alt="<?php $availableThemes->name(); ?>" class="card-img-top object-fit-cover" style="height: 200px;">
                                <div class="theme-actions">
                                    <a href="<?php $security->index('/action/themes-edit?change=' . $availableThemes->name); ?>" class="btn btn-primary rounded-pill px-4 shadow fw-bold activate-btn">
                                        <?php _e('启用'); ?>
                                    </a>
                                </div>
                            </div>

                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold text-dark mb-0 text-truncate" title="<?php echo $availableThemes->title; ?>">
                                        <?php '' != $availableThemes->title ? $availableThemes->title() : $availableThemes->name(); ?>
                                    </h6>
                                    <?php if ($availableThemes->version): ?>
                                        <span class="badge bg-light text-secondary border small">v<?php $availableThemes->version(); ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="small text-muted mb-3 text-truncate">
                                    <?php _e('作者'); ?>:
                                    <?php if ($availableThemes->homepage): ?><a href="<?php $availableThemes->homepage() ?>" target="_blank" class="text-decoration-none"><?php endif; ?>
                                    <?php $availableThemes->author(); ?>
                                    <?php if ($availableThemes->homepage): ?></a><?php endif; ?>
                                </div>

                                <!-- 底部按钮组 (仅显示编辑，启用按钮在图片悬停上) -->
                                <?php if (\Widget\Themes\Files::isWriteable()): ?>
                                    <div class="d-grid">
                                        <a href="<?php $options->adminUrl('theme-editor.php?theme=' . $availableThemes->name); ?>" class="btn btn-sm btn-light text-muted">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> <?php _e('编辑'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            </div>

        </div>
    </div>
</div>

<style>
/* 主题封面比例与效果 */
.theme-cover-hero {
    min-height: 280px;
    background-color: #f1f2f6;
}

.theme-cover {
    background-color: #f1f2f6;
    overflow: hidden;
}

/* 悬停显示“启用”按钮的遮罩 */
.theme-actions {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(45, 52, 54, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    backdrop-filter: blur(2px);
}

.theme-card:hover .theme-actions {
    opacity: 1;
}

.activate-btn {
    transform: translateY(20px);
    transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.theme-card:hover .activate-btn {
    transform: translateY(0);
}

/* 当前主题的静态遮罩 */
.theme-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(to bottom, transparent 70%, rgba(0,0,0,0.1));
}
</style>

<?php
// 引入版权信息、通用JS和页脚文件
include 'copyright.php';
include 'common-js.php';
include 'footer.php';
?>