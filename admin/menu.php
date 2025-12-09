<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>

<?php
/**
 * 定义图标映射数组
 */
$iconMapping = [
    '控制台' => 'fa-solid fa-gauge-high',
    '概要'   => 'fa-solid fa-chart-line',
    '个人设置' => 'fa-solid fa-user-gear',
    '插件'   => 'fa-solid fa-plug',
    '外观'   => 'fa-solid fa-paintbrush',
    '设置外观' => 'fa-solid fa-palette',
    '备份'   => 'fa-solid fa-database',
    '升级'   => 'fa-solid fa-cloud-arrow-up',
    '欢迎'   => 'fa-solid fa-hand-spock',
    '撰写'   => 'fa-solid fa-pen-nib',
    '撰写文章' => 'fa-solid fa-file-pen',
    '创建页面' => 'fa-regular fa-file-lines',
    '管理'   => 'fa-solid fa-list-check',
    '文章'   => 'fa-solid fa-layer-group',
    '独立页面' => 'fa-solid fa-file-invoice',
    '评论'   => 'fa-solid fa-comments',
    '分类'   => 'fa-solid fa-folder-tree',
    '标签'   => 'fa-solid fa-tags',
    '文件'   => 'fa-solid fa-images',
    '用户'   => 'fa-solid fa-users',
    '设置'   => 'fa-solid fa-sliders',
    '基本'   => 'fa-solid fa-gear',
    '阅读'   => 'fa-brands fa-readme',
    '永久链接' => 'fa-solid fa-link',
];

// 获取当前用户头像
$userAvatarUrl = \Typecho\Common::gravatarUrl($user->mail, 100, 'X', 'mm', $request->isSecure());

/**
 * 重建菜单数据结构
 * 因为 Widget_Menu 的 $menu 属性是私有的，我们需要在视图层重新组装数据
 */
$currentUrl = $request->getRequestUrl();
$menuItems = [];

// 1. 定义基础父菜单
$parentNodes = [1 => _t('控制台'), 2 => _t('撰写'), 3 => _t('管理'), 4 => _t('设置')];

// 2. 定义基础子菜单 [Name, Title, Url, Access, Hidden]
$childNodes = [
    1 => [
        [_t('概要'), _t('网站概要'), 'index.php', 'subscriber', false],
        [_t('插件'), _t('插件管理'), 'plugins.php', 'administrator', false],
        [_t('外观'), _t('网站外观'), 'themes.php', 'administrator', false],
        [_t('备份'), _t('备份'), 'backup.php', 'administrator', false],
        [_t('个人设置'), _t('个人设置'), 'profile.php', 'subscriber', false],
    ],
    2 => [
        [_t('撰写文章'), _t('撰写新文章'), 'write-post.php', 'contributor', false],
        [_t('创建页面'), _t('创建新页面'), 'write-page.php', 'editor', false],
    ],
    3 => [
        [_t('文章'), _t('管理文章'), 'manage-posts.php', 'contributor', false],
        [_t('独立页面'), _t('管理独立页面'), 'manage-pages.php', 'editor', false],
        [_t('评论'), _t('管理评论'), 'manage-comments.php', 'contributor', false],
        [_t('分类'), _t('管理分类'), 'manage-categories.php', 'editor', false],
        [_t('标签'), _t('管理标签'), 'manage-tags.php', 'editor', false],
        [_t('文件'), _t('管理文件'), 'manage-medias.php', 'editor', false],
        [_t('用户'), _t('管理用户'), 'manage-users.php', 'administrator', false],
    ],
    4 => [
        [_t('基本'), _t('基本设置'), 'options-general.php', 'administrator', false],
        [_t('评论'), _t('评论设置'), 'options-discussion.php', 'administrator', false],
        [_t('阅读'), _t('阅读设置'), 'options-reading.php', 'administrator', false],
        [_t('永久链接'), _t('永久链接设置'), 'options-permalink.php', 'administrator', false],
    ]
];

// 3. 合并插件扩展菜单 (从 options->panelTable 解析)
$panelTable = unserialize($options->panelTable);
if (isset($panelTable['parent']) && isset($panelTable['child'])) {
    foreach ($panelTable['parent'] as $key => $val) {
        $parentNodes[10 + $key] = $val;
    }
    foreach ($panelTable['child'] as $key => $val) {
        if (isset($childNodes[10 + $key])) {
            $childNodes[10 + $key] = array_merge($childNodes[10 + $key], $val);
        } else {
            $childNodes[10 + $key] = $val;
        }
    }
}

