<div align="center">

# 🔌 MN宝塔 API 开发文档

**MNBT虚拟主机管理系统 RESTful API**

[![API Version](https://img.shields.io/badge/API-v1.7-blue.svg)](http://mf.mengnai.top/)
[![Request Method](https://img.shields.io/badge/Method-POST-green.svg)](#)
[![Response Format](https://img.shields.io/badge/Format-JSON-orange.svg)](#)

*完整的API接口文档，支持第三方系统集成和自动化运维*

</div>

---

## 📖 系统简介

**梦奈宝塔主机系统**（MNBT）是一种将宝塔面板转换为虚拟主机并提供操作面板的系统。采用 **PHP + MySQL** 编写后端，前端使用光年开源框架，以其极快的响应速度和独特的功能深受用户喜爱。

- 🌐 **官方网站**: http://mf.mengnai.top/
- 📦 **当前版本**: v1.7
- 📥 **下载文档**: [Word版本开发文档](http://mf.mengnai.top/)

---

## 🎯 API 基础说明

### 请求方式

所有API请求均采用 **POST** 方式提交（除一键登录外，支持GET/POST）

### 响应格式

API返回标准JSON格式数据：

```json
{
  "code": 200,
  "msg": "主机开通成功！"
}
```

### 状态码说明

| 状态码 | 说明 | 备注 |
|--------|------|------|
| `200` | ✅ 成功 | 请求成功执行 |
| `100` | ❌ 失败 | 请求执行失败，msg包含失败原因 |
| `300` | ⚠️ 版本不匹配 | 插件版本与MNBT版本不兼容 |

---

## 🔐 认证参数

### 必带参数（Required Parameters）

除 **一键登录** 和 **注销登录** 外，所有API请求都必须包含以下参数：

| 参数名 | 示例值 | 说明 |
|--------|--------|------|
| `mn_bh` | `fw12201` | 宝塔编号（在宝塔列表中查看） |
| `mn_key` | `suifghsyufgasyud` | API密钥（在系统设置→API设置中查看/修改） |
| `mn_keye` | `bfasbfuyhafbeaas` | 宝塔调用密钥（在宝塔列表中查看） |
| `mn_vs` | `16` | 插件支持的MNBT版本（16代表v1.6） |

> ⚠️ **安全提示**: 一键登录和注销登录功能不能携带必带参数，因为这些参数会暴露给用户！

---

## 📡 API 接口列表

### 🔍 1. 测试连接

**接口地址**
```
POST http://your-mnbt-domain/api/api.php?gn=cfif
```

**请求参数**

| 参数名 | 类型 | 必填 | 示例值 | 说明 |
|--------|------|------|--------|------|
| `username` | String | ✅ | `Link` | 随意填写（用于测试） |

**响应示例**
```json
{
  "code": 200,
  "msg": "连接成功"
}
```

---

### 🚀 2. 开通主机

**接口地址**
```
POST http://your-mnbt-domain/api/api.php?gn=kt
```

**请求参数**

| 参数名 | 类型 | 必填 | 示例值 | 说明 |
|--------|------|------|--------|------|
| `username` | String | ✅ | `Us1000` | 登录账号（也是FTP账号） |
| `password` | String | ✅ | `123456abc` | 登录密码（也是FTP密码） |
| `webdx` | Integer | ✅ | `100` | 网页空间最大值（单位：MB） |
| `sqldx` | Integer | ✅ | `50` | 数据库空间最大值（单位：MB） |
| `sizemax` | Integer | ✅ | `60` | 最多可用流量（单位：GB/月） |
| `type` | Integer | ✅ | `1` | 产品类型（1=CDN，2=主机） |
| `ymbds` | Integer | ✅ | `5` | 域名最多绑定数 |
| `dqtime` | String | ✅ | `2025-5-20` | 到期时间（填`0`则永久） |

**响应示例**
```json
{
  "code": 200,
  "msg": "主机开通成功！"
}
```

---

### 🔄 3. 主机续费

**接口地址**
```
POST http://your-mnbt-domain/api/api.php?gn=xf
```

**请求参数**

| 参数名 | 类型 | 必填 | 示例值 | 说明 |
|--------|------|------|--------|------|
| `username` | String | ✅ | `Us1000` | 要续费的用户账号 |
| `setdate` | String | ✅ | `2026-5-20` | 续费后的到期时间 |

**响应示例**
```json
{
  "code": 200,
  "msg": "续费成功！"
}
```

---

### 🗑️ 4. 删除主机

**接口地址**
```
POST http://your-mnbt-domain/api/api.php?gn=tz
```

**请求参数**

| 参数名 | 类型 | 必填 | 示例值 | 说明 |
|--------|------|------|--------|------|
| `username` | String | ✅ | `Us1000` | 要删除的主机账号 |

**响应示例**
```json
{
  "code": 200,
  "msg": "主机已删除！"
}
```

---

### ⏸️ 5. 暂停主机

**接口地址**
```
POST http://your-mnbt-domain/api/api.php?gn=zt
```

**请求参数**

| 参数名 | 类型 | 必填 | 示例值 | 说明 |
|--------|------|------|--------|------|
| `username` | String | ✅ | `Us1000` | 要暂停的主机账号 |

**响应示例**
```json
{
  "code": 200,
  "msg": "主机已暂停！"
}
```

---

### ▶️ 6. 解除暂停

**接口地址**
```
POST http://your-mnbt-domain/api/api.php?gn=jc
```

**请求参数**

| 参数名 | 类型 | 必填 | 示例值 | 说明 |
|--------|------|------|--------|------|
| `username` | String | ✅ | `Us1000` | 要解除暂停的主机账号 |

**响应示例**
```json
{
  "code": 200,
  "msg": "已解除暂停！"
}
```

---

### 🔑 7. 重置密码

**接口地址**
```
POST http://your-mnbt-domain/api/api.php?gn=czmm
```

**请求参数**

| 参数名 | 类型 | 必填 | 示例值 | 说明 |
|--------|------|------|--------|------|
| `username` | String | ✅ | `Us1000` | 要重置密码的主机账号 |
| `password` | String | ✅ | `123456abc` | 重置后的新密码 |

**响应示例**
```json
{
  "code": 200,
  "msg": "密码已重置！"
}
```

---

### 🔐 8. 一键登录

**接口地址**（支持GET/POST，直接跳转）
```
GET/POST http://your-mnbt-domain/user/idcdl.php?gn=logine
```

**⚠️ 重要提示**: 此功能 **不能携带必带参数**（`mn_bh`、`mn_key`等），因为会暴露给用户！

**请求参数**

| 参数名 | 类型 | 必填 | 示例值 | 说明 |
|--------|------|------|--------|------|
| `username` | String | ✅ | `Us1000` | 登录账号 |
| `password` | String | ✅ | `123456abc` | 登录密码 |

**功能说明**

访问此接口后会直接跳转到用户控制面板，无需手动输入账号密码。

---

### 🚪 9. 注销登录

**接口地址**
```
GET http://your-mnbt-domain/user/idcdl.php?gn=xz
```

**功能说明**

- 不需要任何参数
- 注销MNBT的上一次登录
- 自动跳转到登录页面

---

## 💡 使用示例

### PHP示例

```php
<?php
// API配置
$api_url = 'http://your-mnbt-domain/api/api.php?gn=kt';
$params = [
    'mn_bh' => 'fw12201',
    'mn_key' => 'your_api_key',
    'mn_keye' => 'your_bt_key',
    'mn_vs' => '16',
    'username' => 'testuser',
    'password' => 'test123456',
    'webdx' => 100,
    'sqldx' => 50,
    'sizemax' => 60,
    'type' => 2,
    'ymbds' => 5,
    'dqtime' => '2025-12-31'
];

// 发送POST请求
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// 解析响应
$result = json_decode($response, true);
if ($result['code'] == 200) {
    echo "成功：" . $result['msg'];
} else {
    echo "失败：" . $result['msg'];
}
?>
```

### JavaScript示例

```javascript
// 使用 Fetch API
const apiUrl = 'http://your-mnbt-domain/api/api.php?gn=kt';
const params = new URLSearchParams({
    mn_bh: 'fw12201',
    mn_key: 'your_api_key',
    mn_keye: 'your_bt_key',
    mn_vs: '16',
    username: 'testuser',
    password: 'test123456',
    webdx: 100,
    sqldx: 50,
    sizemax: 60,
    type: 2,
    ymbds: 5,
    dqtime: '2025-12-31'
});

fetch(apiUrl, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: params
})
.then(response => response.json())
.then(data => {
    if (data.code === 200) {
        console.log('成功：', data.msg);
    } else {
        console.log('失败：', data.msg);
    }
})
.catch(error => console.error('错误：', error));
```

### cURL命令行示例

```bash
curl -X POST 'http://your-mnbt-domain/api/api.php?gn=kt' \
  -d 'mn_bh=fw12201' \
  -d 'mn_key=your_api_key' \
  -d 'mn_keye=your_bt_key' \
  -d 'mn_vs=16' \
  -d 'username=testuser' \
  -d 'password=test123456' \
  -d 'webdx=100' \
  -d 'sqldx=50' \
  -d 'sizemax=60' \
  -d 'type=2' \
  -d 'ymbds=5' \
  -d 'dqtime=2025-12-31'
```

---

## 🔒 安全建议

1. **API密钥保护**
   - 妥善保管 `mn_key` 和 `mn_keye`
   - 定期更换API密钥
   - 不要在客户端代码中硬编码密钥

2. **IP白名单**
   - 在后台设置API访问IP白名单
   - 限制只有授权IP可以调用API

3. **HTTPS传输**
   - 生产环境强烈建议使用HTTPS
   - 防止参数在传输过程中被窃取

4. **参数验证**
   - 服务端会验证所有参数的合法性
   - 请确保参数格式正确，避免不必要的错误

5. **版本控制**
   - `mn_vs` 参数用于版本匹配检查
   - 确保插件版本与MNBT系统版本兼容

---

## ❓ 常见问题

### Q1: 为什么一键登录不能携带必带参数？

**A**: 一键登录会在URL中暴露参数给最终用户，如果包含API密钥等敏感信息会造成安全风险。因此该接口设计为仅需用户名和密码。

### Q2: 如何获取宝塔编号和调用密钥？

**A**: 登录MNBT管理后台，进入"宝塔列表"页面，可以查看所有已添加的宝塔面板的编号（`mn_bh`）和调用密钥（`mn_keye`）。

### Q3: API密钥在哪里设置？

**A**: 登录MNBT管理后台，进入"系统设置" → "API设置"，可以查看和修改全局API密钥（`mn_key`）。

### Q4: 产品类型1和2有什么区别？

**A**:
- `type=1`: CDN产品，仅提供流量转发功能
- `type=2`: 主机产品，提供完整的虚拟主机功能（文件、数据库、域名等）

### Q5: 到期时间可以设置为永久吗？

**A**: 可以，将 `dqtime` 参数设置为 `0` 即表示永久有效，不会过期。

---

## 📞 技术支持

如有API使用问题或建议，欢迎联系：

- 📧 **官方网站**: http://mf.mengnai.top/
- 💬 **GitHub Issues**: [提交问题](https://github.com/miuzhaii/mnbt/issues)
- 📚 **完整文档**: 参见系统附带的Word版开发文档

---

<div align="center">

**© 2023-2025 梦奈科技 版权所有**

Made with ❤️ by MengNai Technology

[返回主页](README.md) • [安装指南](INSTALL.md)

</div>
