<?php
namespace DenDev\Plpadaptability\Test;
use DenDev\Plpadaptability\Config;


class ConfigTest extends \PHPUnit_Framework_TestCase 
{
	private $_config_path;


	public function setUp()
	{
		$this->_config_path = sys_get_temp_dir() . '/test_config.php';
		file_put_contents( $this->_config_path, "<?php return array( 'test' => 'test config file ok', 'test_1' => 'value 1 from file', 'test_2' => 'value 2 from file' );" );
	}

	public function test_default_instanciate()
	{
		$object = new Config();
		$this->assertInstanceOf( 'DenDev\Plpadaptability\Config', $object );
		$this->assertEquals( 'test value', $object->get_value( 'test' ) );
	}

	public function test_array_arg_instanciate()
	{
		$object = new Config( array( 'test' => 'new value', 'add_conf' => 'not override' ) );
		$this->assertInstanceOf( 'DenDev\Plpadaptability\Config', $object );
		$this->assertEquals( 'new value', $object->get_value( 'test' ) );
		$this->assertEquals( 'not override', $object->get_value( 'add_conf' ) );
	}

	public function test_object_arg_instanciate()
	{
		$config = new Config( array( 'test' => 'new value from object', 'test_2' => 'from object' ) );

		$object = new Config( $config );
		$this->assertInstanceOf( 'DenDev\Plpadaptability\Config', $object );
		$this->assertEquals( 'new value from object', $object->get_value( 'test' ) );
		$this->assertEquals( 'from object', $object->get_value( 'test_2' ) );
	}

	public function test_file_arg_instanciate()
	{
		$object = new Config( $this->_config_path );
		$this->assertInstanceOf( 'DenDev\Plpadaptability\Config', $object );
		$this->assertEquals( 'test config file ok', $object->get_value( 'test' ) );
		$this->assertEquals( 'value 1 from file', $object->get_value( 'test_1' ) );
	}

	/**
     * @expectedException Exception
	 **/
	public function test_file_arg_error_instanciate()
	{
		$object = new Config( 'jklkljkl' );
	}

	public function test_child_instanciate()
	{
		$object = new ChildConfig();
		$this->assertInstanceOf( 'DenDev\Plpadaptability\Config', $object );
		$this->assertEquals( 'test value', $object->get_value( 'test' ) );
	}


	public function test_get_default_value()
	{
		$object = new Config( array( 'test' => 'test config default ok' ) );
		$this->assertEquals( 'test config default ok', $object->get_value( 'test' ) );
		$this->assertEquals( 'default value', $object->get_value( 'not_found', 'default value' ) );
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

	public function test_merge_configs()
	{
		$updated_values = array( 'test' => 'nouvel valeur', 'existe' => 'valeur non redefinie'  );
		$object = new Config( array( 'test' => 'default valeur' ) );
		$this->assertArrayHasKey( 'test', $object->merge_configs( $updated_values ) );
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

class ChildConfig extends Config
{
	public function __construct()
	{
		parent::__construct();
	}
}
