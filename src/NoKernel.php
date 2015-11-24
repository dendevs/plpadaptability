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
     * Constructor
     */
    public function __construct()
    {
        $this->_config = array();
    }

    /**
     * Ajout la configuration du service au kernel
     *
     * @param array $service_config tableau de configuration du service
     * 
     * @return array tableau de Configuration
     */
    public function set_config( $service_config )
    {
        $this->_config = $service_config;
        return $this->_config;
    }

    /**
     * Recupere la valeur de l'option demander.
     *
     * Donne une valeur par default si non trouver et argument 2 exists
     *
     * @param string $config_name nom de l'option de Configuration a retrouver service.option_name.sous_option
     * @param string $default_value false par defaut. 
     *
     * @return false|mixed la valeur de l'option
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
     * Retourne toute la configuration.
     *
     * Renvoi toute la config d'un service ou de tout les services
     *
     * @param string $service_name le nom du service dont on veut tout
     *
     * @return array tableau ou tableau de tableau de config
     */
    public function get_config_values( $service_name = false ) // pour etre identique a Kernel
    {
        return $this->_config;
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
