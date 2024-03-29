<?php

namespace Drupal\Tests\simple_fb_connect\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\simple_fb_connect\SimpleFbConnectFbFactory;

/**
 * @coversDefaultClass Drupal\simple_fb_connect\SimpleFbConnectFbFactory
 * @group simple_fb_connect
 */
class SimpleFbConnectFbFactoryTest extends UnitTestCase {

  /**
   * Used for accessing Drupal configuration.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Used for logging errors.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * Used for reading data from and writing data to session.
   *
   * @var \Drupal\simple_fb_connect\SimpleFbConnectPersistentDataHandler
   */
  protected $persistentDataHandler;

  /**
   * FbFactory.
   *
   * @var mixed
   */
  protected $fbFactory;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->loggerFactory = $this->createMock('Drupal\Core\Logger\LoggerChannelFactoryInterface');

    $this->persistentDataHandler = $this->createMock('Drupal\simple_fb_connect\SimpleFbConnectPersistentDataHandler');
  }

  /**
   * Creates mocks with desired configFactory parameters.
   */
  protected function finalizeSetup($app_id, $app_secret) {
    $this->configFactory = $this->getConfigFactoryStub(
      [
        'simple_fb_connect.settings' => [
          'app_id' => $app_id,
          'app_secret' => $app_secret,
        ],
      ]
    );

    $this->fbFactory = new SimpleFbConnectFbFactory(
      $this->configFactory,
      $this->loggerFactory,
      $this->persistentDataHandler
    );
  }

  /**
   * Tests getFbService when app ID and app Secrete have been set.
   *
   * @covers ::getFbService
   * @covers ::validateConfig
   * @covers ::getAppId
   * @covers ::getAppSecret
   */
  public function testGetFbServiceWithGoodData() {
    $this->finalizeSetup('123', 'abc');
    $this->assertInstanceOf('Facebook\Facebook', $this->fbFactory->getFbService());
  }

  /**
   * Tests getFbService with bad data.
   *
   * @covers ::getFbService
   * @covers ::validateConfig
   * @covers ::getAppId
   * @covers ::getAppSecret
   *
   * @dataProvider getFbServiceBadDataProvider
   */
  public function testGetFbServiceWithBadData($app_id, $app_secret) {
    $logger_channel = $this->createMock('Drupal\Core\Logger\LoggerChannel');

    $this->loggerFactory
      ->expects($this->any())
      ->method('get')
      ->with('simple_fb_connect')
      ->willReturn($logger_channel);

    $this->finalizeSetup($app_id, $app_secret);
    $this->assertFalse($this->fbFactory->getFbService());
  }

  /**
   * Data provider for testLoginUser().
   *
   * @return array
   *   Nested arrays of values to check.
   *
   * @see ::testLoginuser()
   */
  public function getFbServiceBadDataProvider() {
    return [
      [NULL, NULL],
      ['', ''],
      ['123', NULL],
      [NULL, 'abc'],
      ['123', ''],
      [NULL, ''],
    ];
  }

}
