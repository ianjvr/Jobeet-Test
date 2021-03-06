<?php
// test/unit/model/JobeetJobTest.php
include (dirname(__FILE__) . '/../../bootstrap/Propel.php');

$t = new lime_test(10, new lime_output_color());

$t->comment('->getActiveJobs()');
$jobs = JobeetJobPeer::getActiveJobs();
$t->ok(!empty($jobs), "->getActiveJobs returns active jobs");

$t->comment('->countActiveJobs()');
$jobs = JobeetJobPeer::countActiveJobs();
$t->ok($jobs > 0, "->countActiveJobs count is " . $jobs);

$t->comment('->getCompanySlug()');
$job = JobeetJobPeer::doSelectOne(new Criteria());
$t->is($job->getCompanySlug(), Jobeet::slugify($job->getCompany()), '->getCompanySlug() return the slug for the company');

$t->comment('->getPositionSlug()');
$job = JobeetJobPeer::doSelectOne(new Criteria());
$t->is($job->getPositionSlug(), Jobeet::slugify($job->getPositionSlug()), '->getPositionSlug() return the slug for the position');

$t->comment('->getLocationSlug()');
$job = JobeetJobPeer::doSelectOne(new Criteria());
$t->is($job->getLocationSlug(), Jobeet::slugify($job->getLocationSlug()), '->getLocationSlug() return the slug for the location');

$t->comment('->save()');
$job = create_job();
$job->save();
$expiresAt = date('Y-m-d', time() + 86400 * sfConfig::get('app_active_days'));
$t->is($job->getExpiresAt('Y-m-d'), $expiresAt, '->save() updates expires_at if not set');

$job = create_job(array('expires_at' => '2008-08-08'));
$job->save();
$t->is($job->getExpiresAt('Y-m-d'), '2008-08-08', '->save() does not update expires_at if set');

$t->comment('->getForLuceneQuery()');
$job = create_job(array(
      'position' => 'foobar',
      'is_activated' => true
));
$job->save();
$jobs = JobeetJobPeer::getForLuceneQuery('position:foobar');
$t->is(count($jobs), 0, '::getForLuceneQuery() does not return non activated jobs');

$job = create_job(array(
      'position' => 'foobar',
      'is_activated' => true
));

$job->save();
$jobs = JobeetJobPeer::getForLuceneQuery('position:foobar');
$t->is(count($jobs), 1, '::getForLuceneQuery() returns jobs matching the criteria');
$t->is($jobs[0]->getId(), $job->getId(), '::getForLuceneQuery() returns jobs matching the criteria');

$job->delete();
$jobs = JobeetJobPeer::getForLuceneQuery('position:foobar');
$t->is(count($jobs), 0, '::getForLuceneQuery() does not return deleted jobs');

$browser->setHttpHeader('X_REQUESTED_WITH', 'XMLHttpRequest');
$browser->
  info('5 - Live search')->
 
  get('/search?query=sens*')->
  with('response')->begin()->
    checkElement('table tr', 2)->
  end()
;





function create_job($defaults = array())
{
   static $category = null;

   if (is_null($category))
   {
      $category = JobeetCategoryPeer::doSelectOne(new Criteria());
   }

   $job = new JobeetJob();
   $job->fromArray(array_merge(array(
         'category_id' => $category->getId(),
         'company' => 'Sensio Labs',
         'position' => 'Senior Tester',
         'location' => 'Paris, France',
         'description' => 'Testing is fun',
         'how_to_apply' => 'Send e-Mail',
         'email' => 'job@example.com',
         'token' => rand(1111, 9999),
         'is_activated' => true,
   ), $defaults), BasePeer::TYPE_FIELDNAME);

   return $job;
}
