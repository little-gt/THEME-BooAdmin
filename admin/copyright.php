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
        padding: 28px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        z-index: 1000;
        width: 90%;
        max-width: 640px;
        max-height: calc(100vh - 32px);
        overflow-y: auto;
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
        margin: 0 0 8px 0;
    }

    .booadmin-copyright-popup .content {
        display: flex;
        align-items: flex-start;
        gap: 20px;
    }
    
    .booadmin-copyright-popup .left {
        flex: 1;
        min-width: 0;
    }
    
    .booadmin-copyright-popup .right {
        flex: 0 0 240px;
    }

    .booadmin-copyright-popup .main-copy {
        margin: 0 0 12px 0;
        font-size: 13px;
        color: #475569;
        line-height: 1.6;
    }

    .booadmin-copyright-popup .support-points {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .booadmin-copyright-popup .support-points li {
        margin: 0 0 8px 0;
        padding: 8px 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        font-size: 13px;
        color: #334155;
        line-height: 1.6;
    }

    .booadmin-copyright-popup .donation-card {
        border: 1px solid #e2e8f0;
        padding: 8px;
        background: #ffffff;
    }
    
    .booadmin-copyright-popup p {
        margin: 0 0 16px 0;
        font-size: 14px;
        color: #4a5568;
        line-height: 1.5;
        text-align: left;
    }
    
    .booadmin-copyright-popup img {
        width: 100%;
        max-width: none;
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
        margin-top: 14px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .booadmin-copyright-popup .action a {
        display: inline-block;
        padding: 10px 16px;
        background-color: #3182ce;
        color: white;
        transition: all 0.2s ease;
        font-size: 13px;
        font-weight: 500;
        text-align: center;
    }
    
    .booadmin-copyright-popup .action a:hover {
        background-color: #2c5282;
    }

    .booadmin-copyright-popup .action a.secondary {
        background-color: #e2e8f0;
        color: #1f2937;
    }

    .booadmin-copyright-popup .action a.secondary:hover {
        background-color: #cbd5e1;
    }
    
    @media (max-width: 768px) {
        .booadmin-copyright-popup {
            padding: 18px;
            width: 95%;
            max-height: calc(100vh - 20px);
        }
        
        .booadmin-copyright-popup .content {
            flex-direction: column;
            gap: 16px;
        }
        
        .booadmin-copyright-popup .right {
            flex: 0 0 auto;
            width: 100%;
        }

        .booadmin-copyright-popup .donation-card {
            max-width: 280px;
            margin: 0 auto;
        }

        .booadmin-copyright-popup .main-copy,
        .booadmin-copyright-popup .support-points li {
            font-size: 12px;
        }

        .booadmin-copyright-popup .action {
            margin-top: 12px;
            flex-direction: column;
            gap: 8px;
        }

        .booadmin-copyright-popup .action a {
            width: 100%;
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
                <div class="version">版本 1.1.15</div>
                <div class="content">
                    <div class="left">
                        <p class="main-copy"><strong>BooAdmin 是免费开源项目。</strong>BooAdmin 的开源维护、CDN资源分发与新功能更新都离不开您的捐助。您的支持将帮助我覆盖以下成本：</p>

                        <ul class="support-points">
                            <li><strong>开源维护成本：</strong>进行版本适配、问题修复、体验优化与 LTS 支持。</li>
                            <li><strong>服务运行成本：</strong>提供静态资源分发、国际/国内线路与 IPv6 优化保障。</li>
                        </ul>

                        <div class="action">
                            <a href="https://github.com/little-gt/THEME-BooAdmin" target="_blank" rel="noopener">访问 GitHub 开源项目</a>
                            <a href="https://github.com/little-gt/THEME-BooAdmin/issues" class="secondary" target="_blank" rel="noopener">反馈问题</a>
                        </div>
                    </div>
                    <div class="right">
                        <div class="donation-card">
                            <img src="<?php echo $options->adminUrl; ?>img/supportme.jpg" alt="支持 BooAdmin 开源维护" />
                        </div>
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
