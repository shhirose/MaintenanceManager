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
namespace Plugin\MaintenanceManager\ServiceProvider;

use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;

class MaintenanceManagerServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(BaseApplication $app)
    {
        $app['translator']->addResource('yaml', __DIR__ . '/../Resource/locale/message.ja.yml', 'ja');

        $app['config'] = $app->share($app->extend('config', function ($config) {
            foreach ($config['nav'] as $key => $menu1) {
                if ($menu1['id'] == 'setting') {
                    $children = $menu1['child'];
                    foreach ($children as $key2 => $menu2) {
                        if ($menu2['id'] == 'system') {
                            $config['nav'][$key]['child'][$key2]['has_child'] = 'true';
                            $config['nav'][$key]['child'][$key2]['child'][] = array(
                                'id' => 'admin_maintenance_manager',
                                'name' => 'メンテナンス管理',
                                'url' => 'admin_maintenance_manager',
                            );
                            break;
                        }
                    }
                }
            }

            return $config;
        }));

        $app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
            $types[] = new \Plugin\MaintenanceManager\Form\Type\MaintenanceType($app);

            return $types;
        }));

        $app['plg.service.maintenance_manager'] = $app->share(function () use ($app) {
            return new \Plugin\MaintenanceManager\Service\MaintenanceManagerService($app);
        });

        $url = '/' . $app["config"]["admin_route"] . '/MaintenanceManager';
        $app->match($url . '/config', '\Plugin\MaintenanceManager\Controller\ConfigController::index')->bind('admin_maintenance_manager');
//         $app->match($url . '/enable', '\Plugin\MaintenanceManager\Controller\ConfigController::enable')->bind('admin_maintenance_manager_enable');
//         $app->match($url . '/disable', '\Plugin\MaintenanceManager\Controller\ConfigController::diable')->bind('admin_maintenance_manager_disable');
    }

    /**
     * {@inheritdoc}
     */
    public function boot(BaseApplication $app)
    {
    }
}