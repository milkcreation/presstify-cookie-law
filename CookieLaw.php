<?php

namespace tiFy\Plugins\CookieLaw;

use tiFy\Contracts\Partial\Modal;
use tiFy\Contracts\View\ViewController;
use tiFy\Contracts\View\ViewEngine;
use tiFy\Support\ParamsBag;
use tiFy\Wordpress\Query\QueryPost;

/**
 * Class CookieLaw
 *
 * @desc Extension PresstiFy d'affichage des règles de cookie.
 * @author Jordy Manner <jordy@tigreblanc.fr>
 * @package tiFy\Plugins\CookieLaw
 * @version 2.0.14
 *
 * USAGE :
 * Activation
 * ---------------------------------------------------------------------------------------------------------------------
 * Dans config/app.php ajouter \tiFy\Plugins\CookieLaw\CookieLaw à la liste des fournisseurs de services.
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
 * Configuration
 * ---------------------------------------------------------------------------------------------------------------------
 * Dans le dossier de config, créer le fichier cookie-law.php
 * @see /vendor/presstify-plugins/cookie-law/Resources/config/cookie-law.php
 */
class CookieLaw extends ParamsBag
{
    /**
     * Instance de la fenêtre modal d'affichage de la politique de confidentialité.
     * @var null|false|Modal
     */
    protected $modal;

    /**
     * Instance du post de politique de confidentialité du site.
     * @var null|false|QueryPost
     */
    protected $privacy_policy;

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
        $this->set(config('cookie-law', []))->parse();

        add_action('init', function () {
            wp_register_style(
                'CookieLaw', class_info($this)->getUrl() . '/Resources/assets/css/styles.css',
                ['dashicons'],
                180921
            );
        });

        add_action('after_setup_theme', function () {
            if (($page_hook = $this->get('page-hook'))) {
                $defaults = [
                    'option_name'         => 'wp_page_for_privacy_policy',
                    'title'               => __('Page d\'affichage de politique de confidentialité', 'tify'),
                    'desc'                => '',
                    'object_type'         => 'post',
                    'object_name'         => 'page',
                    'id'                  => get_option('wp_page_for_privacy_policy') ?: 0,
                    'listorder'           => 'menu_order, title',
                    'show_option_none'    => '',
                    'display_post_states' => false,
                    'edit_form_notice'    => __(
                        'Vous éditez actuellement la page d\'affichage de politique de confidentialité.', 'tify'
                    )
                ];
                $page_hook = is_array($page_hook) ? array_merge($defaults, $page_hook) : $defaults;

                page_hook(['page_for_privacy_policy' => $page_hook]);
            }
        });

        add_action('wp_enqueue_scripts', function () {
            if ($this->get('wp_enqueue_scripts')) {
                partial('modal')->enqueue();
                partial('cookie-notice')->enqueue();
                wp_enqueue_style('CookieLaw');
            }
        });

        add_action('wp_footer', function () {
            if ($this->get('display')) {
                echo $this->display();
            }
        }, 999999);
    }

    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->display();
    }

    /**
     * @inheritdoc
     */
    public function defaults()
    {
        return [
            'display'            => true,
            'modal'              => true,
            'page-hook'          => true,
            'privacy_policy_id'  => 0,
            'viewer'             => [],
            'wp_enqueue_scripts' => true,
        ];
    }

    /**
     * Affichage.
     *
     * @return ViewController
     */
    public function display()
    {
        return $this->viewer('cookie-law', $this->all());
    }

    /**
     * Récupération de la modal d'affichage de la politique de confidentialité.
     *
     * @return Modal
     */
    public function modal()
    {
        if (is_null($this->modal)) {
            if ($modal = $this->get('modal')) {
                $attrs = array_merge([
                    'attrs'          => [
                        'id' => 'Modal-cookieLaw-privacyPolicy'
                    ],
                    'options'        => ['show' => false, 'backdrop' => false],
                    'header'         => (string)$this->viewer('modal-header', $this->all()),
                    'body'           => (string)$this->viewer('modal-body', $this->all()),
                    'footer'         => (string)$this->viewer('modal-footer', $this->all()),
                    'size'           => 'lg',
                    'backdrop_close' => false,
                    'in_footer'      => false,
                ], is_array($modal) ? $modal : []);

                $this->modal = partial('modal', 'cookieLaw-privacyPolicy', $attrs);
            } else {
                $this->modal = false;
            }
        }
        return $this->modal;
    }

    /**
     * @inheritdoc
     */
    public function parse()
    {
        parent::parse();

        $this->set('id', 'CookieLaw');

        if (!$this->get('privacy_policy_id')) {
            $this->set('privacy_policy_id', get_option('wp_page_for_privacy_policy', 0));
        }
    }

    /**
     * Récupération de l'instance du post de politique de confidentialité du site.
     *
     * @return false|QueryPost
     */
    public function privacyPolicy()
    {
        if (is_null($this->privacy_policy)) {
            $this->privacy_policy = ($post = QueryPost::createFromId($this->get('privacy_policy_id'))) ? $post : false;
        }
        return $this->privacy_policy;
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
        if (!$this->viewer) {
            $cinfo = class_info($this);
            $default_dir = $cinfo->getDirname() . '/Resources/views';
            $this->viewer = view()
                ->setDirectory(is_dir($default_dir) ? $default_dir : null)
                ->setController(CookieLawView::class)
                ->setOverrideDir((($override_dir = $this->get('viewer.override_dir')) && is_dir($override_dir))
                    ? $override_dir
                    : (is_dir($default_dir) ? $default_dir : $cinfo->getDirname()))
                ->set('cookie-law', $this);
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }
        return $this->viewer->make("_override::{$view}", $data);
    }
}
