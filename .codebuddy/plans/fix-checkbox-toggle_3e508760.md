---
name: fix-checkbox-toggle
overview: 修复 manage-users.php 和 manage-categories.php 中多选框取消选择后状态不更新的 bug。问题根源在 typecho.js 中 tableSelectable 插件的事件处理逻辑：checkbox 的 click 事件冒泡到 tr 后会重复触发 toggle，导致两次取反等于状态不变。
todos:
  - id: fix-tableselectable-bug
    content: 修复 typecho.js 中 tableSelectable 插件的 checkbox 双重取反 bug
    status: completed
---

## 产品概述

`manage-users.php` 和 `manage-categories.php` 两个管理页面中，表格行的多选框（checkbox）存在取消选择失效的 bug。用户勾选多个 checkbox 后，再点击取消勾选，checkbox 的视觉状态不会发生变化。

## 核心功能

- 修复 checkbox 点击后状态双重取反导致显示不更新的 bug
- 确保全选/取消全选功能正常运作
- 修复影响范围涵盖所有使用 `tableSelectable` 插件的页面（manage-users.php、manage-categories.php、manage-tags.php）

## 技术栈

- jQuery 1.x（Typecho 核心）
- Typecho 内置 `tableSelectable` jQuery 插件

## Bug 根因分析

问题出在 `admin/js/typecho.js` 中 `tableSelectable` 插件的 `<tr>` 行点击事件处理逻辑。

**关键代码段**（已格式化便于理解）：

```javascript
// checkbox 的 click handler —— 点击时取反
u(o.checkEl, this).click(function(e) {
    r(u(this).parents(o.rowEl))  // 第一次取反
})

// <tr> 的 click handler —— 事件冒泡到这里再次取反
.click(function(e) {
    var t = u(e.toElement || e.target),
        n = t.prop("tagName").toLowerCase();
    // 条件：目标在交互元素列表中 且 不是 checkbox → stopPropagation
    // 否则 → 再次调用 r(this) 取反
    0 <= u.inArray(n, ["input","textarea","a","button","i"])
        && "checkbox" != t.attr("type")
        ? e.stopPropagation()
        : r(this)  // 第二次取反
})
```

**触发流程**：

1. 用户点击 checkbox
2. checkbox 的 click handler 触发 → 调用 `r(row)` → checkbox 状态取反（选中→未选中）
3. 事件冒泡到 `<tr>` 的 click handler
4. `e.target` 是 `<input type="checkbox">`，标签名 `"input"` 在交互元素数组中
5. 但 `"checkbox" != t.attr("type")` 为 **FALSE**（因为类型就是 checkbox）
6. AND 条件整体为 FALSE → 不执行 `stopPropagation` → 执行 `r(this)` 再次取反
7. **两次取反 = 状态不变**，checkbox 视觉上没有任何变化

## 修复方案

在 `admin/js/typecho.js` 的 `tableSelectable` 插件中，移除 `<tr>` click handler 条件中对 checkbox 的排除逻辑。

**修改前**：

```
0<=u.inArray(n,["input","textarea","a","button","i"])&&"checkbox"!=t.attr("type")?e.stopPropagation():r(this)
```

**修改后**：

```
0<=u.inArray(n,["input","textarea","a","button","i"])?e.stopPropagation():r(this)
```

**修改理由**：当用户直接点击 checkbox 时，checkbox 自身的 click handler 已经完成了一次取反，`<tr>` 的 click handler 应该对 checkbox 类型同样执行 `stopPropagation()` 以避免重复取反。点击行（非 checkbox 区域）时仍然会正常触发行级别的 toggle。

## 影响范围

此修复会影响所有使用 `tableSelectable` 的页面：

- `manage-users.php`（通过 `table-js.php` 加载）
- `manage-categories.php`（内联调用）
- `manage-tags.php`（内联调用）

## 实现细节

### 目录结构

```
admin/js/
└── typecho.js  # [MODIFY] 修复 tableSelectable 插件中 checkbox 双重取反 bug
```

### 实现说明

- 仅修改一行压缩代码中的条件表达式
- 删除 `&&"checkbox"!=t.attr("type")` 部分（共 28 个字符）
- 不影响其他功能（dropdownMenu、resizeable、tableDnD、mask 等）