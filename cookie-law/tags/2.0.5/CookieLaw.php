<?php

/**
 * @name CookieLaw
 * @desc Extension PresstiFy de notification des règles cookie du site.
 * @author Jordy Manner <jordy@tigreblanc.fr>
 * @package presstify-plugins/cookie-law
 * @namespace tiFy\Plugins\CookieLaw
 * @version 2.0.5
 */

namespace tiFy\Plugins\CookieLaw;

use tiFy\Contracts\View\ViewController;
use tiFy\Contracts\View\ViewEngine;
use tiFy\Kernel\Params\ParamsBag;

/**
 * Class CookieLaw
 * @package tiFy\Plugins\CookieLaw
 *
 * Activation :
 * ----------------------------------------------------------------------------------------------------
 * Dans config/app.php ajouter \tiFy\Plugins\CookieLaw\CookieLaw à la liste des fournisseurs de services
 *     chargés automatiquement par l'application.
 * ex.
 * <?php
 * ...
 * use tiFy\Plugins\CookieLaw\CookieLaw;
 * ...
 *
 * return [
 *      ...
 *      'providers' => [
 *          ...
 *          CookieLaw::class
 *          ...
 *      ]
 * ];
 *
 * Configuration :
 * ----------------------------------------------------------------------------------------------------
 * Dans le dossier de config, créer le fichier cookie-law.php
 * @see /vendor/presstify-plugins/cookie-law/Resources/config/cookie-law.php. Exemple de configuration.
 */
class CookieLaw extends ParamsBag
{
    /**
     * Liste des attributs de configuration.
     * @var array
     */
    protected $attributes = [
        'admin'              => true,
        'display'            => true,
        'privacy_policy_id'  => 0,
        'viewer'             => [],
        'wp_enqueue_scripts' => true
    ];

    /**
     * Instance du moteur de gabarits d'affichage.
     * @return ViewEngine
     */
    protected $viewer;

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(config('cookie-law', $this->attributes));

        add_action('init', function () {
            wp_register_style('CookieLaw', class_info($this)->getUrl() . '/Resources/assets/css/styles.css',
                ['dashicons'], 180921);
        });

        add_action('after_setup_theme', function () {
            if ($this->get('admin') && app()->has('wp.page-hook')) :
                app()->get('wp.page-hook')->set('page_for_privacy_policy', [
                    'option_name'      => 'wp_page_for_privacy_policy',
                    'title'            => __('Page d\'affichage de la politique de confidentialité', 'theme'),
                    'desc'             => '',
                    'object_type'      => 'post',
                    'object_name'      => 'page',
                    'id'               => get_option('wp_page_for_privacy_policy') ?: 0,
                    'listorder'        => 'menu_order, title',
                    'show_option_none' => '',
                ]);
            endif;
        });

        add_action('wp_enqueue_scripts', function () {
            if ($this->get('wp_enqueue_scripts')) :
                partial('modal')->enqueue_scripts();
                partial('cookie-notice')->enqueue_scripts();
                wp_enqueue_style('CookieLaw');
            endif;
        });

        add_action('wp_footer', function () {
            if ($this->get('display')) :
                echo $this->display();
            endif;
        },
            999999
        );
    }

    /**
     * Affichage.
     *
     * @return ViewController
     */
    public function display()
    {
        $this->set('id', 'CookieLaw');

        if ( ! $this->get('privacy_policy_id')) :
            $this->set('privacy_policy_id', get_option('wp_page_for_privacy_policy', 0));
        endif;

        return $this->viewer('cookie-law', $this->all());
    }

    /**
     * Récupération d'un instance du controleur de liste des gabarits d'affichage ou d'un gabarit d'affichage.
     * {@internal Si aucun argument n'est passé à la méthode, retourne l'instance du controleur de liste.}
     * {@internal Sinon récupére l'instance du gabarit d'affichage et passe les variables en argument.}
     *
     * @param null|string view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return ViewController|ViewEngine
     */
    public function viewer($view = null, $data = [])
    {
        if ( ! $this->viewer) :
            $cinfo        = class_info($this);
            $default_dir  = $cinfo->getDirname() . '/Resources/views';
            $this->viewer = view()
                ->setDirectory(is_dir($default_dir) ? $default_dir : null)
                ->setOverrideDir((($override_dir = $this->get('viewer.override_dir')) && is_dir($override_dir))
                    ? $override_dir
                    : (is_dir($default_dir) ? $default_dir : $cinfo->getDirname()));
        endif;

        if (func_num_args() === 0) :
            return $this->viewer;
        endif;

        return $this->viewer->make("_override::{$view}", $data);
    }

    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->display();
    }
}
