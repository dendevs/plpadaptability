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
		if( is_array( $config ) )
		{
			$this->_config = $config;
		}
		else
		{
			$config_file = $config;//dirname( __FILE__ )  . '/' . $config;

			if( file_exists( $config_file ) )
			{
				$this->_wise = Wise::create( $config_file ); // TODO file exist
				$this->_wise->setCacheDir( sys_get_temp_dir() );
				$this->_wise->setCollector( new ResourceCollector() );	

				$this->_config = $this->_wise->load( $config_file );
			}
			else
			{
				throw new \Exception( "config $config_file not found" );
			}
		}
	}

	public function get_value( $config_name )
	{
		$value = false;

		if( array_key_exists( $config_name, $this->_config ) )
		{
			$value = $this->_config[$config_name];
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

	public function merge_default( $default_config )
	{
		$ok = false;

		if( is_array( $default_config ) )
		{
			$this->_config = array_merge( $default_config, $this->_config );
			$ok = $this->_config;
		}

		return $ok;
	}

}
