<?php

/**
 * Exemple de configuration.
 */

return [
    /**
     * Chargement automatique de l'affichage.
     *
     * @var boolean
     */
    'display'                => true,

    /**
     * Activation de l'affichage de la politique de confidentialité dans une fenêtre modale ou liste des attributs de
     * configuration de la modale.
     *
     * @var boolean|array
     */
    'modal'                  => true,

    /**
     * Activation de l'administrabilité de la page d'accroche de la politique de confidentialité ou Attributs de
     * configuration de la page d'accroche de la politique.
     *
     * @var boolean|array
     */
    'page-hook'              => true,

    /**
     * Identifiant de qualification de la page de politique de confidentialité.
     * {@internal Utilise la page par défaut native de Wordpress}
     *
     * @var string
     */
    'privacy_policy_page_id' => '',

    /**
     * Attributs de configuration du gestionnaire d'affichage de gabarits.
     *
     * @see \tiFy\Contracts\View\ViewEngine
     *
     * @var array
     */
    'viewer'                 => [],

    /**
     * Désactivation du chargement automatique des scripts.
     * {@internal Ajouter "import 'presstify-plugins/cookie-law/Resources/assets/index.js';" à votre feuille de style
     * global}
     *
     * @var boolean
     */
    'wp_enqueue_scripts'     => true
];