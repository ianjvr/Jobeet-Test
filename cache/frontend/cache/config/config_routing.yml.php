<?php
// auto-generated by sfRoutingConfigHandler
// date: 2012/01/11 12:49:53
return array(
'change_language' => new sfRoute('/change_language', array (
  'module' => 'language',
  'action' => 'changeLanguage',
), array (
), array (
)),
'affiliate' => new sfPropelRouteCollection(array (
  'model' => 'JobeetAffiliate',
  'actions' => 
  array (
    0 => 'new',
    1 => 'create',
  ),
  'object_actions' => 
  array (
    'wait' => 'get',
  ),
  'prefix_path' => '/:sf_culture/affiliate',
  'name' => 'affiliate',
  'requirements' => 
  array (
    'sf_culture' => '(?:fr|en)',
  ),
)),
'category' => new sfPropelRoute('/:sf_culture/category/:slug.:sf_format', array (
  'module' => 'category',
  'action' => 'show',
  'sf_format' => 'html',
), array (
  'sf_format' => '(?:html|atom)',
  'sf_culture' => '(?:fr|en)',
), array (
  'model' => 'JobeetCategory',
  'type' => 'object',
  'method' => 'doSelectForSlug',
)),
'job_search' => new sfRoute('/:sf_culture/search', array (
  'module' => 'job',
  'action' => 'search',
), array (
), array (
)),
'job' => new sfPropelRouteCollection(array (
  'model' => 'JobeetJob',
  'column' => 'token',
  'object_actions' => 
  array (
    'publish' => 'put',
    'extend' => 'put',
  ),
  'prefix_path' => '/:sf_culture/job',
  'name' => 'job',
  'requirements' => 
  array (
    'token' => '\\w+',
    'sf_culture' => '(?:fr|en)',
  ),
)),
'job_show_user' => new sfPropelRoute('/:sf_culture/job/:company_slug/:location_slug/:id/:position_slug', array (
  'module' => 'job',
  'action' => 'show',
), array (
  'id' => '\\d+',
  'sf_method' => 'get',
  'sf_culture' => '(?:fr|en)',
), array (
  'model' => 'JobeetJob',
  'type' => 'object',
  'method_for_criteria' => 'doSelectActive',
)),
'about' => new sfRoute('/about', array (
  'module' => 'job',
  'action' => 'about',
), array (
), array (
)),
'api_jobs' => new sfPropelRoute('/api/:token/jobs.:sf_format', array (
  'module' => 'api',
  'action' => 'list',
), array (
  'sf_format' => '(?:xml|json|yaml)',
), array (
  'model' => 'JobeetJob',
  'type' => 'list',
  'method' => 'getForToken',
)),
'localized_homepage' => new sfRoute('/:sf_culture/', array (
  'module' => 'job',
  'action' => 'index',
), array (
  'sf_culture' => '(?:fr|en)',
), array (
)),
'homepage' => new sfRoute('/', array (
  'module' => 'job',
  'action' => 'index',
), array (
), array (
)),
'default_index' => new sfRoute('/:module', array (
  'action' => 'index',
), array (
), array (
)),
'default' => new sfRoute('/:module/:action/*', array (
), array (
), array (
)),
);
