<?php
namespace DenDev\Plpadaptability;
use Herrera\Wise\Wise;
use Herrera\Wise\Resource\ResourceCollector;


class Config
{
	private $_wise;
	private $_config;

	public function __construct( $config = array() )
	{
		// default config
		$config_path = $this->get_defaults_config_dir();
		$this->_config = $this->get_config_form_file( $config_path );

		// args config
		if( is_array( $config ) ) 
		{
			$values_config = $config;
		}
		else if( is_a( $config, 'DenDev\Plpadaptability\Config' ) )
		{
			$values_config = $config->get_values();
		}
		else if( file_exists( $config ) )
		{
			$values_config = $this->get_config_form_file( $config );
		}
		else
		{
			throw new \Exception( "config $config not found" );
		}

		// merge configs
		$this->_config = $this->merge_configs( $values_config );
	}

	public function get_defaults_config_dir( $config_name = 'default.php' )
	{
		$config_path = dirname( __FILE__ ) . "/../configs/$config_name";
		if( ! file_exists( $config_path ) )
		{
			$config_path = false;
		}

		return $config_path;
	}

	public function get_config_form_file( $config_file, $flat = false  )
	{
		$config = array();

		if( file_exists( $config_file ) )
		{
			$wise = Wise::create( $config_file );
			//$wise->setCacheDir( sys_get_temp_dir() );
			$wise->setCollector( new ResourceCollector() );	

			if( $flat )
			{
				$config = $wise->loadFlat( $config_file );
			}
			else
			{
				$config = $wise->load( $config_file );
			}
		}

		return $config;
	}

	public function get_value( $config_name, $default_value = false )
	{
		$value = false;

		if( array_key_exists( $config_name, $this->_config ) )
		{
			$value = $this->_config[$config_name];
		}
		else if( $default_value )
		{
			$value = $default_value;
		}

		return $value;
	}

	public function get_values()
	{
		return $this->_config;
	}

	public function set_value( $key, $value )
	{
		return $this->_config[$key] = $value;
	}

	public function merge_configs( $updated_config )
	{
		$configs = array();

		if( is_array( $updated_config ) )
		{
			print_r( $updated_config );
			echo "\n config \n";
			print_r( $this->_config );
			$this->_config= array_merge( $this->_config, $updated_config );
			echo "\n RES \n";
			print_r( $this->_config );
		}

		return $this->_config;
	}

}