// 4. 构建最终菜单数组
foreach ($parentNodes as $key => $parentName) {
    if (!isset($childNodes[$key])) continue;
    
    $children = [];
    $hasActiveChild = false;
    
    foreach ($childNodes[$key] as $child) {
        // 解构数组：[0]Name, [1]Title, [2]Url, [3]Access, [4]Hidden
        $name = $child[0];
        $url = \Typecho\Common::url($child[2], $options->adminUrl);
        $access = $child[3] ?? 'administrator';
        $hidden = $child[4] ?? false;
        
        // 权限检查
        if (!$user->pass($access, true)) {
            continue;
        }
        
        // 隐藏检查 (有些菜单如 update.php 是默认隐藏的)
        // 在新 UI 中，我们可以选择性展示，这里保持原逻辑，但也允许强制显示某些项
        if ($hidden) {
             // 允许例外：如果是当前正在访问的页面，即使标记为 hidden 也应该高亮（但不一定显示在菜单里）
             // 这里为了简化，我们跳过 hidden 的菜单，除非它是插件添加的面板
        }

        // 判断当前激活状态 (简单的 URL 包含匹配)
        $isActive = false;
        if (strpos($currentUrl, $url) !== false) {
            $isActive = true;
            $hasActiveChild = true;
        }
        // 特殊处理首页
        if ($child[2] == 'index.php' && $request->getRequestUri() == $options->adminUrl) {
            $isActive = true;
        }

        $children[] = [
            'name' => $name,
            'url'  => $url,
            'active' => $isActive
        ];
    }
    
    if (!empty($children)) {
        $menuItems[] = [
            'title' => $parentName,
            'children' => $children
        ];
    }
}
?>

<!-- 侧边栏结构 -->
<nav class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <a href="<?php $options->adminUrl(); ?>" class="text-decoration-none d-flex align-items-center" style="color: var(--primary-color);">
            <span>Typecho</span>
        </a>
    </div>

    <!-- Menu Items -->
    <div class="sidebar-menu">
        <?php foreach ($menuItems as $group): ?>
            <div class="menu-category"><?php echo $group['title']; ?></div>
            
            <?php foreach ($group['children'] as $item): 
                $icon = isset($iconMapping[$item['name']]) ? $iconMapping[$item['name']] : 'fa-regular fa-circle';
            ?>
                <a href="<?php echo $item['url']; ?>" class="nav-link <?php if($item['active']) echo 'active'; ?>">
                    <i class="<?php echo $icon; ?>"></i>
                    <span><?php echo $item['name']; ?></span>
                    
                    <!-- 评论红点逻辑 -->
                    <?php if ($item['name'] == '评论'): 
                        $stat = Typecho\Widget::widget('Widget_Stat');
                        if ($stat->waitingCommentsNum > 0): ?>
                        <span class="badge bg-danger rounded-pill ms-auto" style="font-size: 0.6rem; padding: 4px 8px;">
                            <?php echo $stat->waitingCommentsNum; ?>
                        </span>
                    <?php endif; endif; ?>
                </a>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>

    <!-- 底部用户信息区 -->
    <div class="sidebar-user">
        <div class="dropdown dropup">
            <div class="user-card" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?php echo $userAvatarUrl; ?>" alt="<?php $user->screenName(); ?>" class="user-avatar">
                <div style="flex: 1; min-width: 0;">
                    <div class="fw-bold text-truncate" style="font-size: 0.9rem;"><?php $user->screenName(); ?></div>
                    <div class="text-muted text-truncate" style="font-size: 0.75rem;"><?php $user->mail(); ?></div>
                </div>
                <i class="fa-solid fa-chevron-up text-muted ms-2" style="font-size: 0.8rem;"></i>
            </div>
            <ul class="dropdown-menu shadow border-0" style="border-radius: 12px; padding: 8px;">
                <li>
                    <a class="dropdown-item rounded-2 py-2" href="<?php $options->adminUrl('profile.php'); ?>">
                        <i class="fa-solid fa-user-pen me-2 text-primary"></i> <?php _e('个人设置'); ?>
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item rounded-2 py-2 text-danger" href="<?php $options->logoutUrl(); ?>">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> <?php _e('登出'); ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- 主内容区域包装器 (开始) -->
<!-- 注意：这个 div 会在 footer.php 中闭合 -->
<main class="main-content">

    <!-- 顶部导航栏 -->
    <div class="top-navbar fade-in-up">
        <div class="d-flex align-items-center">
            <!-- 移动端侧边栏切换按钮 -->
            <button class="btn btn-light d-lg-none me-3 shadow-sm border-0" id="sidebarToggle" style="width: 40px; height: 40px; border-radius: 50%;">
                <i class="fa-solid fa-bars"></i>
            </button>
            
            <!-- 页面标题 -->
            <div>
                <h1 class="page-title"><?php echo $menu->title; ?></h1>
                <?php if($menu->title == '网站概要'): ?>
                    <p class="text-muted mb-0 small">
                        <?php echo date('Y年m月d日'); ?> &bull; Typecho <?php echo $options->version; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- 快捷操作区 -->
        <div class="quick-actions d-flex align-items-center">
            <!-- 查看站点 -->
            <a href="<?php $options->siteUrl(); ?>" target="_blank" class="btn-circle" title="<?php _e('查看网站'); ?>">
                <i class="fa-solid fa-earth-asia"></i>
            </a>
        </div>
    </div>

    <!-- 消息提示区域 -->
    <div class="row">
        <div class="col-12">
            <div id="typecho-message-container">
                <!-- JS 会在这里插入提示信息 -->
            </div>
        </div>
    </div>