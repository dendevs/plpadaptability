<?php
namespace DenDev\Plpadaptability;


/**
 * Fournit un kernel par defaut avec services limiter.
 *
 * Permet le logs, gestion erreur, recup config.
 * Permet à un service d'etre utiliser sans reel kernel.
 */
class NoKernel
{
    /** @var array contient toute les valeurs de config */
    private $_config;


    /**
     * Set la config.
     */
    public function __construct()
    {
        $this->_config = array();
    }

    /**
     * Merge la config general du kernl avec la config par defaut du service.
     *
     * La config kernel a la priorite sur celle du service.
     * Le but est de pouvoir customiser le service via le kernel
     *
     * @param array $default_service_configs tableau associatif config valeur
     *
     * @return array la config merger
     */
    public function merge_configs( $default_service_configs )
    {
        if( ( bool ) $default_service_configs ) // better than is_array
        {
            $this->_config = array_merge( $default_service_configs, $this->_config );
        }


        return $this->_config;
    }

    /**
     * Recupere la valeur d'un option de configuration.
     *
     * @param string config_name nom de l'option
     * @param mixed $default_value valeur si l'option n'existe pas, false par default
     *
     * @return mixed|false la valeur ou false si rien trouver
     */
    public function get_config_value( $config_name, $default_value = false )
    {
        $value = false;
        if( array_key_exists( $config_name, $this->_config ) )
        {
            $value = $this->_config[$config_name];
        }
        else if( ! $default_value )
        {
            $value = $default_value;
        }

        return $value;
    }

    /**
     * Ecriture de log basique.
     *
     * Le logs est ecrit dans /tmp par defaut. 
     * Si la config log_path existe alors l'ecriture ce fait dans ce repertoire nom_plugin.
     *
     * @param string $log_name nom fichier ( sans ext ) 
     * @param string $level niveau du message ( info, debug, ... )
     * @param string $message message a logger
     * @param array $context informations supplementaires
     *
     * @return bool true si ecriture ok
     */
    public function log( $service_name, $log_name, $level, $message, $context = array() )
    {
        $ok = false;

        $tmp_log_path = $this->get_config_value( 'log_path' );
        $log_path = ( $this->get_config_value( 'log_path' ) ) ? $this->get_config_value( 'log_path' ) . '/' . $service_name . '/' : sys_get_temp_dir() . '/' . $service_name . '/';

        if( ! file_exists( $log_path ) )
        {
            mkdir( $log_path, 0755 );
        }
        
        $log_path .= $log_name . ".log";

        // avoid big file
        $append = false;
        if( file_exists( $log_path ) && filesize( $log_path ) >= 1024 )
        {
        //    unlink( $log_path );
            $append = FILE_APPEND;
        }
        
        // write
        $context_string = ( (bool) $context ) ? print_r( $context, true ) : '';
        $formated_message = $level . ': ' . $message . ' ( ' . $context_string . ' )';
        $ok = file_put_contents( $log_path, $formated_message, $append );

        return ( $ok === false ) ? false : true;
    }

    /**
     * Gere les erreurs fatal ou non.
     *
     * Si l'erreur est fatal log l'erreur et declenche un object Exception
     * Sinon log l'erreur.
     *
     * @param string $service_name le nom du service_name
     * @param string $message le message
     * @param int $code le code erreur
     * @param array $context infos supp sur le context de l'erreur
     * @param bool $fatal declenche ou non une exception
     *
     * @return bool true si l'ecriture dans le log est ok ( et erreur non fatal )
     */
    public function error( $service_name, $message, $code, $context = false, $fatal = false )
    {
        $ok = false;
        // log
        $level = ( $fatal ) ? 'alert' : 'error'; 

        $context_string = ( (bool) $context ) ? print_r( $context, true ) : '';
        $formated_message = $level . ': ' . $message . ' ( ' . $context_string . ' )';
        
        $ok = $this->log( $service_name, 'error', $level, $formated_message, $context_string );

        // error
        if( $fatal )
        {
            throw new \Exception( $formated_message );
        }

        return $ok;
    }
}
