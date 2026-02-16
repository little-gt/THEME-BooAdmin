<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>
<footer id="admin-footer" class="flex-shrink-0 py-4 text-center text-xs text-discord-muted bg-gray-50 border-t border-gray-200" role="contentinfo">
    <div class="mb-2">
        <a href="https://typecho.org" class="font-medium hover:text-discord-accent transition-colors" target="_blank" rel="noopener">Typecho</a>
        <span class="mx-2 text-gray-300">&bull;</span>
        <span><?php _e('由 <a href="https://typecho.org" class="hover:text-discord-accent" target="_blank" rel="noopener">%s</a> 驱动, 版本 %s', $options->software, $options->version); ?></span>
        <span class="mx-2 text-gray-300">&bull;</span>
        <span>Theme by <a href="https://github.com/little-gt/THEME-BooAdmin" class="font-medium hover:text-discord-accent transition-colors" target="_blank" rel="noopener">BooAdmin</a></span>
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
