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
namespace Plugin\MaintenanceManager\Entity;

use Eccube\Common\Constant;

class Maintenance
{
    /**
     * @var integer
     */
    private $status;

    /**
     * @var string
     */
    private $content;

    public static function newInstance()
    {
        $self = new Maintenance();
        $self->setStatus(Constant::DISABLED);
        return $self;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }
}