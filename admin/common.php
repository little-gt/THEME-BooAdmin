<?php
if (!defined('__DIR__')) {
    define('__DIR__', dirname(__FILE__));
}

// 定义后台常量，这对许多插件判断当前环境至关重要
define('__TYPECHO_ADMIN__', true);

/** 载入配置文件 */
if (!defined('__TYPECHO_ROOT_DIR__') && !@include_once __DIR__ . '/../config.inc.php') {
    // 如果找不到配置，跳转到安装程序
    file_exists(__DIR__ . '/../install.php') ? header('Location: ../install.php') : print('Missing Config File');
    exit;
}

/** 初始化核心组件 */
\Widget\Init::alloc();

/** 注册一个初始化插件钩子 */
// 很多插件会在这个挂载点执行后台特定的逻辑
\Typecho\Plugin::factory('admin/common.php')->begin();

/** 初始化常用组件并赋值给变量，供后续页面直接使用 */
\Widget\Options::alloc()->to($options);   // $options: 全局设置
\Widget\User::alloc()->to($user);         // $user: 当前登录用户
\Widget\Security::alloc()->to($security); // $security: 安全/Token验证
\Widget\Menu::alloc()->to($menu);         // $menu: 菜单生成器

/** 初始化上下文请求和响应对象 */
$request = $options->request;
$response = $options->response;

/**
 * 登录状态与权限检查逻辑
 * 下面的逻辑处理了：
 * 1. 强制跳转登录页
 * 2. 首次登录欢迎页跳转
 * 3. 强制升级跳转
 */
$currentMenu = $menu->getCurrentMenu();

if (!empty($currentMenu)) {
    $params = parse_url($currentMenu[2]);
    $adminFile = basename($params['path']);

    // 如果未登录，跳转到登录页（除非是欢迎页设置标记）
    if (!$user->logged && !\Typecho\Cookie::get('__typecho_first_run')) {
        if ('welcome.php' != $adminFile) {
            $response->redirect(\Typecho\Common::url('welcome.php', $options->adminUrl));
        } else {
            \Typecho\Cookie::set('__typecho_first_run', 1);
        }
    } elseif ($user->pass('administrator', true)) {
        // 管理员权限下的版本升级检测
        $mustUpgrade = version_compare(\Typecho\Common::VERSION, $options->version, '>');

        if ($mustUpgrade && 'upgrade.php' != $adminFile && 'backup.php' != $adminFile) {
            // 如果核心版本高于数据库记录版本，强制跳转升级
            $response->redirect(\Typecho\Common::url('upgrade.php', $options->adminUrl));
        } elseif (!$mustUpgrade && 'upgrade.php' == $adminFile) {
            // 如果无需升级但访问了升级页，跳回首页
            $response->redirect($options->adminUrl);
        } elseif (!$mustUpgrade && 'welcome.php' == $adminFile && $user->logged) {
            // 如果已登录且无需升级，访问欢迎页则跳回首页
            $response->redirect($options->adminUrl);
        }
    }
}