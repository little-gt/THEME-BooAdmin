<?php if (!defined('__TYPECHO_ADMIN__')) exit; ?>
<ul class="typecho-option-tabs fix-tabs flex space-x-1 bg-white p-1 rounded-lg border border-gray-200 mb-6">
    <li<?php if ($menu->getCurrentMenuUrl() === 'themes.php'): ?> class="current"<?php endif; ?>><a href="<?php $options->adminUrl('themes.php'); ?>" class="px-4 py-2 rounded-md text-sm font-medium transition-colors <?php if ($menu->getCurrentMenuUrl() === 'themes.php'): ?>bg-discord-accent text-white<?php else: ?>text-discord-muted hover:bg-gray-100 hover:text-discord-text<?php endif; ?>"><?php _e('可以使用的外观'); ?></a></li>
    <?php if (\Widget\Themes\Files::isWriteable()): ?>
        <li<?php if ($menu->getCurrentMenuUrl() === 'theme-editor.php'): ?> class="current"<?php endif; ?>><a href="<?php $options->adminUrl('theme-editor.php'); ?>" class="px-4 py-2 rounded-md text-sm font-medium transition-colors <?php if ($menu->getCurrentMenuUrl() === 'theme-editor.php'): ?>bg-discord-accent text-white<?php else: ?>text-discord-muted hover:bg-gray-100 hover:text-discord-text<?php endif; ?>">
                <?php if (!isset($files) || $options->theme == $files->theme): ?>
                    <?php _e('编辑当前外观'); ?>
                <?php else: ?>
                    <?php _e('编辑%s外观', ' <cite>' . $files->theme . '</cite> '); ?>
                <?php endif; ?>
            </a></li>
    <?php endif; ?>
    <?php if (\Widget\Themes\Config::isExists()): ?>
        <li<?php if ($menu->getCurrentMenuUrl() === 'options-theme.php'): ?> class="current"<?php endif; ?>><a href="<?php $options->adminUrl('options-theme.php'); ?>" class="px-4 py-2 rounded-md text-sm font-medium transition-colors <?php if ($menu->getCurrentMenuUrl() === 'options-theme.php'): ?>bg-discord-accent text-white<?php else: ?>text-discord-muted hover:bg-gray-100 hover:text-discord-text<?php endif; ?>"><?php _e('设置外观'); ?></a></li>
    <?php endif; ?>
</ul>
