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
    'display'            => true,

    /**
     * Activation de l'affichage de la politique de confidentialité dans une fenêtre modale ou liste des attributs de
     * configuration de la modale.
     *
     * @var boolean|array
     */
    'modal'              => true,

    /**
     * Données de politique de confidentialité.
     *
     * @var array
     */
    'privacy_policy'     => [
        'title'     => '',
        'content'   => '',
        'permalink' => '',
    ],

    /**
     * Attributs de configuration du gestionnaire d'affichage de gabarits.
     *
     * @see \tiFy\Contracts\View\ViewEngine
     *
     * @var array
     */
    'viewer'             => [],

    /**
     * WORDPRESS
     */
    /**
     * Activation de l'administrabilité de la page d'accroche de la politique de confidentialité ou Attributs de
     * configuration de la page d'accroche de la politique.
     *
     * @var boolean|array
     */
    'page-hook'          => true,

    /**
     * Désactivation du chargement automatique des scripts.
     * {@internal Ajouter "import 'presstify-plugins/cookie-law/Resources/assets/index.js';" à votre feuille de style
     * global}
     *
     * @var boolean
     */
    'wp_enqueue_scripts' => true,
];