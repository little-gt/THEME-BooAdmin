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
    '编辑外观' => 'fa-solid fa-code',
    '外观设置' => 'fa-solid fa-palette',
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
    '编辑分类' => 'fa-solid fa-folder-plus',
    '标签'   => 'fa-solid fa-tags',
    '文件'   => 'fa-solid fa-images',
    '编辑文件' => 'fa-solid fa-image',
    '用户'   => 'fa-solid fa-users',
    '编辑用户' => 'fa-solid fa-user-pen',
    '设置'   => 'fa-solid fa-sliders',
    '基本'   => 'fa-solid fa-gear',
    '阅读'   => 'fa-brands fa-readme',
    '永久链接' => 'fa-solid fa-link',
];

// 获取当前用户头像
$userAvatarUrl = \Typecho\Common::gravatarUrl($user->mail, 100, 'X', 'mm', $request->isSecure());

/**
 * 菜单数据构建逻辑
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
        [_t('编辑外观'), _t('编辑外观'), 'theme-editor.php', 'administrator', true],  // 隐藏，仅用于高亮
        [_t('外观设置'), _t('外观设置'), 'options-theme.php', 'administrator', true],  // 隐藏，仅用于高亮
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
        [_t('编辑分类'), _t('编辑分类'), 'category.php', 'editor', true],  // 隐藏，仅用于高亮
        [_t('标签'), _t('管理标签'), 'manage-tags.php', 'editor', false],
        [_t('文件'), _t('管理文件'), 'manage-medias.php', 'editor', false],
        [_t('编辑文件'), _t('编辑文件'), 'media.php', 'editor', true],  // 隐藏，仅用于高亮
        [_t('用户'), _t('管理用户'), 'manage-users.php', 'administrator', false],
        [_t('编辑用户'), _t('编辑用户'), 'user.php', 'administrator', true],  // 隐藏，仅用于高亮
    ],
    4 => [
        [_t('基本'), _t('基本设置'), 'options-general.php', 'administrator', false],
        [_t('评论'), _t('评论设置'), 'options-discussion.php', 'administrator', false],
        [_t('阅读'), _t('阅读设置'), 'options-reading.php', 'administrator', false],
        [_t('永久链接'), _t('永久链接设置'), 'options-permalink.php', 'administrator', false],
    ]
];

// 3. 合并插件扩展菜单 (从 options->panelTable 解析)
// Typecho 1.3.0 兼容：panelTable 可能已经是数组或需要反序列化
$panelTable = $options->panelTable;
if (is_string($panelTable)) {
    $panelTable = unserialize($panelTable);
}

if (is_array($panelTable) && isset($panelTable['parent']) && isset($panelTable['child'])) {
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

// 4. 构建最终菜单数组结构
foreach ($parentNodes as $key => $parentName) {
    if (!isset($childNodes[$key])) continue;

    $children = [];
    $isGroupActive = false; // 标记该组是否包含当前激活的页面
    $hiddenActiveName = null; // 用于存储隐藏但激活的菜单项名称

    foreach ($childNodes[$key] as $child) {
        $name = $child[0];
        $url = \Typecho\Common::url($child[2], $options->adminUrl);
        $access = $child[3] ?? 'administrator';
        $hidden = $child[4] ?? false;

        // 权限检查
        if (!$user->pass($access, true)) {
            continue;
        }

        // 判断当前激活状态 - 直接使用文件名匹配
        $isActive = false;
        $currentRequestUri = $request->getRequestUri();

        // 精确匹配文件名
        if (basename($currentRequestUri) === $child[2]) {
            $isActive = true;
        }
        // 首页特殊处理
        else if ($child[2] === 'index.php') {
            if ($currentRequestUri === $options->adminUrl || $currentRequestUri === $options->adminUrl . 'index.php' || $currentRequestUri === '/' || $currentRequestUri === '') {
                $isActive = true;
            }
        }

        if ($isActive) {
            $isGroupActive = true;
            // 如果是隐藏的菜单项,记录其名称
            if ($hidden) {
                $hiddenActiveName = $name;
            }
        }

        // 判断是否是插件菜单（用于分配默认图标）
        $isPluginItem = ($key >= 10);

        // 只有非隐藏的菜单项才添加到渲染列表中
        if (!$hidden) {
            $children[] = [
                'name' => $name,
                'url'  => $url,
                'active' => $isActive,
                'is_plugin' => $isPluginItem
            ];
        }
    }

    if (!empty($children)) {
        $menuItems[] = [
            'id' => 'menu-group-' . $key, // 用于 Bootstrap Collapse
            'title' => $parentName,
            'children' => $children,
            'expanded' => $isGroupActive // 如果组内有激活项，则展开
        ];
    }
}
?>

<!-- 侧边栏结构 -->
<nav class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <a href="<?php $options->adminUrl(); ?>" class="text-decoration-none d-flex align-items-center gap-2" style="color: var(--primary-color);">
            <i class="fa-solid fa-ghost"></i> <!-- BooAdmin Logo -->
            <span>BooAdmin</span>
        </a>
    </div>

    <!-- Menu Items (Scrollable Area) -->
    <div class="sidebar-menu" id="sidebarMenuContent">
        <?php foreach ($menuItems as $group): ?>
            <div class="menu-group mb-2">
                <!-- Group Header (Collapsible Trigger) -->
                <a class="menu-category d-flex justify-content-between align-items-center"
                   data-bs-toggle="collapse"
                   href="#<?php echo $group['id']; ?>"
                   role="button"
                   aria-expanded="<?php echo $group['expanded'] ? 'true' : 'false'; ?>"
                   aria-controls="<?php echo $group['id']; ?>">
                    <span><?php echo $group['title']; ?></span>
                    <i class="fa-solid fa-chevron-down transition-icon small opacity-50"></i>
                </a>

                <!-- Group Body (Collapsible Content) -->
                <div class="collapse <?php echo $group['expanded'] ? 'show' : ''; ?>" id="<?php echo $group['id']; ?>">
                    <div class="menu-list">
                        <?php foreach ($group['children'] as $item):
                            // 图标逻辑：优先映射 -> 插件默认拼图 -> 普通默认圆圈
                            $icon = isset($iconMapping[$item['name']])
                                ? $iconMapping[$item['name']]
                                : ($item['is_plugin'] ? 'fa-solid fa-puzzle-piece' : 'fa-regular fa-circle');
                        ?>
                            <a href="<?php echo $item['url']; ?>" class="nav-link <?php if($item['active']) echo 'active'; ?>">
                                <div class="nav-icon-wrapper">
                                    <i class="<?php echo $icon; ?>"></i>
                                </div>
                                <span class="nav-text"><?php echo $item['name']; ?></span>

                                <!-- 评论红点逻辑 - Typecho 1.3.0 兼容 -->
                                <?php if ($item['name'] == '评论'):
                                    $statWidget = \Widget\Stat::alloc();
                                    if ($statWidget->waitingCommentsNum > 0): ?>
                                    <span class="badge bg-danger rounded-pill ms-auto" style="font-size: 0.6rem; padding: 4px 8px;">
                                        <?php echo $statWidget->waitingCommentsNum; ?>
                                    </span>
                                <?php endif; endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- 底部用户信息区 -->
    <div class="sidebar-user">
        <div class="dropdown dropup w-100">
            <div class="user-card w-100" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?php echo $userAvatarUrl; ?>" alt="<?php $user->screenName(); ?>" class="user-avatar">
                <div style="flex: 1; min-width: 0;">
                    <div class="fw-bold text-truncate text-dark" style="font-size: 0.9rem;"><?php $user->screenName(); ?></div>
                    <div class="text-muted text-truncate" style="font-size: 0.75rem;"><?php $user->mail(); ?></div>
                </div>
                <i class="fa-solid fa-chevron-up text-muted ms-2" style="font-size: 0.8rem;"></i>
            </div>
            <ul class="dropdown-menu shadow border-0 mb-2 w-100" style="border-radius: 12px; padding: 8px;">
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

<!-- 菜单专用 CSS -->
<style>
/* 侧边栏整体布局 */
.sidebar {
    width: var(--sidebar-width);
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background: var(--bg-sidebar);
    border-right: 1px solid rgba(0,0,0,0.04);
    display: flex;
    flex-direction: column;
    z-index: 1020;
    transition: transform var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 4px 0 24px rgba(0,0,0,0.02);
}

