<?php

/**
 * @name CookieLaw
 * @desc Extension PresstiFy de notification des règles cookie du site.
 * @author Jordy Manner <jordy@tigreblanc.fr>
 * @package presstiFy
 * @namespace tiFy\Plugins\CookieLaw
 * @version 1.4.0
 */

namespace tiFy\Plugins\CookieLaw;

use App\AppResolverTrait;
use tiFy\Contracts\Views\ViewsInterface;
use tiFy\Contracts\Views\ViewInterface;
use tiFy\Kernel\Parameters\AbstractParametersBag;
use tiFy\PageHook\PageHook;

class CookieLaw extends AbstractParametersBag
{
    use AppResolverTrait;

    /**
     * Liste des attributs de configuration.
     * @var array
     */
    protected $attributes = [
        'id'                 => 'CookieLaw',
        'display'            => true,
        'text'               => '',
        'privacy_policy_url' => '',
        'page_hook'          => true,
        'wp_enqueue_scripts' => true
    ];

    /**
     * Instance du moteur de gabarits d'affichage.
     * @return ViewsInterface
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

        add_action('tify_page_hook_register', function ($pageHook) {
            if (!$this->get('page_hook')) :
                return;
            endif;

            /** @var PageHook $pageHook */
            $pageHook->register('page_for_privacy_policy', [
                    'option_name'      => 'wp_page_for_privacy_policy',
                    'title'            => __('Page d\'affichage de la politique de confidentialité', 'theme'),
                    'desc'             => '',
                    'object_type'      => 'post',
                    'object_name'      => 'page',
                    'id'               => get_option('wp_page_for_privacy_policy') ?: 0,
                    'listorder'        => 'menu_order, title',
                    'show_option_none' => '',
                ]);
        });

        add_action('wp_enqueue_scripts', function () {
            if ($this->get('wp_enqueue_scripts')) :
                partial('cookie-notice')->enqueue_scripts();
                wp_enqueue_style('CookieLaw');
            endif;
        });

        add_action('wp_footer', function () {
            if ($this->get('display')) :
                echo $this->display();
            endif;
        });
    }

    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->display();
    }

    /**
     * Affichage.
     *
     * @return string
     */
    public function display()
    {
        if (!$this->get('privacy_policy_url')) :
            $privacy_policy = get_option('wp_page_for_privacy_policy', 0);
            $privacy_policy_url = $privacy_policy ? get_permalink($privacy_policy) : '';

            $this->set('privacy_policy_url', $privacy_policy_url);
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
     * @return ViewsInterface|ViewInterface
     */
    public function viewer($view = null, $data = [])
    {
        if (!$this->viewer) :
            $cinfo = class_info($this);
            $default_dir = $cinfo->getDirname() . '/Resources/views';
            $this->viewer = view()->setDirectory(is_dir($default_dir) ? $default_dir : null)->setOverrideDir((($override_dir = $this->get('viewer.override_dir')) && is_dir($override_dir)) ? $override_dir : (is_dir($default_dir) ? $default_dir : $cinfo->getDirname()));
        endif;

        if (func_num_args() === 0) :
            return $this->viewer;
        endif;

        return $this->viewer->make("_override::{$view}", $data);
    }
}
