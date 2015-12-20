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
namespace Plugin\MaintenanceManager\Service;

use Symfony\Component\Filesystem\Filesystem;

class MaintenanceManagerService
{
    const DEFAULT_FILE = '.htaccess';
    const COPY_FILE = '.htaccess.mm_copy';
    const MAINTENANCE_HTML = 'maintenance.html';
    const HTACCESS_ALLOW_HOST = '#admin_allow_host#';

    /**
     *
     * @param \Eccube\Application $app
     */
    public function __construct(\Eccube\Application $app)
    {
        $this->app = $app;
    }


    public function getReadTemplateFile()
    {
        $readPaths = array(
            __DIR__ . '/../Resource/template/default',
        );
        foreach ($readPaths as $readPath) {
            $filePath = $readPath . '/maintenance.twig';
            $fs = new Filesystem();
            if ($fs->exists($filePath)) {
                return file_get_contents($filePath);
            }
        }
    }

    public function writeMaintenaceFile($content)
    {
        $dir = $this->getDocumentRootDir();

        $fs = new Filesystem();
        $fs->dumpFile(__DIR__ . '/../Resource/template/default/maintenance.twig', $content);

        $html = $this->app->render('MaintenanceManager/Resource/template/default/maintenance.twig');
        $fs->dumpFile($dir . 'maintenance.html', $html->getContent());
    }

    /**
     * メンテナンス状態を有効化します。
     *
     * @param Application $app
     */
    public function enable()
    {
        $dir = $this->getDocumentRootDir();
        if (!$this->isEnable()) {
            rename($dir . self::DEFAULT_FILE, $dir . self::COPY_FILE);

            $allow_host = '';
            $hosts = $this->app['config']['admin_allow_host'];
            foreach ($hosts as $host) {
                $allow_host .= "\n    RewriteCond %{REMOTE_ADDR} !=" . $host;
            }
            $htaccess = file_get_contents(__DIR__ . '/../Resource/template/default/.htaccess');
            $fs = new Filesystem();
            $fs->dumpFile($dir . self::DEFAULT_FILE, str_replace(self::HTACCESS_ALLOW_HOST, $allow_host, $htaccess));
        }
    }

    /**
     * メンテナンス状態を無効化します。
     *
     * @param Application $app
     */
    public function disable()
    {
        $dir = $this->getDocumentRootDir();
        if ($this->isEnable()) {
            unlink($dir . self::DEFAULT_FILE);
            rename($dir . self::COPY_FILE, self::DEFAULT_FILE);
        }
    }

    /**
     * メンテナンス状態かを返します。
     *
     * @return メンテナンス状態の場合 true
     */
    public function isEnable()
    {
        $fs = new Filesystem();
        return $fs->exists($this->getDocumentRootDir() . self::COPY_FILE);
    }

    private function getDocumentRootDir()
    {
        return $this->app['config']['root_dir'] . '/html/';
    }
}
