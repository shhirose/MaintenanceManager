<?php
/**
 * MaintenanceManager
 *
 * Copyright (c) 2015- all right reserved.
 *
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 *
 * @author shhirose.eccube@gmail.com
 */
namespace Plugin\MaintenanceManager;

use Eccube\Plugin\AbstractPluginManager;

class PluginManager extends AbstractPluginManager
{
    /**
     * インストール時に処理したい内容を記載する。
     *
     * @param yaml $config
     * @param \Eccube\Application $app
     */
    public function install($config, $app)
    {
    }

    /**
     * アンインストール時に処理したい内容を記載する。
     *
     * @param yaml $config
     * @param \Eccube\Application $app
     */
    public function uninstall($config, $app)
    {
    }

    /**
     * プラグイン有効時に処理したい内容を記載する。
     *
     * @param yaml $config
     * @param \Eccube\Application $app
     */
    public function enable($config, $app)
    {
    }

    /**
     * プラグイン無効時に処理したい内容を記載する。
     *
     * @param yaml $config
     * @param \Eccube\Application $app
     */
    public function disable($config, $app)
    {
        $app['plg.service.maintenance_manager']->disable($app);
    }

    /**
     * アップデート時に処理したい内容を記載する。
     *
     * @param yaml $config
     * @param \Eccube\Application $app
     */
    public function update($config, $app)
    {
    }
}
