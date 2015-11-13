<?php
namespace DenDev\Plpadaptability;


class NoKernel
{
    public function __call( $name, $args )
    {
        //echo "Appel de la méthode '$name' ". print_r( $args, true ). "\n";
    }

    public function get_kernel_service( $id_service )
    {
        return false;
    }

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
            $log_path = "logs/$log_name.log";
            // avoid big file
            if( file_exists( $log_path ) && filesize( $log_path ) >= 1024 )
            {
                unlink( $log_path );
            }

            // write
            error_log("$level: $message ( " .  print_r( $extras, true ) . " ) ", 3, "logs/$log_name.log");
            $ok = 2;
        }

        return $ok;
    }

}
