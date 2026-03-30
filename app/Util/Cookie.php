<?php
declare(strict_types=1);

namespace App\Util;

class Cookie
{
    /**
     * 判断当前请求是否为 HTTPS（含反向代理场景）。
     */
    public static function isSecureRequest(): bool
    {
        $https = strtolower((string)($_SERVER['HTTPS'] ?? ''));
        if ($https === 'on' || $https === '1') {
            return true;
        }

        $forwardedProto = strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''));
        if ($forwardedProto === 'https') {
            return true;
        }

        $cfVisitor = (string)($_SERVER['HTTP_CF_VISITOR'] ?? '');
        if ($cfVisitor !== '' && str_contains($cfVisitor, '"https"')) {
            return true;
        }

        return (int)($_SERVER['SERVER_PORT'] ?? 80) === 443;
    }

    /**
     * 统一写入安全 Cookie。
     */
    public static function set(
        string $name,
        string $value,
        int $expire,
        string $path = '/',
        string $sameSite = 'Lax',
        bool $httpOnly = true
    ): void {
        setcookie($name, $value, [
            'expires' => $expire,
            'path' => $path,
            'secure' => self::isSecureRequest(),
            'httponly' => $httpOnly,
            'samesite' => $sameSite
        ]);
    }

    /**
     * 统一删除 Cookie。
     */
    public static function clear(string $name, string $path = '/', string $sameSite = 'Lax', bool $httpOnly = true): void
    {
        self::set($name, '', time() - 3600, $path, $sameSite, $httpOnly);
    }
}