/* 品牌区 */
.sidebar-brand {
    height: var(--header-height);
    display: flex;
    align-items: center;
    padding: 0 24px;
    font-size: 1.4rem;
    font-weight: 800;
    letter-spacing: -0.5px;
    border-bottom: 1px solid rgba(0,0,0,0.03);
    flex-shrink: 0; /* 防止被压缩 */
}

/* 菜单滚动区 */
.sidebar-menu {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 15px 12px;
}

/* 分类标题 (折叠触发器) */
.menu-category {
    font-size: 0.75rem;
    text-transform: uppercase;
    color: var(--text-light);
    font-weight: 700;
    padding: 10px 12px;
    letter-spacing: 0.5px;
    cursor: pointer;
    text-decoration: none;
    user-select: none;
    transition: all 0.2s ease;
}
.menu-category:hover {
    color: var(--primary-color);
}

/* 一级菜单高亮状态 */
.menu-category.active-category {
    color: var(--primary-color);
    background-color: var(--primary-soft);
    border-radius: 8px;
}

/* 旋转箭头动画 */
.menu-category .transition-icon {
    transition: transform 0.3s ease;
}
/* 当 aria-expanded="true" 时旋转箭头 */
.menu-category[aria-expanded="true"] .transition-icon {
    transform: rotate(180deg);
}

