<?php 
namespace DenDev\Plpadaptability\Test;
use DenDev\Plpadaptability\NoKernel;


class NoKernelTest extends \PHPUnit_Framework_TestCase 
{
	public function test_instanciate()
	{
		$object = new NoKernel();
		$this->assertInstanceOf( 'DenDev\Plpadaptability\NoKernel', $object );
	}

	public function test_get_config_value()
	{
		$object = new NoKernel();
		$default_service_configs = array( 'test1' => 'ok value 1' );
		$this->assertEquals( $default_service_configs, $object->set_config( $default_service_configs ) );
		$this->assertEquals( 'ok value 1', $object->get_config_value( 'test1' ) );
	}

	public function test_log()
	{
		$object = new NoKernel();
		$this->assertTrue( $object->log( 'service_test', 'fichier', 'debug', 'message debug texte' ) );
		$this->assertTrue( $object->log( 'service_test', 'fichier', 'error', 'message error texte' ) );
	}

	public function test_error()
	{
		$object = new NoKernel();

		$this->assertTrue( $object->error( 'service_test', 'messsage erreur', 100 ) );
	}

	/**
	 * @expectedException Exception
	 */
	public function test_fatal_error()
	{
		$object = new NoKernel();
		$object->error( 'service_test', 'message erreur fatal', 200, array( 'info' => 'info context' ), true );
	}
}

