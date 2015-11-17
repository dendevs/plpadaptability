<?php
namespace DenDev\Plpwptinymce\Test;
use DenDev\Plpadaptability\Adaptability;


class AdaptabilityTest extends \PHPUnit_Framework_TestCase 
{
	private $_config_path;


	public function setUp()
	{
		$this->_config_path = sys_get_temp_dir() . '/test_config.php';
		file_put_contents( $this->_config_path, "<?php return array( 'test' => 'test config file ok', 'test_1' => 'value 1 from file', 'test_2' => 'value 2 from file' );" );
	}

	public function test_instanciate()
	{
		$object = new Adaptability();
		$this->assertInstanceOf( "DenDev\Plpadaptability\Adaptability", $object );
	}

	public function test_get_service()
	{
		$object = new Adaptability();
		$this->assertFalse( $object->get_service( 'noexist' ) );
	}

	public function test_get_config_value()
	{
		$object = new Adaptability();
		$this->assertEquals( 'test value config ok', $object->get_config_value( 'test' ) );
	}

	public function test_write_log()
	{
		$object = new Adaptability();
		$this->assertEquals( 2, $object->write_log( 'test', 'test ecriture log', 'warning', array( 'test' => 'valeur' ) ) );
	}

	public function tearDown()
	{
		if( file_exists( $this->_config_path ) )
		{
			unlink( $this->_config_path );
		}
	}
}
