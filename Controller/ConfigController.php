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
namespace Plugin\MaintenanceManager\Controller;

use Eccube\Application;
use Eccube\Common\Constant;
use Plugin\MaintenanceManager\Entity\Maintenance;
use Symfony\Component\HttpFoundation\Request;

class ConfigController
{
    /**
     * 設定画面
     *
     * @param Application $app
     * @param Request $request
     */
    public function index(Application $app, Request $request)
    {
        $service = $app['plg.service.maintenance_manager'];

        $Maintenance = Maintenance::newInstance();
        if ($service->isEnable()) {
            $Maintenance->setStatus(Constant::ENABLED);
        }
        $Maintenance->setContent($service->getReadTemplateFile());

        $form = $app['form.factory']
            ->createBuilder('admin_maintenance_manager_form', $Maintenance)
            ->getForm();

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $service->writeMaintenaceFile($Maintenance->getContent());

                if ($Maintenance->getStatus() == Constant::ENABLED) {
                    try {
                        $service->enable($app);
                        $app->addSuccess('plg.maintenance_manager.enable.success', 'admin');
                    } catch (\Exception $e) {
                        $app->addError('plg.maintenance_manager.enable.error', 'admin');
                        return $app->render('MaintenanceManager/Resource/template/admin/index.twig', array(
                            'form' => $form->createView(),
                        ));
                    }
                } else {
                    try {
                        $service->disable($app);
                        $app->addSuccess('plg.maintenance_manager.disable.success', 'admin');
                    } catch (\Exception $e) {
                        $app->addError('plg.maintenance_manager.disable.error', 'admin');
                        return $app->render('MaintenanceManager/Resource/template/admin/index.twig', array(
                            'form' => $form->createView(),
                        ));
                    }
                }

                return $app->redirect($app->url('admin_maintenance_manager'));
            }
        }

        return $app->render('MaintenanceManager/Resource/template/admin/index.twig', array(
            'form' => $form->createView(),
        ));
    }
}