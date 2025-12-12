# 👻 BooAdmin Theme for Typecho

> 简洁 · 现代 · 高效
>
> 一款为 Typecho 打造的现代化、简洁高效的后台主题。基于 Bootstrap 5 重构，旨在告别传统界面，全面拥抱高效与美观。

[![项目版本](https://img.shields.io/badge/版本-1.0.1--pre-6f42c1?style=flat-square)](https://github.com/little-gt/THEME-BooAdmin)
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
| **免刷新体验** | 引入 PJAX 技术，避免频繁管理时页面频繁刷新，提升体验。 |
| **完全响应式** | 从桌面到手机，自适应各种屏幕尺寸，随时随地轻松管理。 |
| **体验优化** | 重新设计的登录、撰写、管理界面，操作流程更直观、更高效。 |
| **核心兼容** | 完美兼容 Typecho 1.2.1 核心功能，确保插件挂载点和基础功能稳定。 |
| **简单部署** | 仅需替换 `admin` 目录即可完成升级，无需复杂配置。 |
| **轻量高效** | 移除部分原生 jQuery UI 依赖，使用现代 JS 框架，加载速度更快。 |

---

## 🚀 更新日志

### v1.0.1-pre
  - **⚡ 核心稳定性修复 (PJAX)**:
  - **JS 加载机制重构**: 核心库（jQuery, Typecho Core）改为同步阻塞加载，彻底解决 `Uncaught ReferenceError: $ is not defined` 报错，确保内联脚本执行时依赖已就绪。
  - **生命周期管理**: 建立统一的 `reloadGlobalComponents` 全局重载入口，修复 PJAX 跳转后事件监听器重复绑定或失效的问题。
  - **DOM 清理**: 增加 PJAX 请求前的深度清理逻辑，强制销毁残留的 Tooltips、Dropdowns 和模态框背景，防止页面状态污染。
- **💬 评论管理重构**:
  - **快速编辑修复**: 修复点击“编辑”无法读取原有评论数据的问题，采用更稳健的 JSON 解析方案。
  - **交互优化**: 重新设计“快速回复”功能，回复框现在以动画形式在评论下方展开，且提交后自动高亮新回复。
  - **逻辑分离**: 将评论操作的 JS 逻辑与 PHP 视图彻底分离，并使用命名空间管理事件，杜绝多次提交。
- **✨ 体验优化**:
  - **全局消息反馈**: 优化 `checkTypechoNotice` 触发时机，修复插件启用/禁用、设置保存后无法弹出成功提示（Flash Message）的问题。
  - **UI 组件重载**: 修复 PJAX 跳转后 Bootstrap Tooltips（工具提示）和下拉菜单失效的问题。
  - **通用确认机制**: 统一了全站的删除/操作确认逻辑（`.btn-operate`），无需在每个页面单独绑定。

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

![支持我](screenshot/supportme.jpg)

---

**BooAdmin** — 为您的 Typecho 带来应有的现代后台体验。