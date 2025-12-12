<?php
// 引入通用配置、头部和菜单文件
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<div class="container-fluid">

    <!-- 顶部操作栏 -->
    <div class="row mb-4">
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
                        <a href="https://typecho.org/plugins" target="_blank" class="btn btn-outline-primary px-4 fw-bold">
                            <i class="fa-solid fa-cart-plus me-2"></i><?php _e('获取更多插件'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 插件列表区域 -->
    <div class="row">
        <div class="col-12">

            <?php \Widget\Plugins\Rows::allocWithAlias('activated', 'activated=1')->to($activatedPlugins); ?>

            <!-- 已启用插件列表 -->
            <?php if ($activatedPlugins->have() || !empty($activatedPlugins->activatedPlugins)): ?>
            <div class="d-flex align-items-center mb-3 mt-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 me-2">
                    <i class="fa-solid fa-circle-check me-1"></i> Running
                </span>
                <h5 class="fw-bold text-dark mb-0"><?php _e('已启用的插件'); ?></h5>
            </div>

            <div class="row g-4 mb-5" style="animation-delay: 0.1s;">
                <?php while ($activatedPlugins->next()): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card-modern h-100 plugin-card active" id="plugin-<?php $activatedPlugins->name(); ?>">
                        <div class="card-body">
                            <div class="d-flex align-items-start mb-3">
                                <div class="plugin-icon bg-light-primary me-3">
                                    <i class="fa-solid fa-puzzle-piece"></i>
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="fw-bold text-dark mb-1 text-truncate" title="<?php $activatedPlugins->title(); ?>">
                                            <?php $activatedPlugins->title(); ?>
                                        </h5>
                                        <?php if (!$activatedPlugins->dependence): ?>
                                            <span class="text-danger" data-bs-toggle="tooltip" title="<?php _e('%s 无法在此版本的typecho下正常工作', $activatedPlugins->title); ?>">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="small text-muted mb-2">
                                        <span class="badge bg-light text-secondary border">v<?php $activatedPlugins->version(); ?></span>
                                        <span class="ms-1">by <?php echo empty($activatedPlugins->homepage) ? $activatedPlugins->author : '<a href="' . $activatedPlugins->homepage . '" target="_blank" class="text-decoration-none">' . $activatedPlugins->author . '</a>'; ?></span>
                                    </div>
                                </div>
                            </div>

                            <p class="text-secondary small mb-4 line-clamp-2" style="min-height: 40px;">
                                <?php $activatedPlugins->description(); ?>
                            </p>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <!-- 禁用插件按钮 -->
                                <a lang="<?php _e('你确认要禁用插件 %s 吗?', $activatedPlugins->name); ?>" href="<?php $security->index('/action/plugins-edit?deactivate=' . $activatedPlugins->name); ?>" class="btn-operate text-decoration-none d-flex align-items-center text-success fw-bold small">
                                    <i class="fa-solid fa-toggle-on fa-lg me-2"></i> <?php _e('已启用'); ?>
                                </a>

                                <!-- 插件设置按钮 -->
                                <?php if ($activatedPlugins->config): ?>
                                    <a href="<?php $options->adminUrl('options-plugin.php?config=' . $activatedPlugins->name); ?>" class="btn btn-sm btn-light text-primary rounded-pill px-3 fw-bold">
                                        <i class="fa-solid fa-gear me-1"></i> <?php _e('设置'); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small opacity-50"><i class="fa-solid fa-bolt me-1"></i><?php _e('即插即用'); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>

                <!-- 异常插件处理 -->
                <?php if (!empty($activatedPlugins->activatedPlugins)): ?>
                    <?php foreach ($activatedPlugins->activatedPlugins as $key => $val): ?>
                    <div class="col-md-6 col-xl-4">
                        <div class="card-modern h-100 plugin-card error border-danger border-start border-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="plugin-icon bg-light-danger me-3">
                                        <i class="fa-solid fa-bug"></i>
                                    </div>
                                    <h5 class="fw-bold text-danger mb-0"><?php echo $key; ?></h5>
                                </div>
                                <p class="text-danger small mb-3">
                                    <?php _e('此插件文件已经损坏或者被不安全移除, 强烈建议你禁用它'); ?>
                                </p>
                                <a lang="<?php _e('你确认要禁用插件 %s 吗?', $key); ?>" href="<?php $security->index('/action/plugins-edit?deactivate=' . $key); ?>" class="btn btn-sm btn-danger rounded-pill w-100">
                                    <i class="fa-solid fa-ban me-1"></i> <?php _e('强制禁用'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php \Widget\Plugins\Rows::allocWithAlias('unactivated', 'activated=0')->to($deactivatedPlugins); ?>

            <!-- 已禁用插件列表 -->
            <?php if ($deactivatedPlugins->have() || !$activatedPlugins->have()): ?>
            <div class="d-flex align-items-center mb-3 mt-4" style="animation-delay: 0.2s;">
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-2 me-2">
                    <i class="fa-solid fa-circle-pause me-1"></i> Disabled
                </span>
                <h5 class="fw-bold text-muted mb-0"><?php _e('禁用的插件'); ?></h5>
            </div>

            <div class="row g-4 mb-5" style="animation-delay: 0.3s;">
                <?php if ($deactivatedPlugins->have()): ?>
                    <?php while ($deactivatedPlugins->next()): ?>
                    <div class="col-md-6 col-xl-4">
                        <div class="card-modern h-100 plugin-card inactive" id="plugin-<?php $deactivatedPlugins->name(); ?>">
                            <div class="card-body opacity-75 hover-opacity-100 transition-all">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="plugin-icon bg-light me-3 text-muted">
                                        <i class="fa-solid fa-puzzle-piece"></i>
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <h5 class="fw-bold text-muted mb-1 text-truncate"><?php $deactivatedPlugins->title(); ?></h5>
                                        <div class="small text-muted mb-2">
                                            <span class="badge bg-light text-muted border">v<?php $deactivatedPlugins->version(); ?></span>
                                            <span class="ms-1">by <?php echo empty($deactivatedPlugins->homepage) ? $deactivatedPlugins->author : '<a href="' . $deactivatedPlugins->homepage . '" target="_blank" class="text-muted text-decoration-none">' . $deactivatedPlugins->author . '</a>'; ?></span>
                                    </div>
                                    </div>
                                </div>

                                <p class="text-muted small mb-4 line-clamp-2" style="min-height: 40px;">
                                    <?php $deactivatedPlugins->description(); ?>
                                </p>

                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <a href="<?php $security->index('/action/plugins-edit?activate=' . $deactivatedPlugins->name); ?>" class="text-decoration-none d-flex align-items-center text-muted fw-bold small hover-text-primary">
                                        <i class="fa-solid fa-toggle-off fa-lg me-2"></i> <?php _e('已禁用'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="card-modern text-center py-5">
                            <div class="text-muted opacity-50">
                                <i class="fa-solid fa-box-open fa-3x mb-3"></i>
                                <p><?php _e('没有安装插件'); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<style>
/* 插件卡片样式 */
.plugin-card { transition: all 0.3s ease; border: 1px solid transparent; }
.plugin-card.active { border-color: rgba(108, 92, 231, 0.1); }
.plugin-card.active:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(108, 92, 231, 0.1) !important; border-color: var(--primary-light); }
.plugin-card.inactive { background-color: #fcfcfd; border: 1px solid #f1f2f6; }
.plugin-card.inactive:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.05) !important; background-color: #fff; opacity: 1 !important; }
.plugin-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
.hover-text-primary:hover { color: var(--primary-color) !important; }
.hover-text-primary:hover i { color: var(--primary-color) !important; transform: scale(1.1); transition: transform 0.2s; }
</style>

<?php
include 'copyright.php';
include 'common-js.php';
?>

<script>
// 插件操作确认逻辑 - 修复 PJAX 绑定问题
(function() {
    // 使用 off().on() 防止 PJAX 重复绑定
    $(document).off('click.pluginAction', '.btn-operate, a[lang]');
    $(document).on('click.pluginAction', '.btn-operate, a[lang]', function (e) {
        var t = $(this), msg = t.attr('lang');
        if (msg && !confirm(msg)) {
            e.preventDefault(); // 阻止 PJAX 跳转
            return false;
        }
        // 如果 confirm 通过，PJAX 会自动处理 href 跳转
        // common-js.php 中的 pjax:complete 会负责 checkTypechoNotice 显示成功消息
    });
})();
</script>

<?php include 'footer.php'; ?>