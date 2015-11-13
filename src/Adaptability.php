<?php 
namespace DenDev\Plpadaptability;
use DenDev\Plpadaptability\Config;


class Adaptability
{
    protected $_krl;
    protected $_config;
    protected $_service;


    public function __construct( $krl = false, $config = array() )
    {
        $this->_set_krl( $krl );
        $this->_set_config( $config );
    }

    // get
    public function get_service( $id_service ) // in NoKernel ?
    {
        return $this->_krl->get_service( $id_service );
    }

    public function get_config_value( $config_name )
    {
        return $this->_config->get_value( $config_name );
    }

    public function get_config_values()
    {
        return $this->_config->get_values();
    }

    // log
    public function write_log( $log_name, $message, $level, $extras ) // in NoKernel ?
    {
        $ok = false;

        if( $service = $this->get_service( 'logger' ) ) // 
        {
            $service->log( $log_name, $message, $level, $extras );
            $ok = 1;
        }
        else
        {
            $log_path = $this->get_config_value( 'log_path' );

            if( file_exists( $log_path ) )
            {
                $log_path .= $log_name . ".log";
                // avoid big file
                if( file_exists( $log_path ) && filesize( $log_path ) >= 1024 )
                {
                    unlink( $log_path );
                }

                // write
                error_log("$level: $message ( " .  print_r( $extras, true ) . " ) ", 3, $log_path );
                $ok = 2;
            }
            else
            {
				throw new \Exception( "log_path $log_path not found" );
            }
        }

        return $ok;
    }

    // -
    private function _set_krl( $krl )
    {
        if( is_object( $krl ) )
        {
            $this->_krl = $krl;
            $ok = true;
        }
        else
        {
            $this->_krl = new NoKernel();
            $ok = false;
        }

        return $ok;
    }

    private function _set_config( $config )
    {
        $ok = false;

        $config = ( is_null( $config ) || ! $config ) ? array() : $config;
        $this->_config = new Config( $config );
        if( method_exists( $this, '_set_default_config' ) )
        {
            $this->_config->merge_default( $this->_set_default_config() );
        }

        return $ok;
    }
}