/* 一级菜单高亮时箭头颜色 */
.menu-category.active-category .transition-icon {
    color: var(--primary-color);
}

/* 菜单项容器 */
.menu-list {
    padding-bottom: 5px;
}

/* 单个菜单链接 */
.sidebar .nav-link {
    color: var(--text-muted);
    padding: 10px 16px;
    border-radius: var(--btn-radius);
    font-weight: 600;
    display: flex;
    align-items: center;
    margin-bottom: 2px;
    transition: all 0.2s ease;
    border: none;
    position: relative;
    overflow: hidden;
}

.nav-icon-wrapper {
    width: 24px;
    display: flex;
    justify-content: center;
    margin-right: 10px;
}

.sidebar .nav-link i {
    font-size: 1.1rem;
    color: #b2bec3;
    transition: transform 0.2s, color 0.2s;
}

/* 悬停状态 */
.sidebar .nav-link:hover {
    color: var(--primary-color);
    background-color: var(--primary-soft);
}
.sidebar .nav-link:hover i {
    color: var(--primary-color);
    transform: scale(1.1);
}

/* 激活状态 */
.sidebar .nav-link.active {
    background-color: var(--primary-color);
    color: #fff;
    box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
}
.sidebar .nav-link.active i {
    color: #fff;
}

/* 底部用户区 */
.sidebar-user {
    padding: 16px;
    border-top: 1px solid rgba(0,0,0,0.05);
    background: #fff;
    flex-shrink: 0; /* 防止被压缩 */
}

.user-card {
    display: flex;
    align-items: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: var(--btn-radius);
    cursor: pointer;
    transition: 0.2s;
    border: 1px solid transparent;
}
.user-card:hover {
    background: #f1f2f6;
    border-color: #e9ecef;
}
.user-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 12px;
    border: 2px solid #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
</style>

<!-- 菜单行为控制 JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. 自动定位到当前激活的菜单项
    // 这种微交互对于长菜单（特别是安装了很多插件时）非常重要
    const activeLink = document.querySelector('.sidebar .nav-link.active');
    if (activeLink) {
        // 使用 scrollIntoView 将元素滚动到视口中间
        // behavior: 'smooth' 会更好看，但在后台刷新场景下 'auto' 更直接
        activeLink.scrollIntoView({
            block: 'center',
            behavior: 'auto'
        });
    }

    // 2. 移动端侧边栏切换逻辑 (配合 header.php 中的按钮)
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');

    if (toggleBtn && sidebar && overlay) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });

        overlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }
});
</script>

<!-- 主内容区域包装器 (开始) -->
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