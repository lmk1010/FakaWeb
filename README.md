# FakaWeb

私有化部署的发卡系统项目（基于 acg-faka 进行业务定制）。

## 当前状态
- 生产域名：`https://jiminaishop.com`
- 运行方式：Docker Compose
- 支付：本地 `Epay` 插件（非应用市场安装）
- 远程更新：默认禁用

## 生产改动记录（2026-03-30）
- 已切换为本地支付插件模式，`Epay` 使用本地实现。
- 已屏蔽应用市场/开发者中心入口与接口，避免远程插件覆盖本地改动。
- 已增加远程更新开关（默认关闭）：`config/app.php` -> `remote_update_enabled`。

## 远程更新开关
- 关闭（默认）：`'remote_update_enabled' => '0'`
- 开启：改为 `1` 后重建 `web` 容器

```bash
cd /opt/acg-faka
docker compose up -d --build web
```

## Epay 签名规则（已按此实现）
1. 参数按 ASCII 升序排序。
2. `sign`、`sign_type`、空值不参与签名。
3. 拼接 `a=b&c=d`，值不做 URL 编码。
4. `sign = md5(拼接串 + KEY)`，结果小写。

## 本地开发
```bash
docker compose up -d --build
```

## 注意
- 本仓库用于当前业务定制版本，生产部署前请先在测试环境验证。
