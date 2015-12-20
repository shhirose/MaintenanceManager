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
namespace Plugin\MaintenanceManager\Form\Type;

use Eccube\Application;
use Eccube\Common\Constant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class MaintenanceType extends AbstractType
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $app = $this->app;

        $builder
            ->add('status', 'choice', array(
                'label' => $app['translator']->trans('plg.maintenance_manager.label.status'),
                'required' => true,
                'choices' => array(
                    Constant::DISABLED => '無効',
                    Constant::ENABLED => '有効',
                ),
                'expanded' => true,
                'multiple' => false,
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            ))
            ->add('content', 'textarea', array(
                'label' => $app['translator']->trans('plg.maintenance_manager.label.content'),
                'required' => true,
                'constraints' => array(
                    new Assert\NotBlank(),
                ),
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_maintenance_manager_form';
    }
}