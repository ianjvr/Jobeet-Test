<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Service_Twitter
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/TestSuite.php';

/** Zend_Service_Twitter */
require_once 'Zend/Service/Twitter.php';

/** Zend_Http_Client */
require_once 'Zend/Http/Client.php';

/** Zend_Http_Client_Adapter_Test */
require_once 'Zend/Http/Client/Adapter/Test.php';

/**
 * @category   Zend
 * @package    Zend_Service_Twitter
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Service_TwitterTest extends PHPUnit_Framework_TestCase
{
    /**
     * Change these to your login credentials for testing
     */
    const TWITTER_USER = 'zftestuser';
    const TWITTER_PASS = 'zftestuser';

    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        require_once 'PHPUnit/TextUI/TestRunner.php';

        $suite  = new PHPUnit_Framework_TestSuite('Zend_Service_TwitterTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
    	Zend_Service_Abstract::getHttpClient()->setAdapter('Zend_Http_Client_Adapter_Socket');
        $this->twitter = new Zend_Service_Twitter(self::TWITTER_USER, self::TWITTER_PASS);

        /*$adapter = new Zend_Http_Client_Adapter_Test();
        $client = new Zend_Http_Client(null, array(
            'adapter' => $adapter
        ));
        $this->adapter = $adapter;
        Zend_Service_Twitter::setHttpClient($client);*/
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown()
    {
        // unset($this->adapter);
    }

    /**
     * @return void
     */
    public function testConstructorShouldSetUsernameAndPassword()
    {
        $this->assertEquals('zftestuser', $this->twitter->getUsername());
        $this->assertEquals('zftestuser', $this->twitter->getPassword());
    }

    /**
     * @return void
     */
    public function testUsernameAccessorsShouldAllowSettingAndRetrievingUsername()
    {
        $this->twitter->setUsername('foo');
        $this->assertEquals('foo', $this->twitter->getUsername());
    }

    /**
     * @return void
     */
    public function testPasswordAccessorsShouldAllowSettingAndRetrievingPassword()
    {
        $this->twitter->setPassword('foo');
        $this->assertEquals('foo', $this->twitter->getPassword());
    }

    /**
     * @return void
     */
    public function testOverloadingGetShouldReturnObjectInstanceWithValidMethodType()
    {
        try {
            $return = $this->twitter->status;
            $this->assertSame($this->twitter, $return);
        } catch (Exception $e) {
            $this->fail('Property overloading with a valid method type should not throw an exception');
        }
    }

    /**
     * @return void
     */
    public function testOverloadingGetShouldthrowExceptionWithInvalidMethodType()
    {
        try {
            $return = $this->twitter->foo;
            $this->fail('Property overloading with an invalid method type should throw an exception');
        } catch (Exception $e) {
        }
    }

    /**
     * @return void
     */
    public function testOverloadingGetShouldthrowExceptionWithInvalidFunction()
    {
        try {
            $return = $this->twitter->foo();
            $this->fail('Property overloading with an invalid function should throw an exception');
        } catch (Exception $e) {
        }
    }

    /**
     * @return void
     */
    public function testMethodProxyingDoesNotThrowExceptionsWithValidMethods()
    {
        try {
            $this->twitter->status->publicTimeline();
        } catch (Exception $e) {
            $this->fail('Method proxying should not throw an exception with valid methods; exception: ' . $e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testMethodProxyingThrowExceptionsWithInvalidMethods()
    {
        try {
            $this->twitter->status->foo();
            $this->fail('Method proxying should throw an exception with invalid methods');
        } catch (Exception $e) {
        }
    }

    /**
     * @return void
     */
    public function testVerifiedCredentials()
    {
        $response = $this->twitter->account->verifyCredentials();
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
    }

    /**
     * @return void
     */
    public function testPublicTimelineStatusReturnsResults()
    {
        $response = $this->twitter->status->publicTimeline();
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
        $this->assertTrue(isset($response->status));
    }

    /**
     * @return void
     */
    public function testUsersFeaturedStatusReturnsResults()
    {
        $response = $this->twitter->user->featured();
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);

        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
        $this->assertTrue(isset($response->status));
    }

    public function testRateLimitStatusReturnsResults()
    {
        /* @var $response Zend_Rest_Client_Result */
        $response = $this->twitter->account->rateLimitStatus();
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);

        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
    }

    public function testRateLimitStatusHasHitsLeft()
    {
        /* @var $response Zend_Rest_Client_Result */
        $response = $this->twitter->account->rateLimitStatus();
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);

        $remaining_hits = $response->toValue($response->{'remaining-hits'});

        $this->assertType('numeric', $remaining_hits);
        $this->assertGreaterThan(0, $remaining_hits);
    }

    /**
     * @return void
     */
    public function testAccountEndSession()
    {
        $response = $this->twitter->account->endSession();
        $this->assertTrue($response);
    }

    /**
     * @return void
     */
    public function testFriendshipCreate()
    {
        $response = $this->twitter->friendship->create('zftestuser1');
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
    }

    /**
     * @return void
     */
    public function testFriendshipExists()
    {
        /* @var $response Zend_Rest_Client_Result */
        $response = $this->twitter->friendship->exists('zftestuser1');
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);

        $httpClient     = Zend_Service_Twitter::getHttpClient();
        $httpRequest    = $httpClient->getLastRequest();
        $httpResponse   = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
    }

    /**
     * @return void
     */
    public function testFriendsTimelineWithInvalidParamReturnsResults()
    {
        /* @var $response Zend_Rest_Client_Result */
        $response = $this->twitter->status->friendsTimeline( array('foo' => 'bar') );
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
        $this->assertTrue(isset($response->status));
    }

    /**
     * @return void
     */
    public function testFriendsTimelineStatusWithFriendSpecifiedReturnsResults()
    {
        /* @var $response Zend_Rest_Client_Result */
        $response = $this->twitter->status->friendsTimeline( array('id' => 'zftestuser1') );
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
        $this->assertTrue(isset($response->status));
    }

    /**
     * @return void
     */
    public function testUserTimelineStatusSinceTwoDaysAgoDateAsStringReturnsResults()
    {
        $this->insertTestTwitterData();
        $response = $this->twitter->status->userTimeline( array('id' => 'zftestuser1', 'since' => '-2 days') );
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());

        $this->assertTrue(isset($response->status));
    }

    /**
     * @return void
     */
    public function testUserTimelineStatusSinceTwoDaysAgoDateAsIntegerReturnsResults()
    {
        $this->insertTestTwitterData();
        $response = $this->twitter->status->userTimeline( array('id' => 'zftestuser1', 'since' => strtotime('-2 days')) );
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());

        $this->assertTrue(isset($response->status));
    }

    /**
     * @return void
     */
    public function testFriendsTimelineStatusSinceTwoDaysAgoReturnsResults()
    {
        /* @var $response Zend_Rest_Client_Result */
        $response = $this->twitter->status->friendsTimeline( array('id' => 'zftestuser1', 'since' => '-2 days') );
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());

        $this->assertTrue(isset($response->status));
    }

    /**
     * @return void
     */
    public function testFriendsTimelineWithPageReturnsResults()
    {
        /* @var $response Zend_Rest_Client_Result */
        $response = $this->twitter->status->friendsTimeline( array('id' => 'zftestuser1', 'page' => '2') );
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());

        $this->assertTrue(isset($response->status));
    }

    /**
     * @return void
     */
    public function testFriendsTimelineWithCountReturnsResults()
    {
        /* @var $response Zend_Rest_Client_Result */
        $response = $this->twitter->status->friendsTimeline( array('id' => 'zftestuser1', 'count' => '2') );
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());

        $this->assertTrue(isset($response->status));
        $this->assertEquals(2, count($response->status), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
    }

    /**
     * @return void
     */
    public function testUserTimelineStatusWithPageAndTwoTweetsReturnsResults()
    {
        /* @var $response Zend_Rest_Client_Result */
        $response = $this->twitter->status->userTimeline( array('id' => 'zftestuser1', 'count' => 2) );
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $raw_response = $httpResponse->getHeadersAsString() . $httpResponse->getBody();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());

        $this->assertTrue(isset($response->status));
        $this->assertEquals(2, count($response->status), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
    }

    public function testUserTimelineStatusShouldReturnFortyResults()
    {
        /* @var $response Zend_Rest_Client_Result */
        $response = $this->twitter->status->userTimeline( array('id' => 'zftestuser1', 'count' => 40) );
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());

        $this->assertTrue(isset($response->status));
        $this->assertEquals(40, count($response->status));
    }

    /**
     * @return void
     */
    public function testPostStatusUpdateReturnsResponse()
    {
        /* @var $response Zend_Rest_Client_Result */
        $response = $this->twitter->status->update( 'Test Message - ' . rand() );
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);

        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
        $this->assertTrue(isset($response->status));
    }

    /**
     * $return void
     */
    public function testPostStatusUpdateToLongShouldThrowException()
    {
        try {
            $response = $this->twitter->status->update( 'Test Message - ' . str_repeat(' Hello ', 140) );
            $this->fail('Trying to post a status with > 140 character should throw exception');
        } catch (Exception $e) {
        }
    }

	public function testPostStatusUpdateUTF8ShouldNotThrowException()
	{
		try {
			$response = $this->twitter->status->update( str_repeat('M�r', 46) . 'M�' );
		} catch (Exception $e) {
			$this->fail('Trying to post a utf8 string of 140 chars should not throw exception');
		}
	}

    /**
     * $return void
     */
    public function testPostStatusUpdateEmptyShouldThrowException()
    {
        try {
            $response = $this->twitter->status->update('');
            $this->fail('Trying to post an empty status should throw exception');
        } catch (Exception $e) {
        }
    }

    /**
     * @return void
     */
    public function testShowStatusReturnsResponse()
    {
        $response = $this->twitter->status->publicTimeline();
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);

        $status_id = $response->toValue($response->status->id);
        $this->assertType('numeric', $status_id);

        $response2 = $this->twitter->status->show($status_id);
        $this->assertTrue($response2 instanceof Zend_Rest_Client_Result);

        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
        $this->assertTrue(isset($response->status));

    }

    /**
     * @return void
     */
    public function testCreateFavoriteStatusReturnsResponse()
    {
        /* @var $response Zend_Rest_Client_Result */
        $response = $this->twitter->status->userTimeline();
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);

        $update_id = $response->toValue($response->status->id);
        $this->assertType('numeric', $update_id);

        $response2 = $this->twitter->favorite->create($update_id);
        $this->assertTrue($response2 instanceof Zend_Rest_Client_Result);

        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
        $this->assertTrue(isset($response->status));

    }

    /**
     * @return void
     */
    public function testFavoriteFavoriesReturnsResponse()
    {
        $response = $this->twitter->favorite->favorites();
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);

        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
        $this->assertTrue(isset($response->status));
    }

    public function testDestroyFavoriteReturnsResponse()
    {
        $response = $this->twitter->favorite->favorites();
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);

        $update_id = $response->toValue($response->status->id);
        $this->assertType('numeric', $update_id);

        $response2 = $this->twitter->favorite->destroy($update_id);
        $this->assertTrue($response2 instanceof Zend_Rest_Client_Result);

        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
        $this->assertTrue(isset($response->status));
    }

    public function testStatusDestroyReturnsResult()
    {
        /* @var $response Zend_Rest_Client_Result */
        $response = $this->twitter->status->userTimeline();
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);

        $update_id = $response->toValue($response->status->id);
        $this->assertType('numeric', $update_id);

        $response2 = $this->twitter->status->destroy($update_id);
        $this->assertTrue($response2 instanceof Zend_Rest_Client_Result);

        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
        $this->assertTrue(isset($response->status));
    }

    public function testUserFriendsReturnsResults()
    {
        $response = $this->twitter->user->friends();
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
        $this->assertTrue(isset($response->status));
    }

    public function testUserFolloersReturnsResults()
    {
        $response = $this->twitter->user->followers(array('id' =>'zftestuser1'));
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
        $this->assertTrue(isset($response->status));
    }

    public function testUserFriendsSpecificUserReturnsResults()
    {
        $response = $this->twitter->user->friends(array('id' =>'zftestuser1'));
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
        $this->assertTrue(isset($response->status), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());

        return $response;
    }

    public function testUserShowReturnsResults()
    {
        $userInfo = $this->testUserFriendsSpecificUserReturnsResults();
        $userId = $userInfo->toValue($userInfo->user->id);

        $response = $this->twitter->user->show($userId);
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);

        $this->assertEquals($userInfo->toValue($userInfo->user->name), $response->toValue($response->name));
        $this->assertEquals($userId, $response->toValue($response->id));
    }

    public function testStatusRepliesReturnsResults()
    {
        $response = $this->twitter->status->replies(array('since' => '-800 days', 'page' => 1, 'since_id' => 10000, 'invalid_option' => 'doh'));
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);
        $httpClient    = Zend_Service_Twitter::getHttpClient();
        $httpRequest   = $httpClient->getLastRequest();
        $httpResponse  = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
    }

    /**
     * @return void
     */
    public function testFriendshipDestory()
    {
        $response = $this->twitter->friendship->destroy('zftestuser1');
        $this->assertTrue($response instanceof Zend_Rest_Client_Result);

        $httpClient     = Zend_Service_Twitter::getHttpClient();
        $httpRequest    = $httpClient->getLastRequest();
        $httpResponse   = $httpClient->getLastResponse();
        $this->assertTrue($httpResponse->isSuccessful(), $httpResponse->getStatus() . ': ' . var_export($httpRequest, 1) . '\n' . $httpResponse->getHeadersAsString());
    }

    /**
     * Insert Test Data
     *
     */
    protected function insertTestTwitterData()
    {
        $twitter = new Zend_Service_Twitter('zftestuser1','zftestuser1');
        // create 10 new entries
        for($x=0; $x<10; $x++) {
            $twitter->status->update( 'Test Message - ' . $x);
        }
        $twitter->account->endSession();
    }
}
