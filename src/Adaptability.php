<?php
namespace DenDev\Plpadaptability;
use DenDev\Plpadaptability\AdaptabilityInterface;


/**
 *  Fait le lien entre le service et le kernel ou son absence.
 *
 *  Si un kernel existe la class utiliserat les services du kernel pour le log, error, config.
 *  Instancie un kernel par defaut dans le cas contraire.
 *  Cette classe assure de pouvoir utiliser un service avec ou sans kernel.
 */
abstract class Adaptability implements AdaptabilityInterface
{
    /** @var object kernel offrant les services */
    protected $_krl;
    /** @var array tableau associatif nom service, version, ... */
    protected $_service_metas;


    /**
     * Set le kernel pour l'object enfant ou cree un kernel minimal.
     *
     * @param object $krl la ref du kernel auquel appartient le service ou false par defaut.
     *
     * @return void
     */
    public function __construct( $krl = false )
    {
        $this->_set_kernel( $krl );
        $this->set_service_metas();
    }

    /**
     * Recolte l'inforation sur le(s) services existant.
     *
     * @param bool $meta_name pour recuperer une valeur specifique, renvoi tout si false.
     *
     * @return string|array renvoi une meta_value specifique ou toutes
     */
    public function get_service_metas( $meta_name = false )
    {
        $meta_value = false;
        if( $meta_name &&  array_key_exists( $meta_name, $this->_service_metas ) )
        {
            $meta_value = $this->_service_metas[$meta_name];
        }

        return ( $meta_value ) ? $meta_value : $this->_service_metas;
    }

    /**
     * Donne l'instance du service enfant
     *
     * @return object instance du service
     */
    public function get_service_instance()
    {
        return $this;
    }

    /**
     * Delegue au kernel la recuperation d'une valeur de configuration.
     *
     * @param string $config_name nom de l'option
     * @param mixed $default_value valeur si l'option n'existe pas, false par default
     *
     * @return mixed|false la valeur ou false si rien trouver
     */
    public function get_config_value( $config_name, $default_value = false )
    {
        return $this->_krl->get_config_value( $config_name, $default_value );
    }

    /**
     * Retourne toute la configuration.
     *
     * @return array tableau ou tableau de tableau de config
     */
    public function get_config_values( $service_name = false )
    {
        return $this->_krl->get_config_values( $service_name );
    }

    /**
     * Ecriture de log basique ou via service du kernel
     *
     * @param string $log_name nom du logger, si service de base alors non fichier
     * @param string $level niveau du message ( info, debug, ... )
     * @param string $message message a logger
     * @param array $context informations supplementaires
     *
     * @return bool true si ecriture ok
     */
    public function log( $log_name, $level, $message, $context )
    {
        $service_name = $this->get_service_metas( 'service_name' );
        return $this->_krl->log( $service_name, $log_name, $level, $message, $context );
    }

    /**
     * Gere les erreurs fatal ou non.
     *
     * Sous traite a NoKernel
     * @see NoKernel::error()
     *
     * @param string $message le message
     * @param int $code le code erreur
     * @param array $context infos supp sur le context de l'erreur
     * @param bool $fatal declenche ou non une exception
     *
     * @return bool true si l'ecriture dans le log est ok ( et erreur non fatal )
     */
    public function error( $message, $code, $context = false, $fatal = false )
    {
        $service_name = $this->get_service_metas( 'service_name' );
        return $this->_krl->error( $service_name, $message, $code, $context, $fatal );
    }

    // -
    private function _set_kernel( $krl )
    {
        // create kernel
        if( ! $krl )
        {
            $krl = new NoKernel();
            $krl->set_config( $this->get_default_configs() );
        }

        $this->_krl = $krl;
    }
}
