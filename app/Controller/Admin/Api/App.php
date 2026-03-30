<?php
declare(strict_types=1);

namespace App\Controller\Admin\Api;

use App\Interceptor\ManageSession;
use App\Interceptor\Waf;
use App\Model\ManageLog;
use App\Util\Opcache;
use Kernel\Annotation\Inject;
use Kernel\Annotation\Interceptor;
use Kernel\Exception\JSONException;

#[Interceptor([Waf::class, ManageSession::class], Interceptor::TYPE_API)]
class App extends Manage
{
    private const MARKET_DISABLED_MSG = "应用市场已禁用，当前仅支持本地插件。";
    private const REMOTE_UPDATE_DISABLED_MSG = "远程更新已禁用，如需开启请在 config/app.php 设置 remote_update_enabled=1。";

    #[Inject]
    private \App\Service\App $app;

    /**
     * 统一拦截应用市场能力，只保留本地插件方案
     * @throws JSONException
     */
    private function marketDisabled(): void
    {
        throw new JSONException(self::MARKET_DISABLED_MSG);
    }

    private function remoteUpdateEnabled(): bool
    {
        $app = (array)config("app");
        return (string)($app["remote_update_enabled"] ?? "0") === "1";
    }

    /**
     * @throws JSONException
     */
    private function ensureRemoteUpdateEnabled(): void
    {
        if (!$this->remoteUpdateEnabled()) {
            throw new JSONException(self::REMOTE_UPDATE_DISABLED_MSG);
        }
    }

    /**
     * @return array
     */
    public function versions(): array
    {
        if (!$this->remoteUpdateEnabled()) {
            $local = config("app")['version'];
            return $this->json(200, "ok", [[
                "version" => $local,
                "content" => "远程更新已禁用",
                "update_url" => "",
                "update_date" => date("Y-m-d"),
                "beta" => 0
            ]]);
        }
        return $this->json(200, "ok", $this->app->getVersions());
    }

    /**
     * @return array
     */
    public function latest(): array
    {
        $local = config("app")['version'];
        if (!$this->remoteUpdateEnabled()) {
            return $this->json(200, "ok", [
                "local" => $local,
                "latest" => true,
                "version" => $local,
                "remote_update_enabled" => 0
            ]);
        }
        $versions = $this->app->getVersions();
        $latestVersion = $versions[0]['version'];
        $latest = $latestVersion == $local;
        return $this->json(200, 'ok', [
            "local" => $local,
            "latest" => $latest,
            "version" => $latestVersion,
            "remote_update_enabled" => 1
        ]);
    }

    /**
     * @return array
     */
    public function update(): array
    {
        $this->ensureRemoteUpdateEnabled();
        $this->app->update();
        return $this->json(200, "升级完成");
    }

    /**
     * @return array
     */
    public function ad(): array
    {
        return $this->json(200, "ok", $this->app->ad());
    }


    /**
     * @throws JSONException
     */
    public function init(): array
    {
        $this->marketDisabled();
        $config = (array)config("store");
        if (!$config['app_key'] || !$config["app_id"]) {
            throw new JSONException("未登录");
        }
        return $this->json(200, "ok");
    }

    /**
     * @return array
     */
    public function captcha(): array
    {
        $this->marketDisabled();
        $type = (string)$_GET['type'];
        $captcha = $this->app->captcha($type);
        return $this->json(200, "ok", $captcha);
    }

    /**
     * @throws JSONException
     */
    public function register(): array
    {
        $this->marketDisabled();
        if (!$_POST['username'] || !$_POST['password'] || !$_POST['captcha'] || !$_POST['cookie']) {
            throw new JSONException("所有选项都不能为空");
        }
        $register = $this->app->register((string)$_POST['username'], (string)$_POST['password'], (string)$_POST['captcha'], (array)$_POST['cookie']);
        setConfig([
            "app_id" => $register["id"],
            "app_key" => $register["key"],
        ], BASE_PATH . "/config/store.php");
        Opcache::invalidate(BASE_PATH . "/config/store.php");
        return $this->json(200, "success");
    }

    /**
     * @throws JSONException
     */
    public function login(): array
    {
        $this->marketDisabled();
        if (!$_POST['username'] || !$_POST['password']) {
            throw new JSONException("所有选项都不能为空");
        }
        $login = $this->app->login($_POST['username'], $_POST['password']);
        setConfig([
            "app_id" => $login["id"],
            "app_key" => $login["key"],
        ], BASE_PATH . "/config/store.php");
        Opcache::invalidate(BASE_PATH . "/config/store.php");
        return $this->json(200, "success");
    }

