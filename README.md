# 👻 BooAdmin Theme for Typecho

> 简洁 · 现代 · 高效
>
> 一款为 Typecho 打造的现代化、简洁高效的后台主题。基于 Bootstrap 5 重构，旨在告别传统界面，全面拥抱高效与美观。

[![项目版本](https://img.shields.io/badge/版本-1.0.0-6f42c1?style=flat-square)](https://github.com/little-gt/THEME-BooAdmin)
[![许可证: GPL v3](https://img.shields.io/badge/许可证-GPLv3-blue?style=flat-square)](https://www.gnu.org/licenses/gpl-3.0.html)
[![Bootstrap 版本](https://img.shields.io/badge/Bootstrap-5.3-7952b3?style=flat-square&logo=bootstrap)](https://getbootstrap.com/)
[![Typecho 版本](https://img.shields.io/badge/Typecho-1.2%2B-orange?style=flat-square&logo=typecho)](https://typecho.org/)
[![作者信息](https://img.shields.io/badge/作者-GARFIELDTOM-blueviolet?style=flat-square&logo=github)](https://github.com/little-gt/)

**主题预览：**

![登录界面](screenshot/screenshot1.png)

![插件管理](screenshot/screenshot2.png)

![网站外观](screenshot/screenshot3.png)

![编辑文件](screenshot/screenshot4.png)

**参与讨论：**

[前往知乎帖子进行讨论](https://zhuanlan.zhihu.com/p/1981856895309480062)

---

## ✨ 特点概览

| 功能模块 | 说明 |
| :-- | :-- |
| **现代化设计** | 基于 Bootstrap 5 与 FontAwesome 6，提供清爽、一致的视觉体验。 |
| **完全响应式** | 从桌面到手机，自适应各种屏幕尺寸，随时随地轻松管理。 |
| **体验优化** | 重新设计的登录、撰写、管理界面，操作流程更直观、更高效。 |
| **核心兼容** | 完美兼容 Typecho 1.2.1 核心功能，确保插件挂载点和基础功能稳定。 |
| **简单部署** | 仅需替换 `admin` 目录即可完成升级，无需复杂配置。 |
| **轻量高效** | 移除部分原生 jQuery UI 依赖，使用现代 JS 框架，加载速度更快。 |

---

## 🚀 更新日志

### v1.0.0
- **UI/UX**:
  - 全新的登录与注册界面。
  - 基于 Bootstrap 5 Card 组件重构所有管理页面。
  - 现代化、响应式的侧边栏与顶部导航栏设计。
- **功能修复与增强**:
  - 修复 `write-post.php` 和 `write-page.php` 的 JavaScript 兼容性问题。
  - 优化文章标签（TokenInput）加载与显示逻辑。
  - 改进附件上传区域的用户体验，整个区域均可点击。
  - 优化自定义字段区域，默认展开且样式统一。
  - 实现文章标题输入框根据内容自动增高。
- **技术栈**:
  - 前端框架升级至 **Bootstrap 5.3**。
  - 图标库升级至 **FontAwesome 6**。

---

## ⚙️ 安装指南

**⚠️ 重要提示：** 此主题将替换 Typecho 的整个 `admin` 核心目录。在开始之前，**请务必备份您的整个网站和数据库！**

1.  **备份**
    *   备份您网站根目录下的 `admin` 文件夹（例如，将其重命名为 `admin_backup`）。
    *   备份您的数据库。

2.  **下载主题**
    ```bash
    git clone https://github.com/little-gt/BooAdmin.git
    ```
    或者直接下载 ZIP 压缩包并解压。

3.  **替换目录**
    *   将下载好的 `BooAdmin` 文件夹中的 `admin` 目录，上传并覆盖到您 Typecho 网站的根目录。

4.  **完成**
    *   登录您的后台 (例如 `yourdomain.com/admin/`)，即可看到全新的 BooAdmin 界面。

---

## 🎨 配置与使用

BooAdmin 的核心设计理念是**零配置、开箱即用**。

您无需进行任何额外的设置，只需按照安装指南替换 `admin` 目录，即可享受现代化的后台体验。所有原生功能（包括插件和主题设置）都将无缝集成到新界面中。

---

## 🧱 使用的组件

| 组件 | 描述 |
| :------- | :----------------------------- |
| **框架** | Bootstrap 5.3 |
| **核心库** | jQuery (保留以兼容 Typecho 核心及插件) |
| **图标库** | FontAwesome 6 |
| **异步交互** | Plupload (文件上传) |
| **表单增强** | TokenInput (标签输入), TimePicker (日期选择) |

---

## ❤️ 开源与支持

> 如果你喜欢这个项目，请点个 ⭐ Star 支持！

---

**BooAdmin** — 为您的 Typecho 带来应有的现代后台体验。