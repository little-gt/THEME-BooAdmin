# 👻 BooAdmin Theme for Typecho

> **稳定 · 现代 · 极简**
>
> 一款基于 Bootstrap 5 重构的 Typecho 现代化后台主题。v1.0.1 版本回归原生加载机制，彻底解决了插件兼容性问题，并带来了全新的智能侧边栏体验。

[![Release](https://img.shields.io/badge/Release-v1.0.1-blue?style=flat-square)](https://github.com/little-gt/THEME-BooAdmin/releases)
[![Typecho](https://img.shields.io/badge/Typecho-1.2+-orange?style=flat-square&logo=typecho)](https://typecho.org)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952b3?style=flat-square&logo=bootstrap)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-GPLv3-green?style=flat-square)](LICENSE)

![预览图](screenshot/screenshot1.png)

## ✨ v1.0.1 更新亮点

**此版本是里程碑式的正式版更新，重点在于“回归稳定”与“交互升级”。**

### 🔌 极致的插件兼容性
- **移除 PJAX**: 为了确保所有第三方插件（尤其是包含复杂 JS 逻辑的统计、编辑器类插件）能 100% 正常工作，我们移除了 PJAX 机制，回归 Typecho 原生的生命周期。
- **补全核心钩子**: 重新加入了 `admin/index.php` (begin/end)、`menu.php`、`footer.php` 等关键位置的插件挂载点。现在，Access、Stat 等插件的面板可以完美显示在后台首页了。

### 🧭 智能侧边栏 (Smart Sidebar)
- **折叠式分组**: 菜单支持按组折叠/展开，保持界面整洁。
- **智能状态保持**: 自动展开当前所在的菜单组，其他无关组自动收起。
- **自动定位**: 刷新页面后，侧边栏会自动滚动并将当前激活的菜单项置于视野中心（防止在安装大量插件后菜单过长找不到当前项）。
- **插件图标支持**: 智能识别插件注入的菜单，并为其分配专属图标。

### 🎨 细节优化
- **移动端适配**: 修复了移动端侧边栏遮罩和切换按钮的逻辑。
- **消息提示**: 重写了 Flash Message (操作成功/失败提示) 的样式与触发逻辑，更加醒目且不遮挡内容。
- **Bootstrap 5**: 全面使用 BS5 的原生组件（Tooltip, Dropdown, Collapse），减少对 jQuery UI 的依赖，加载更快。

---

## 🚀 特性概览

| 功能模块 | 说明 |
| :-- | :-- |
| **现代化 UI** | 采用清爽的配色与卡片式设计，告别陈旧的后台界面。 |
| **完全响应式** | 完美适配 PC、平板与手机，随时随地管理博客。 |
| **开箱即用** | 无需繁琐配置，替换目录即可生效。 |
| **原生体验** | 保留 Typecho 所有原生操作习惯，但在视觉与交互上做减法。 |
| **代码高亮** | 内置主题编辑器支持代码高亮与行号显示。 |

---

## ⚙️ 安装与升级

**⚠️ 重要提示：操作前请务必备份您的网站数据库和 `admin` 目录！**

### 全新安装
1.  下载本项目的 [最新 Release 版本](https://github.com/little-gt/THEME-BooAdmin/releases)。
2.  解压压缩包。
3.  将解压得到的 `admin` 文件夹上传至您的 Typecho 网站根目录，**覆盖**原有的 `admin` 目录。
4.  登录后台，享受全新的 BooAdmin。

### 从 v1.0.0 或 Pre 版本升级
1.  **强烈建议**删除服务器上旧的 `admin` 目录（保留 `config.inc.php` 文件在根目录不动即可，`admin` 目录下通常没有配置文件）。
2.  上传 v1.0.1 版本的 `admin` 目录到网站根目录。
3.  清理浏览器缓存，以加载最新的 CSS 和 JS 文件。

---

## 📸 截图预览

|                登录页                 | 插件管理 |
|:----------------------------------:| :--: |
| ![仪表盘](screenshot/screenshot1.png) | ![插件管理](screenshot/screenshot2.png) |

|                编辑文件                 |               主题设置                |
|:-----------------------------------:|:---------------------------------:|
| ![撰写文章](screenshot/screenshot4.png) | ![菜单](screenshot/screenshot3.png) |

---

## 🧱 技术栈

*   **框架**: Bootstrap 5.3
*   **图标**: FontAwesome 6 Free
*   **脚本**: jQuery (核心依赖), Chart.js (图表支持)
*   **字体**: Nunito (英文字体优化)

---

## ❤️ 贡献与反馈

如果您在使用过程中遇到任何问题，或有更好的建议：

1.  欢迎提交 [Issues](https://github.com/little-gt/THEME-BooAdmin/issues) 反馈 BUG。
2.  欢迎提交 Pull Requests 贡献代码。
3.  如果喜欢这个主题，请点亮右上角的 ⭐ **Star** 支持作者！

---

**BooAdmin** — 让写作回归纯粹与优雅。