    /**
     * @return array
     */
    public function plugins(): array
    {
        $this->marketDisabled();
        $owner = -1;
        if (isset($_POST['equal-owner'])) {
            $owner = (int)$_POST['equal-owner'];
        }
        $keywords = (string)$_POST['keywords'];

        $data = [
            "owner" => $owner,
            "page" => (int)$_POST['page'],
            "limit" => (int)$_POST['limit'],
            "group" => (int)$_POST['group']
        ];

        if ($keywords) {
            $data['keywords'] = urldecode($keywords);
        }

        $plugins = $this->app->plugins($data);

        //判断自己是否安装
        $fileInit = false;
        foreach ($plugins['rows'] as $index => $plugin) {
            if ($plugin['type'] == 0) {
                $installPath = BASE_PATH . "/app/Plugin/{$plugin['plugin_key']}";
                $fileInit = file_exists($installPath . "/Config/Info.php");
                if (is_dir($installPath) && $fileInit) {
                    $config = require($installPath . "/Config/Info.php");
                    $plugins['rows'][$index]['local_version'] = $config[\App\Consts\Plugin::VERSION];
                }
            } else if ($plugin['type'] == 1) {
                $installPath = BASE_PATH . "/app/Pay/{$plugin['plugin_key']}";
                $fileInit = file_exists($installPath . "/Config/Info.php");
                if (is_dir($installPath) && $fileInit) {
                    $config = require($installPath . "/Config/Info.php");
                    $plugins['rows'][$index]['local_version'] = $config["version"];
                }
            } elseif ($plugin['type'] == 2) {
                $installPath = BASE_PATH . "/app/View/User/Theme/{$plugin['plugin_key']}";
                $fileInit = file_exists($installPath . "/Config.php");
                if (is_dir($installPath) && $fileInit) {
                    $config = require($installPath . "/Config.php");
                    $namespace = "App\\View\\User\\Theme\\{$plugin['plugin_key']}\\Config";
                    $plugins['rows'][$index]['local_version'] = $namespace::INFO["VERSION"];
                }
            } else {
                continue;
            }
            if (is_dir($installPath) && $fileInit) {
                $plugins['rows'][$index]['install'] = 1;
            } else {
                $plugins['rows'][$index]['install'] = 0;
            }

            $plugins['rows'][$index]['icon'] = \App\Service\App::APP_URL . "/{$plugins['rows'][$index]['icon']}";
        }

        $json = $this->json(data: [
            "list" => $plugins['rows'],
            "total" => $plugins['count']
        ]);

        $json['user'] = $plugins['user'];
        $json['purchase'] = $plugins['purchase'];
        return $json;
    }

    /**
     * @return array
     * @throws JSONException
     */
    public function getUpdates(): array
    {
        $this->marketDisabled();
        $file = BASE_PATH . "/runtime/plugin/store.cache";

        $filectime = filectime($file);
        if ($filectime + 120 > time()) {
            throw new JSONException("CACHE HIT");
        }

        $plugins = $this->app->plugins([
            "type" => -1,
            "page" => 1,
            "limit" => 1000,
            "group" => 0,
        ]);

        //appStroe缓存
        $appStore = (array)json_decode((string)file_get_contents($file), true);

        foreach ($plugins['rows'] as $plugin) {
            // $info = Helper::isInstall($plugin['plugin_key'], (int)$plugin['type']);

            /*     if (!$info) {
                     continue;
                 }*/
            $appStore[$plugin['plugin_key']] = [
                "icon" => $plugin['icon'],
                "name" => $plugin['plugin_name'],
                "version" => $plugin['version'],
                "update_content" => $plugin['update_content'],
                "id" => $plugin['id'],
                "type" => $plugin['type']
            ];
        }

        file_put_contents($file, json_encode($appStore));
        return $this->json(200, "ok", $appStore);
    }

    /**
     * @return array
     */
    public function delUpdates(): array
    {
        $this->marketDisabled();
        $file = BASE_PATH . "/runtime/plugin/store.cache";
        unlink($file);
        return $this->json(200, "ok");
    }

    /**
     * @return array
     */
    public function purchase(): array
    {
        $this->marketDisabled();
        $purchase = $this->app->purchase((int)$_POST['type'], (int)$_POST['plugin_id'], (int)$_POST['payType']);
        return $this->json(200, "下单成功", $purchase);
    }

    /**
     * @return array
     */
    public function install(): array
    {
        $this->marketDisabled();
        $this->app->installPlugin((string)$_POST['plugin_key'], (int)$_POST['type'], (int)$_POST['plugin_id']);
        ManageLog::log($this->getManage(), "安装了应用({$_POST['plugin_key']})");
        return $this->json(200, "安装完成");
    }

    /**
     * @return array
     */
    public function upgrade(): array
    {
        $this->marketDisabled();
        $this->app->updatePlugin((string)$_POST['plugin_key'], (int)$_POST['type'], (int)$_POST['plugin_id']);
        ManageLog::log($this->getManage(), "更新了应用({$_POST['plugin_key']})");
        return $this->json(200, "更新完成");
    }

