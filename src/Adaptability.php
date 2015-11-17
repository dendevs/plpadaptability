<?php 
namespace DenDev\Plpadaptability;
use DenDev\Plpadaptability\Config;


class Adaptability
{
    protected $_krl;
    protected $_config;
    protected $_service;


    public function __construct( $krl = false, $config_path = false )
    {
        $this->_set_krl( $krl );
        $this->_set_config( $config_path );
        $this->_service = false;
    }

    // get
    public function get_service( $id_service, $config_file_with_ext = false ) // in NoKernel ?
    {
        return $this->_krl->get_kernel_service( $id_service, $config_file_with_ext );
    }

    public function get_service_instance() // for service classe
    {
        return $this->_service;
    }

    public function get_config_value( $config_name, $default_value = false )
    {
        $value = $this->_config->get_value( $config_name );

        return ( $value ) ? $value : $default_value;
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

    private function _set_config( $config_path )
    {
        if( ! $config_path )
        {
            $config_path = dirname( __FILE__ ) . '/../configs/default.php';
        }

        $this->_config = new Config( $config_path );

        return $this->_config;
    }
}


