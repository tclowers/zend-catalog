<?php
namespace Catalog;
	return array (
		'controllers' => array(
			'invokables' => array(
				'Catalog\Controller\Product' => 'Catalog\Controller\ProductController',
				'Catalog\Controller\Customer' => 'Catalog\Controller\CustomerController',
			),
		),
		//routes
		'router' => array(
			'routes' => array(
				'product' => array(
					'type' => 'segment',
					'options' => array(
						'route' => '/product[/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id' => '[0-9]+',
						),
						'defaults' => array(
							'controller' => 'Catalog\Controller\Product',
							'action' => 'index',
						),
					),
				),
				'customer' => array(
					'type' => 'segment',
					'options' => array(
						'route' => '/customer[/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							'id' => '[0-9]+',
						),
						'defaults' => array(
							'controller' => 'Catalog\Controller\Customer',
							'action' => 'index',
						),
					),
				),
			),
		),

		'view_manager' => array(
			'template_path_stack' => array(
				'product' => __DIR__ . '/../view',
			),
		),

		'view_manager' => array(
			'template_path_stack' => array(
				'customer' => __DIR__ . '/../view',
			),
		),

		// Doctrine config
		'doctrine' => array(
		  'driver' => array(
		      __NAMESPACE__ . '_driver' => array(
		          'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
		          'cache' => 'array',
		          'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
		      ),
		      'orm_default' => array(
		          'drivers' => array(
		              __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
		          ),
		      ),
		  ),
		),
	);