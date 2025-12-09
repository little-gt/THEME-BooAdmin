<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>

<!-- 底部版权与链接栏 -->
<div class="container-fluid mt-auto">
    <footer class="footer-modern py-4 text-center">
        
        <!-- 资源链接组 -->
        <div class="footer-links mb-3 d-flex justify-content-center flex-wrap gap-3 gap-md-4">
            <a href="https://docs.typecho.org" target="_blank" class="footer-link" title="<?php _e('查看帮助文档'); ?>">
                <i class="fa-regular fa-file-lines me-1"></i><?php _e('帮助文档'); ?>
            </a>
            <a href="https://forum.typecho.org" target="_blank" class="footer-link" title="<?php _e('进入支持论坛'); ?>">
                <i class="fa-solid fa-comments me-1"></i><?php _e('支持论坛'); ?>
            </a>
            <a href="https://github.com/typecho/typecho/issues" target="_blank" class="footer-link" title="<?php _e('提交 Bug 报告'); ?>">
                <i class="fa-brands fa-github me-1"></i><?php _e('报告错误'); ?>
            </a>
            <a href="https://typecho.org/download" target="_blank" class="footer-link" title="<?php _e('下载更多资源'); ?>">
                <i class="fa-solid fa-cloud-arrow-down me-1"></i><?php _e('资源下载'); ?>
            </a>
        </div>

        <!-- 版权信息 -->
        <div class="footer-copyright small text-muted opacity-75">
            <p class="mb-1">
                &copy; <?php echo date('Y'); ?> 
                <a href="https://typecho.org" target="_blank" class="text-decoration-none fw-bold brand-text">Typecho</a>.
                <?php _e('由 <a href="https://typecho.org" target="_blank" class="text-muted text-decoration-none border-bottom border-secondary border-opacity-25">Typecho</a> 强力驱动'); ?>.
            </p>
            <p class="mb-0 font-monospace" style="font-size: 0.75rem;">
                Version <?php echo $options->version; ?>
            </p>
        </div>
    </footer>
</div>

<style>
/* 页脚专用样式 */
.footer-modern {
    border-top: 1px solid rgba(0,0,0,0.03);
    margin-top: 2rem;
}

.footer-link {
    color: var(--text-muted);
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    padding: 4px 8px;
    border-radius: 6px;
}

.footer-link:hover {
    color: var(--primary-color);
    background-color: var(--primary-soft);
    transform: translateY(-1px);
}

.footer-link i {
    opacity: 0.7;
}

.brand-text {
    color: var(--primary-color);
    transition: color 0.2s;
}
.brand-text:hover {
    color: var(--primary-hover);
}
</style>