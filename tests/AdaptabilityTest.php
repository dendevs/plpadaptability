<?php
namespace DenDev\Plpwptinymce\Test;
use DenDev\Plpadaptability\Adaptability;


class AdaptabilityTest extends \PHPUnit_Framework_TestCase 
{
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
		$test_file = sys_get_temp_dir() . '/test_config.php';
		file_put_contents( $test_file, "<?php return array( 'test' => 'test config file ok' );" );

		$object = new Adaptability( false, $test_file );
		$this->assertEquals( 'test config file ok', $object->get_config_value( 'test' ) );

		$object = new ObjectTest( false, array( 'test' => 'test config nouvelle ok' ) );
		$this->assertEquals( 'test config nouvelle ok', $object->get_config_value( 'test' ) ); 
	}

	public function test_write_log()
	{
		$object = new Adaptability( false, array( 'log_path' => sys_get_temp_dir() . '/' ) );
		$this->assertEquals( 2, $object->write_log( 'test', 'test ecriture log', 'warning', array( 'test' => 'valeur' ) ) );
	}

	public function tearDown()
	{
		if( file_exists( sys_get_temp_dir() . '/test.log' ) )
		{
			unlink( sys_get_temp_dir(). '/test.log' );
		}
	}
}
class ObjectTest extends Adaptability
{
	public function __construct( $krl, $config )
	{
		parent::__construct( $krl, $config );
	}

	protected function _get_default_config()
	{
		$config = array( 'test' => 'test config default ok',
	   	'merge' => 'valeur default',
		'norewrite' => 'valeur par defaut non redefinie'	
		);

		return $config;
	}
}
