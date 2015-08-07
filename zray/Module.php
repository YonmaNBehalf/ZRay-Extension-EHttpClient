<?php

namespace ZRay\ZendHttp;

class Module extends \ZRay\ZRayModule {

	public function config() {
        return array(
            // The name defined in ZRayExtension
            'extension' => array(
                'name' => 'yiiehttpclient',
            ),
            // Prevent those default panels from being displayed
            'defaultPanels' => array(
                'RequestsYiiEHttp' => false,
            ),
            // configure all custom panels
            'panels' => array(
                'yiiehttpclientTable' => array(
                    'display'           => true,
                    'menuTitle'         => 'EHttp Requests',
                    'panelTitle'        => 'EHttp Requests',
                    'searchId'          => 'yiiehttp-table-search',
                    'pagerId'           => 'yiiehttp-table-pager',
                )
            )
        );
    }
	
	
	
}
