<?php
namespace DenDev\Plpadaptability;


interface AdaptabilityInterface
{
    /**
     * Oblige la presence d'une configuration de base.
     */
    public function get_default_configs();

    /**
     * Chaque service doit avoir un identifiant.
     *
     * Ajoute quelques infos comme la version, etc
     */
    public function set_service_metas();

    /**
     * Renvoi l'instance du service enfant
     *
     * @return object instance du service
     */ 
    public function get_service_instance();

    /**
     * Donne la configuration complete du service
     *
     * Ou la configuration complete de tout les services
     *
     * @param string $service_name nom du service ou false
     *
     * @return array tableau associatif de configuration
     */
    public function get_config_values( $service_name = false );
}
