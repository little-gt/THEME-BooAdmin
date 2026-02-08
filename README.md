# 👻 BooAdmin Theme for Typecho

> **稳定 · 现代 · 极简**
>
> 一款基于 Bootstrap 5 重构的 Typecho 现代化后台主题。v1.0.3 版本深度兼容 Typecho 1.3.0，修复了评论管理功能的多项关键问题，带来更流畅的后台管理体验。

[![Release](https://img.shields.io/badge/Release-v1.0.3-blue?style=flat-square)](https://github.com/little-gt/THEME-BooAdmin/releases)
[![Typecho](https://img.shields.io/badge/Typecho-1.3+-orange?style=flat-square&logo=typecho)](https://typecho.org)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952b3?style=flat-square&logo=bootstrap)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-GPLv3-green?style=flat-square)](LICENSE)

![预览图](screenshot/screenshot1.png)

## ✨ v1.0.3 更新亮点

**此版本专注于评论管理功能的全面修复与优化。**

### 🔧 评论管理功能修复
- **jQuery Cookie 兼容性**: 修复了 jQuery 3.x 中 `$.cookie()` 函数不存在导致的评论管理页面功能失效
- **批量操作功能**: 修复了批量通过、待审核、标记垃圾、删除评论的功能，现在可以正常使用
- **清空垃圾评论**: 修复了清空垃圾评论按钮的处理逻辑
- **评论删除确认**: 修复了删除评论时的确认对话框功能
- **评论状态切换**: 修复了评论状态切换（通过/待审核/垃圾）功能
- **快速回复评论**: 修复了快速回复功能的变量作用域问题和 `.effect()` 方法兼容性
- **快速编辑评论**: 修复了编辑功能的数据加载和提交逻辑
- **滚动条位置记忆**: 修复了滚动条位置记忆功能，将 `$(window).bind()` 改为 `$(window).on()` 兼容 jQuery 3.x
- **复选框全选**: 修复了评论复选框的全选/取消全选功能，添加了对 `input[name="coid[]"]` 的支持
- **下拉菜单初始化**: 修复了下拉菜单初始化问题，兼容 Bootstrap 5 的 Dropdown 组件

### 🛡️ 代码健壮性提升
- **事件命名空间**: 使用命名空间 `.manageComments` 进行事件绑定和解绑，防止 PJAX 环境下事件重复绑定
- **错误处理**: 在评论编辑功能中增加了 JSON 解析的异常捕获
- **兼容性降级**: 为 `.effect()` 方法添加了兼容性检查，在不可用时使用 CSS 动画替代

### 🎯 交互体验优化
- **操作反馈**: 批量操作前会检查是否选中了评论，未选中时给出提示
- **确认对话框**: 删除操作和清空垃圾评论操作都有确认对话框，防止误操作
- **动画效果**: 回复成功后使用高亮动画提示用户

---

## 🚀 特性概览

| 功能模块 | 说明 |
| :-- | :-- |
| **现代化 UI** | 采用清爽的配色与卡片式设计，告别陈旧的后台界面。 |
| **完全响应式** | 完美适配 PC、平板与手机，随时随地管理博客。 |
| **开箱即用** | 无需繁琐配置，替换目录即可生效。 |
| **原生体验** | 保留 Typecho 所有原生操作习惯，但在视觉与交互上做减法。 |
| **代码高亮** | 内置主题编辑器支持代码高亮与行号显示。 |
| **Typecho 1.3.0 兼容** | 完美适配 Typecho 最新版本，无兼容性问题。 |

---

## ⚙️ 安装与升级

**⚠️ 重要提示：操作前请务必备份您的网站数据库和 `admin` 目录！**

### 全新安装
1.  下载本项目的 [最新 Release 版本](https://github.com/little-gt/THEME-BooAdmin/releases)。
2.  解压压缩包。
3.  将解压得到的 `admin` 文件夹上传至您的 Typecho 网站根目录，**覆盖**原有的 `admin` 目录。
4.  登录后台，享受全新的 BooAdmin。

### 从 v1.0.0、v1.0.1 或 v1.0.2 升级
1.  **强烈建议**删除服务器上旧的 `admin` 目录。
2.  上传 v1.0.3 版本的 `admin` 目录到网站根目录。
3.  清理浏览器缓存，以加载最新的 CSS 和 JS 文件。
4.  如果您使用的是 Typecho 1.3.0 或更高版本，升级后所有功能将自动适配。

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
*   **进度条**: NProgress 0.2.0

---

## 🐛 已知问题

如果您在使用过程中遇到任何问题，请优先尝试以下解决方案：

1.  **菜单高亮不生效**: 请确保 Typecho 版本 >= 1.2，并清理浏览器缓存。
2.  **插件面板不显示**: 请检查插件是否已激活，并确认插件是否在 `admin/index.php` 的 `begin` 或 `end` 钩子位置输出内容。
3.  **JavaScript 报错**: 请确保没有其他插件修改了全局 JavaScript 环境，并查看浏览器控制台的具体错误信息。

---

## 📋 更新日志

### v1.0.3 (2026-02-08)
- 🔧 修复 jQuery 3.x 中 `$.cookie()` 函数不存在导致的评论管理页面功能失效
- 🔧 修复批量通过、待审核、标记垃圾、删除评论的功能
- 🔧 修复清空垃圾评论按钮的处理逻辑
- 🔧 修复删除评论时的确认对话框功能
- 🔧 修复评论状态切换（通过/待审核/垃圾）功能
- 🔧 修复快速回复功能的变量作用域问题和 `.effect()` 方法兼容性
- 🔧 修复快速编辑功能的数据加载和提交逻辑
- 🔧 修复滚动条位置记忆功能，将 `$(window).bind()` 改为 `$(window).on()` 兼容 jQuery 3.x
- 🔧 修复评论复选框的全选/取消全选功能，添加了对 `input[name="coid[]"]` 的支持
- 🔧 修复下拉菜单初始化问题，兼容 Bootstrap 5 的 Dropdown 组件
- 🛡️ 使用命名空间 `.manageComments` 进行事件绑定和解绑，防止 PJAX 环境下事件重复绑定
- 🛡️ 在评论编辑功能中增加了 JSON 解析的异常捕获
- 🛡️ 为 `.effect()` 方法添加了兼容性检查，在不可用时使用 CSS 动画替代
- 📝 更新资源文件版本号为 v1.0.3

### v1.0.2 (2026-02-04)
- 🔧 修复 Typecho 1.3.0 `panelTable` 数据结构变更导致的 `unserialize()` 类型错误
- 🔧 修复 write-post.php 和 write-page.php 中 PHP 警告输出到 JavaScript 导致的语法错误
- 🔧 重构菜单高亮逻辑，使用文件名匹配代替路径匹配，提高可靠性
- 🔧 增强 Markdown 状态检测逻辑，增加异常捕获与容错处理
- 🛡️ 优化代码健壮性，多处增加 try-catch 块和错误抑制
- 📝 更新资源文件版本号为 v1.0.2

### v1.0.1
- 🎉 首个正式版发布
- ✨ 智能折叠式侧边栏
- 🔌 移除 PJAX，回归原生加载机制，提升插件兼容性
- 🧭 菜单状态保持与自动滚动
- 📱 移动端适配优化
- 🎨 Bootstrap 5 原生组件全面应用

### v1.0.0 Pre
- 🚀 初始预览版本
- 🎨 基于 Bootstrap 5 的 UI 重构

---

## ❤️ 贡献与反馈

如果您在使用过程中遇到任何问题，或有更好的建议：

1.  欢迎提交 [Issues](https://github.com/little-gt/THEME-BooAdmin/issues) 反馈 BUG。
2.  欢迎提交 Pull Requests 贡献代码。
3.  如果喜欢这个主题，请点亮右上角的 ⭐ **Star** 支持作者！

---

## 🔐 安全策略

如果您发现安全漏洞，请通过安全邮箱 [coolerxde@gt.ac.cn](mailto:coolerxde@gt.ac.cn) 私密报告，不要在 GitHub Issues 中公开披露。详细信息请参阅 [SECURITY.md](SECURITY.md)。

---

**BooAdmin** — 让写作回归纯粹与优雅。
