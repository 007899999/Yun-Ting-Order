<?php

return array (
  'autoload' => false,
  'hooks' => 
  array (
    'action_begin' => 
    array (
      0 => 'geetest',
    ),
    'config_init' => 
    array (
      0 => 'geetest',
    ),
    'admin_login_init' => 
    array (
      0 => 'loginbg',
    ),
    'prismhook' => 
    array (
      0 => 'prism',
    ),
  ),
  'route' => 
  array (
    '/example$' => 'example/index/index',
    '/example/d/[:name]' => 'example/demo/index',
    '/example/d1/[:name]' => 'example/demo/demo1',
    '/example/d2/[:name]' => 'example/demo/demo2',
  ),
);