    /**
     * @return array
     */
    public function uninstall(): array
    {
        $this->marketDisabled();
        //卸载插件
        $pluginKey = (string)$_POST['plugin_key'];
        $type = (int)$_POST['type'];

        if ($type == 0) {
            _plugin_stop($pluginKey);
        }

        $this->app->uninstallPlugin($pluginKey, $type);

        ManageLog::log($this->getManage(), "卸载了应用({$pluginKey})");
        return $this->json(200, "卸载完成");
    }

    /**
     * 开发者插件
     * @return array
     */
    public function developerPlugins(): array
    {
        $this->marketDisabled();
        $plugins = $this->app->developerPlugins([
            "page" => (int)$_POST['page'],
            "limit" => (int)$_POST['limit']
        ]);

        foreach ($plugins['rows'] as &$plugin) {
            $plugin['icon'] = \App\Service\App::APP_URL . "/{$plugin['icon']}";
        }

        $json = $this->json(data: [
            "list" => $plugins['rows'],
            "total" => $plugins['count']
        ]);
        $json['user'] = $plugins['user'];
        return $json;
    }


    /**
     * 创建插件
     * @return array
     * @throws JSONException
     */
    public function developerCreatePlugin(): array
    {
        $this->marketDisabled();
        $file = $_POST['icon'];
        if (!file_exists(BASE_PATH . $file)) {
            throw new JSONException("请上传图标");
        }
        $iconBody = file_get_contents(BASE_PATH . $file);
        $_POST['icon'] = $iconBody;
        return $this->json(200, "创建成功", $this->app->developerCreatePlugin($_POST));
    }

    /**
     * @throws JSONException
     */
    public function developerCreateKit(): array
    {
        $this->marketDisabled();
        $file = $_POST['resource'];
        if (!file_exists(BASE_PATH . $file)) {
            throw new JSONException("请重新上传插件包");
        }
        //上传安装包
        $upload = $this->app->upload([
            [
                'name' => 'file',
                'contents' => fopen(BASE_PATH . $file, 'r'),
                'filename' => 'file.zip'
            ]
        ]);
        //删除本地安装包
        unlink(BASE_PATH . $file);
        //需要审核的安装包临时存放地址
        $_POST['resource'] = $upload['path'];
        return $this->json(200, "提交成功", $this->app->developerCreateKit($_POST));
    }

    /**
     * @return array
     */
    public function developerDeletePlugin(): array
    {
        $this->marketDisabled();
        return $this->json(200, "删除成功", $this->app->developerDeletePlugin($_POST));
    }

    /**
     * @return array
     * @throws JSONException
     */
    public function developerUpdatePlugin(): array
    {
        $this->marketDisabled();
        $file = $_POST['audit_resource'];
        if (!file_exists(BASE_PATH . $file)) {
            throw new JSONException("请重新上传插件包");
        }
        //上传更新包
        $upload = $this->app->upload([
            [
                'name' => 'file',
                'contents' => fopen(BASE_PATH . $file, 'r'),
                'filename' => 'file.zip'
            ]
        ]);
        //删除本地更新包
        unlink(BASE_PATH . $file);
        //需要审核的安装包临时存放地址
        $_POST['audit_resource'] = $upload['path'];
        return $this->json(200, "提交成功", $this->app->developerUpdatePlugin($_POST));
    }

    /**
     * @return array
     */
    public function developerPluginPriceSet(): array
    {
        $this->marketDisabled();
        return $this->json(200, "新的定价已生效", $this->app->developerPluginPriceSet($_POST));
    }


    /**
     * @return array
     */
    public function purchaseRecords(): array
    {
        $this->marketDisabled();
        return $this->json(data: ["list" => $this->app->purchaseRecords((int)$_GET['plugin_id'])]);
    }

    /**
     * @return array
     */
    public function unbind(): array
    {
        $this->marketDisabled();
        $this->app->unbind((int)$_POST['auth_id']);
        return $this->json(200, "绑定授权成功");
    }

    /**
     * @throws JSONException
     */
    public function setServer(): array
    {
        $this->marketDisabled();
        $server = (int)$_POST['server'];
        $config = config("store");
        $config['server'] = $server;
        $path = BASE_PATH . "/config/store.php";
        setConfig($config, $path);
        Opcache::invalidate($path);
        return $this->json(200, "线路切换成功");
    }

    /**
     * @return array
     */
    public function levels(): array
    {
        $this->marketDisabled();
        return $this->json(data: ["list" => $this->app->levels()]);
    }

    /**
     * @return array
     */
    public function bindLevel(): array
    {
        $this->marketDisabled();
        $this->app->bindLevel((int)$_POST['auth_id']);
        return $this->json(200, "绑定授权成功");
    }

    /**
     * @return array
     */
    public function service(): array
    {
        $this->marketDisabled();
        return $this->json(data: $this->app->service());
    }


    /**
     * @return array
     */
    public function editPassword(): array
    {
        $this->marketDisabled();
        $this->app->editPassword($_POST);
        return $this->json();
    }
}
