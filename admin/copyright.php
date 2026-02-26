<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>
<style>
    .booadmin-tooltip {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }
    
    .booadmin-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
        display: none;
    }
    
    .booadmin-overlay.show {
        display: block;
    }
    
    .booadmin-popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        border: 1px solid #e2e8f0;
        padding: 24px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        z-index: 1000;
        width: 320px;
        display: none;
    }
    
    .booadmin-popup.show {
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
    
    .booadmin-popup h3 {
        margin: 0 0 16px 0;
        font-size: 18px;
        font-weight: 600;
        color: #1a202c;
        text-align: center;
    }
    
    .booadmin-popup p {
        margin: 0 0 20px 0;
        font-size: 14px;
        color: #4a5568;
        line-height: 1.5;
        text-align: center;
    }
    
    .booadmin-popup img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto 20px;
    }
    
    .booadmin-popup .close-btn {
        position: absolute;
        top: 12px;
        right: 12px;
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        color: #718096;
        padding: 4px;
        line-height: 1;
    }
    
    .booadmin-popup .close-btn:hover {
        color: #4a5568;
    }
</style>

<div class="booadmin-overlay" id="booadminOverlay"></div>

<footer id="admin-footer" class="flex-shrink-0 py-4 text-center text-xs text-discord-muted bg-gray-50 border-t border-gray-200" role="contentinfo">
    <div class="mb-2">
        <a href="https://typecho.org" class="font-medium hover:text-discord-accent transition-colors" target="_blank" rel="noopener">Typecho</a>
        <span class="mx-2 text-gray-300">&bull;</span>
        <span><?php _e('由 <a href="https://typecho.org" class="hover:text-discord-accent" target="_blank" rel="noopener">%s</a> 驱动, 版本 %s', $options->software, $options->version); ?></span>
        <span class="mx-2 text-gray-300">&bull;</span>
        <span>Theme by <span class="booadmin-tooltip">
            <a href="https://github.com/little-gt/THEME-BooAdmin" class="font-medium hover:text-discord-accent transition-colors" target="_blank" rel="noopener">BooAdmin</a>
            <div class="booadmin-popup" id="booadminPopup">
                <button class="close-btn" onclick="togglePopup(); event.stopPropagation();">&times;</button>
                <h3>关于 BooAdmin</h3>
                <p>感谢您选择使用 BooAdmin 开源项目！</p>
                <img src="<?php echo $options->adminUrl; ?>img/supportme.jpg" alt="支持我" />
                <p class="mt-4 text-sm">如果您希望支持 BooAdmin 后续开发与更新，可以支持开发者，感谢您的支持。</p>
                <div class="text-center mt-4">
                    <a href="https://github.com/little-gt/THEME-BooAdmin" class="inline-block px-4 py-2 bg-discord-accent text-white hover:bg-discord-accent/90 transition-colors" target="_blank" rel="noopener">转跳到 GitHub 开源项目页</a>
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
        const popup = document.getElementById('booadminPopup');
        const overlay = document.getElementById('booadminOverlay');
        popup.classList.toggle('show');
        overlay.classList.toggle('show');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const tooltip = document.querySelector('.booadmin-tooltip');
        const popup = document.getElementById('booadminPopup');
        const overlay = document.getElementById('booadminOverlay');
        
        tooltip.addEventListener('click', function(e) {
            e.preventDefault();
            togglePopup();
        });
        
        overlay.addEventListener('click', function() {
            popup.classList.remove('show');
            overlay.classList.remove('show');
        });
    });
</script>
