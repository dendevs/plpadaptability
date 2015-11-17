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

	public function test_instanciate()
	{
		$object = new Config( $this->_config_path );
		$this->assertInstanceOf( 'DenDev\Plpadaptability\Config', $object );
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
		$this->assertEquals( 'test value config ok', $object->get_value( 'test' ) );
	}


	public function test_get_default_value()
	{
		$object = new Config( $this->_config_path );
		$this->assertEquals( 'test config file ok', $object->get_value( 'test' ) );
		$this->assertEquals( 'value 1 from file', $object->get_value( 'test_1' ) );
	}

	public function test_get_value()
	{
		$object = new Config( $this->_config_path );
		$this->assertEquals( 'test config file ok', $object->get_value( 'test' ) );
	}

	public function test_set_value()
	{
		$object = new Config( $this->_config_path );
		$object->set_value( 'test_tmp', 'test tmp ok' );
		$this->assertEquals( 'test tmp ok', $object->get_value( 'test_tmp' ) );
	}

	/*
	public function test_merge_configs()
	{
		$updated_values = array( 'test' => 'nouvel valeur', 'existe' => 'valeur non redefinie'  );
		$object = new Config( array( 'test' => 'default valeur' ) );
		$this->assertArrayHasKey( 'test', $object->merge_configs( $updated_values ) );
		$this->assertEquals( 'nouvel valeur', $object->get_value( 'test' ) );
		$this->assertEquals( 'valeur non redefinie', $object->get_value( 'existe' ) );
	}
	 */
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
		$config_path = dirname( __FILE__ ) . '/../configs/default.php';
		parent::__construct( $config_path );
	}
}
