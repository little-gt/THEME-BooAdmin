<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>
<footer class="mt-10 mb-6 text-center text-xs text-discord-muted" role="contentinfo">
    <div class="mb-2">
        <a href="https://typecho.org" class="font-bold hover:text-discord-accent transition-colors">Typecho</a>
        <span class="mx-1">&bull;</span>
        <span><?php _e('由 <a href="https://typecho.org" class="hover:underline">%s</a> 强力驱动, 版本 %s', $options->software, $options->version); ?></span>
    </div>
    <nav class="space-x-2">
        <a href="https://docs.typecho.org" class="hover:text-discord-accent transition-colors" target="_blank"><?php _e('帮助文档'); ?></a>
        <span class="text-gray-300">|</span>
        <a href="https://forum.typecho.org" class="hover:text-discord-accent transition-colors" target="_blank"><?php _e('支持论坛'); ?></a>
        <span class="text-gray-300">|</span>
        <a href="https://github.com/typecho/typecho/issues" class="hover:text-discord-accent transition-colors" target="_blank"><?php _e('报告错误'); ?></a>
        <span class="text-gray-300">|</span>
        <a href="https://typecho.org/download" class="hover:text-discord-accent transition-colors" target="_blank"><?php _e('资源下载'); ?></a>
    </nav>
</footer>
