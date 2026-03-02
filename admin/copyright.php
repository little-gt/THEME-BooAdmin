<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>
<style>
    .booadmin-copyright-tooltip {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }
    
    .booadmin-copyright-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
        display: none;
    }
    
    .booadmin-copyright-overlay.show {
        display: block;
    }
    
    .booadmin-copyright-popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        border: 1px solid #e2e8f0;
        padding: 32px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        z-index: 1000;
        width: 90%;
        max-width: 640px;
        display: none;
    }
    
    .booadmin-copyright-popup.show {
        display: block;
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    }
    
    .booadmin-copyright-popup h3 {
        margin: 0 0 8px 0;
        font-size: 20px;
        font-weight: 700;
        color: #1a202c;
        text-align: center;
    }
    
    .booadmin-copyright-popup .version {
        text-align: center;
        font-size: 12px;
        color: #718096;
        margin: 0 0 24px 0;
    }
    
    .booadmin-copyright-popup .content {
        display: flex;
        align-items: flex-start;
        gap: 32px;
    }
    
    .booadmin-copyright-popup .left {
        flex: 1;
        min-width: 0;
    }
    
    .booadmin-copyright-popup .right {
        flex: 0 0 200px;
    }
    
    .booadmin-copyright-popup p {
        margin: 0 0 16px 0;
        font-size: 14px;
        color: #4a5568;
        line-height: 1.5;
        text-align: left;
    }
    
    .booadmin-copyright-popup img {
        max-width: 100%;
        height: auto;
        display: block;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .booadmin-copyright-popup .close-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #718096;
        padding: 4px;
        line-height: 1;
        transition: all 0.2s ease;
    }
    
    .booadmin-copyright-popup .close-btn:hover {
        color: #4a5568;
        background-color: #f7fafc;
    }
    
    .booadmin-copyright-popup .action {
        margin-top: 24px;
    }
    
    .booadmin-copyright-popup .action a {
        display: inline-block;
        padding: 10px 40px;
        background-color: #3182ce;
        color: white;
        transition: all 0.2s ease;
        font-size: 14px;
        font-weight: 500;
    }
    
    .booadmin-copyright-popup .action a:hover {
        background-color: #2c5282;
    }
    
    @media (max-width: 640px) {
        .booadmin-copyright-popup {
            padding: 24px;
            width: 95%;
        }
        
        .booadmin-copyright-popup .content {
            flex-direction: column;
            text-align: center;
            gap: 24px;
        }
        
        .booadmin-copyright-popup .right {
            flex: 0 0 140px;
        }
    }
</style>

<div class="booadmin-copyright-overlay" id="booadminCopyrightOverlay"></div>

<footer id="admin-footer" class="flex-shrink-0 py-4 text-center text-xs text-discord-muted bg-gray-50 border-t border-gray-200" role="contentinfo">
    <div class="mb-2">
        <a href="https://typecho.org" class="font-medium hover:text-discord-accent transition-colors" target="_blank" rel="noopener">Typecho</a>
        <span class="mx-2 text-gray-300">&bull;</span>
        <span><?php _e('由 <a href="https://typecho.org" class="hover:text-discord-accent" target="_blank" rel="noopener">%s</a> 驱动, 版本 %s', $options->software, $options->version); ?></span>
        <span class="mx-2 text-gray-300">&bull;</span>
        <span>Theme by <span class="booadmin-copyright-tooltip">
            <a href="https://github.com/little-gt/THEME-BooAdmin" class="font-medium hover:text-discord-accent transition-colors" target="_blank" rel="noopener">BooAdmin</a>
            <div class="booadmin-copyright-popup" id="booadminCopyrightPopup">
                <button class="close-btn" onclick="closePopup(); event.stopPropagation();">&times;</button>
                <h3>关于 BooAdmin</h3>
                <div class="version">版本 1.1.7</div>
                <div class="content">
                    <div class="left">
                        <p>感谢您选择使用 BooAdmin 开源项目！</p>
                        <p>如果您希望支持 BooAdmin 后续开发与更新，可以扫描右侧小程序码支持开发者，感谢您的支持。</p>
                        <p class="text-sm text-gray-500">您可以在小程序中留言说明您希望的优化方向，相比于 GitHub Issue 会更优先看到和处理。</p>
                        <div class="action">
                            <a href="https://github.com/little-gt/THEME-BooAdmin" class="inline-block px-4 py-2 bg-discord-accent text-white hover:bg-discord-accent/90 transition-colors" target="_blank" rel="noopener">转跳到 GitHub 开源项目页</a>
                        </div>
                    </div>
                    <div class="right">
                        <img src="<?php echo $options->adminUrl; ?>img/supportme.jpg" alt="支持我" />
                    </div>
                </div>
            </div>
        </span></span>
    </div>
    <nav class="space-x-3 text-gray-400">
        <a href="https://docs.typecho.org" class="hover:text-discord-accent transition-colors" target="_blank" rel="noopener"><?php _e('帮助文档'); ?></a>
        <span>|</span>
        <a href="https://forum.typecho.org" class="hover:text-discord-accent transition-colors" target="_blank" rel="noopener"><?php _e('支持论坛'); ?></a>
        <span>|</span>
        <a href="https://github.com/typecho/typecho/issues" class="hover:text-discord-accent transition-colors" target="_blank" rel="noopener"><?php _e('报告错误'); ?></a>
        <span>|</span>
        <a href="https://typecho.org/download" class="hover:text-discord-accent transition-colors" target="_blank" rel="noopener"><?php _e('资源下载'); ?></a>
    </nav>
</footer>

<script>
    function togglePopup() {
        const popup = document.getElementById('booadminCopyrightPopup');
        const overlay = document.getElementById('booadminCopyrightOverlay');
        popup.classList.toggle('show');
        overlay.classList.toggle('show');
    }
    
    function openPopup() {
        const popup = document.getElementById('booadminCopyrightPopup');
        const overlay = document.getElementById('booadminCopyrightOverlay');
        if (!popup.classList.contains('show')) {
            popup.classList.add('show');
            overlay.classList.add('show');
        }
    }
    
    function closePopup() {
        const popup = document.getElementById('booadminCopyrightPopup');
        const overlay = document.getElementById('booadminCopyrightOverlay');
        popup.classList.remove('show');
        overlay.classList.remove('show');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const tooltip = document.querySelector('.booadmin-copyright-tooltip');
        const popup = document.getElementById('booadminCopyrightPopup');
        const overlay = document.getElementById('booadminCopyrightOverlay');
        
        tooltip.addEventListener('click', function(e) {
            e.preventDefault();
            openPopup();
        });
        
        overlay.addEventListener('click', function() {
            closePopup();
        });
        
        popup.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
</script>
