# 安全策略

> BooAdmin 是一个完全开源、未压缩加密的 Typecho 后台主题，代码透明可审计。如发现安全问题，您可以根据本安全策略的说明进行处理，并且通知 BooAdmin 的开发者。

[![BooAdmin](https://img.shields.io/badge/BooAdmin-v1.1.18-blue?style=for-the-badge)](https://github.com/little-gt/THEME-BooAdmin/releases)
[![License](https://img.shields.io/badge/License-GPLv3-blue?style=for-the-badge)](LICENSE)

---

## 📌 支持版本

| 版本 | 状态 | 说明 |
| :--- | :---: | :--- |
| **v1.1.18** | ✅ 支持 | 当前主分支，持续接收安全更新 |
| 低于 **v1.1.17** | ❌ EOL | 资源已失效，停止维护，请升级至 v1.1.18 版本 |

---

## 📮 报告漏洞

> **请勿在 GitHub Issues 或公开场合披露漏洞**，请通过邮件披露，避免漏洞在被及时修复前被滥用，BooAdmin 感谢您的理解和支持。

**联系邮箱**：📬 [coolerxde@gt.ac.cn](mailto:coolerxde@gt.ac.cn)

**邮件标题格式**：`[Security] BooAdmin - 漏洞简述 - 版本 1.1.x`

**建议包含**：
- 影响版本与漏洞类型（XSS/CSRF/SQL注入等）
- 危害评估与复现步骤
- PoC 代码与环境信息（PHP/Typecho版本）

<details>
<summary><b>点击展开：邮件示例</b></summary>

**标题**: `[Security] BooAdmin - 存储型 XSS 漏洞 - 版本 1.1.3`

**正文**:
```
影响版本: v1.1.3
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

## 🛡️ 安全建议

- 始终使用最新版本
- 启用 HTTPS，使用强密码
- 定期备份数据库与文件
- 限制 `/admin` 目录访问
- 仅安装可信来源插件

---

## ⚖️ 免责声明

本项目按 GNU GENERAL PUBLIC LICENSE V3 开源协议发布，使用者需自行承担风险，项目维护者不对任何损失承担法律责任。