# 安全策略

> BooAdmin 是一个完全开源、未压缩加密的 Typecho 后台主题，代码透明可审计。如发现安全问题，您可以根据本安全策略的说明进行处理，并且通知 BooAdmin 的开发者。

[![BooAdmin](https://img.shields.io/badge/BooAdmin-v1.2.0-blue?style=for-the-badge)](https://github.com/little-gt/THEME-BooAdmin/releases)
[![License](https://img.shields.io/badge/License-GPLv3-blue?style=for-the-badge)](LICENSE)

---

## 📌 支持版本

| 版本 | 状态 | 说明 |
| :--- | :---: | :--- |
| **v1.2.0** | ✅ 支持 | 当前主分支，持续接收安全更新 |
| **v1.1.x 系列** | ❌ EOL | 已完成样式重构，停止维护，请升级至 v1.1.18 及更高 |
| **v1.0.x 系列** | ❌ EOL | 已抵达生命周期，停止维护，请升级至 v1.1.18 及更高 |

---

## 📮 报告漏洞

> **请勿在 GitHub Issues 或公开场合披露漏洞**，请通过邮件披露，避免漏洞在被及时修复前被滥用，BooAdmin 感谢您的理解和支持。

**联系邮箱**：📬 [coolerxde@gt.ac.cn](mailto:coolerxde@gt.ac.cn)

**邮件标题格式**：`[Security] BooAdmin - 漏洞简述 - 版本 1.2.x`

**建议包含**：
- 影响版本与漏洞类型（XSS/CSRF/SQL注入等）
- 危害评估与复现步骤
- PoC 代码与环境信息（PHP/Typecho版本）

<details>
<summary><b>点击展开：邮件示例</b></summary>

**标题**: `[Security] BooAdmin - 存储型 XSS 漏洞 - 版本 1.2.0`

**正文**:
```
影响版本: v1.1.18
漏洞类型: 存储型 XSS
复现步骤: 
  1. 登录后台
  2. 提交评论 <script>alert(1)</script>
  3. 访问文章管理页触发
PoC: <img src=x onerror=alert('XSS')>
危害: 可窃取管理员 Cookie

致谢ID: Security-Hunter（可选）
```
</details>

---

## 🎯 范围流程

| ✅ 属于本项目 | ❌ 不属于本项目 |
| :--- | :--- |
| `admin/` 目录下主题 PHP 文件漏洞 | Typecho 核心漏洞 |
| 主题自带 JS/CSS 前端安全问题 | 第三方插件隐患 |
| 主题引发的 XSS/CSRF 漏洞 | 服务器配置不当 |
| 主题接口权限控制缺陷 | 弱口令攻击 |

**当收到处理范围内的报告时：**

1. **确认接收** - 48小时内回复
2. **评估修复** - 开发补丁期间请保持静默
3. **发布更新** - 在 v1.1.x 分支发布修复版本
4. **公开致谢** - 可选择是否署名

---


## 🔗 资源说明

BooAdmin 通过 [GARFIELDTOM'S NEST CDN](https://cdn.garfieldtom.cool) 加速分发外部静态资源，包括 FontAwesome、ECharts.js 及 TailwindCSS 等。为了确保用户始终获得良好的体验，CDN 会定期更新与整理缓存资源。因此，当 BooAdmin 发布新版本时，早期版本所引用的资源可能会被替换，建议您及时更新以获得最佳的显示效果。若您发现您的 BooAdmin 显示异常，请尝试更新主题版本。

如您希望自行托管这些资源以便更好地控制更新节奏，我们建议您确保资源版本与主题要求保持一致。同时，也建议您定期关注主题更新，以便及时获取最新的质量改进、功能优化与安全修复。

---

## 🔐 文件完整性校验

BooAdmin 提供了内置的文件完整性校验工具，用于检测主题文件是否被篡改。**强烈建议在每次更新 BooAdmin 后运行一次校验。**

### 工具位置

```
verify/
├── hash_tool.php    # 校验工具（支持 Web 和 CLI）
├── hash.json        # 本地 HASH 记录（生成后自动创建）
└── position.txt     # 目标目录配置（可选）
```

### 配置目标目录

校验工具默认检查 `admin/` 目录。如果您已将主题目录重命名（例如改为 `ABC`），需要通过 `position.txt` 指定：

1. 在 `verify/` 目录下创建 `position.txt` 文件
2. 文件内容只需写入目标目录名，例如：

```
ABC
```

3. 如果 `position.txt` 不存在或内容为空，工具将默认使用 `admin`

### 使用方式

#### Web 界面

![Web界面](https://cnb.cool/little-gt/BooAdmin/-/git/raw/main/screenshot/verify/screenshot1.png)

通过浏览器访问 `verify/hash_tool.php`，界面提供三个操作按钮：

| 按钮 | 功能 |
| :--- | :--- |
| **生成 HASH** | 扫描当前主题文件并生成本地 HASH 记录 |
| **远程校验** | 从官方 CDN 获取 HASH 进行比对，检测篡改 |
| **本地校验** | 与本地已生成的 HASH 记录进行比对 |

校验结果会以图表和文件列表的形式展示，标注每个文件的状态：

- ✅ **正常** - 文件未被修改
- ❌ **篡改** - 文件内容与官方记录不一致
- ➕ **新增** - 官方记录中不存在的文件
- ➖ **缺失** - 官方记录中存在但本地缺失的文件

#### 命令行（CLI）

```bash
# 生成 HASH 文件
php verify/hash_tool.php --generate

# 使用远程 HASH 校验（检测篡改）
php verify/hash_tool.php --verify

# 使用本地 HASH 校验
php verify/hash_tool.php --verify=local

# 查看当前状态
php verify/hash_tool.php --status

# 查看帮助
php verify/hash_tool.php --help
```

### 远程 HASH 地址

官方 HASH 文件托管于 CDN，工具会自动使用该地址进行远程校验：

```
https://cdn.garfieldtom.cool/resource/libs/booadmin/1.2.0/hash/hash.json
```

如果您打开工具时，提示工具被篡改或者不完整，请您尝试检查网络环境是否正常，或尝试更新到最新的已发布版本，开发版本可能因为没有更新权威数据导致校验异常。

![Web界面](https://cnb.cool/little-gt/BooAdmin/-/git/raw/main/screenshot/verify/screenshot2.png)

---

## ⚖️ 免责声明

本项目按 GNU GENERAL PUBLIC LICENSE V3 开源协议发布，使用者需自行承担风险，项目维护者不对任何损失承担法律责任。