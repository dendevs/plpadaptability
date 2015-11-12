<?php
namespace DenDev\Plpadaptability\Test;
use DenDev\Plpadaptability\Config;


class ConfigTest extends \PHPUnit_Framework_TestCase 
{
	public function test_instanciate()
	{
		$object = new Config();
		$this->assertInstanceOf( 'DenDev\Plpadaptability\Config', $object );
	}

	public function test_get_default_value()
	{
		$object = new Config( array( 'test' => 'test config default ok' ) );
		$this->assertEquals( 'test config default ok', $object->get_value( 'test' ) );
	}

	public function test_get_value()
	{
		$test_file = sys_get_temp_dir() . '/test_config.php';
		file_put_contents( $test_file, "<?php return array( 'test' => 'test config file ok' );" );

		$object = new Config( $test_file );
		$this->assertEquals( 'test config file ok', $object->get_value( 'test' ) );
	}

	public function test_set_value()
	{
		$object = new Config();
		$object->set_value( 'test_tmp', 'test tmp ok' );
		$this->assertEquals( 'test tmp ok', $object->get_value( 'test_tmp' ) );
	}

	public function test_merge_default()
	{
		$default_value = array( 'test' => 'default valeur', 'existe' => 'valeur non redefinie'  );
		$object = new Config( array( 'test' => 'nouvel valeur' ) );
		$this->assertArrayHasKey( 'test', $object->merge_default( $default_value ) );
		$this->assertEquals( 'nouvel valeur', $object->get_value( 'test' ) );
		$this->assertEquals( 'valeur non redefinie', $object->get_value( 'existe' ) );
	}

	public function tearDown()
	{
		$test_file = sys_get_temp_dir() . '/test_config.php';
		if( file_exists( $test_file ) )
		{
			unlink( $test_file );
		}
	}
}
