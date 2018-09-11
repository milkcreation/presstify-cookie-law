<?php

/**
 * @name CookieLaw
 * @desc Extension PresstiFy de notification des règles cookie du site.
 * @author Jordy Manner <jordy@tigreblanc.fr>
 * @package presstiFy
 * @namespace tiFy\Plugins\CookieLaw
 * @version 2.0.0
 */

namespace tiFy\Plugins\CookieLaw;

use Illuminate\Support\Arr;
use tiFy\App\Dependency\AbstractAppDependency;
use tiFy\Partial\Partial;

class CookieLaw extends AbstractAppDependency
{
    /**
     * Liste des attributs de configuration.
     * @var array $attributes {
     *      @var string|callable $content Texte du message de notification.
     *      @var array $accept {
     *          Liste des attributs de configuration du bouton de validation.
     *      }
     *      @var array $dismiss {
     *          Liste des attributs de configuration du bouton de fermeture.
     *      }
     *      @var bool $display Activation de l'affichage sur toutes les pages du site.
     *      @var bool $enqueue_scripts Activation de la mise en file automatique des scripts.
     * }
     */
    protected $attributes = [
        'attrs'           => [],
        'content'         => '',
        'accept'          => [],
        'dismiss'         => false,
        'cookie_name'     => 'tify_cookie_law',
        'cookie_hash'     => true,
        'cookie_expire'   => YEAR_IN_SECONDS,

        'backdrop'        => true,

        'theme'           => 'dark',

        'policy'          => [
            'modal'         => true
        ],
        'display'         => true,
        'enqueue_scripts' => true
    ];

    /**
     * Récupération de la liste des attributs de configuration.
     *
     * @return array
     */
    public function all()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if (is_admin()) :
            return;
        endif;

        $this->app->appAddAction('init', [$this, 'init']);
        $this->app->appAddAction('wp_loaded', [$this, 'wp_loaded']);
        $this->app->appAddAction('wp_enqueue_scripts', [$this, 'wp_enqueue_scripts']);
        $this->app->appAddAction('wp_footer', [$this, 'wp_footer']);
    }

    /**
     * Affichage.
     *
     * @return string
     */
    public function display()
    {
        return $this->app->appTemplateRender('cookie-law', config('cookie-law', []));
    }

    /**
     * Récupération d'un attribut de configuration.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->attributes, $key, $default);
    }

    /**
     * Vérification d'existance d'un attribut de configuration.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     *
     * @return bool
     */
    public function has($key)
    {
        return Arr::has($this->attributes, $key);
    }

    /**
     * Initialisation globale de Wordpress.
     *
     * @return void
     */
    public function init()
    {
        \wp_register_style(
            'tiFyCookieLaw',
            class_info($this)->getUrl() . '/assets/css/styles.css',
            [],
            180523
        );
    }

    /**
     * Traitement des attributs de configuration.
     *
     * @param array $attrs Liste des attributs de configuration personnalisés.
     *
     * @return void
     */
    protected function parse($attrs = [])
    {
        $this->set(
            'content',
            '<div class="tiFyPluginCookieLaw-Text">' . $this->app->appTemplateRender('content') . '</div>'
        );
        $this->set(
            'accept.attrs.class',
            'tiFyPluginCookieLaw-Button'
        );

        $this->attributes = array_merge(
            $this->attributes,
            config('cookie-law', [])
        );

        if (!$this->has('accept.content')) :
            $this->set('accept.content', __('Accepter', 'tify'));
        endif;

        $this->set('attrs.id', 'tiFyPluginCookieLaw');
        $this->set('attrs.class', 'tiFyPluginCookieLaw tiFyPluginCookieLaw--' . $this->get('theme'));

        $content = $this->get('content', '');
        $this->set('content', is_callable($content) ? call_user_func($content) : $content);

        if ($policy = $this->get('policy')) :
            if (!is_array($policy)) :
                $policy = [];
            endif;

            $this->set(
                'policy',
                (string) Partial::ModalTrigger(
                    array_merge(
                        [
                            'attrs'   => [
                                'class' => 'tiFyPluginCookieLaw-Button'
                            ],
                            'content' => __('En savoir plus', 'tify'),
                            'modal'   => [
                                'size'   => 'lg',

                                'backdrop_close' => false,
                                'header' => '<div class="modal-header"><h2>' .
                                    __('Réglement Général sur la Protection des Données', 'tify') .
                                    '</h2></div>',
                                'body'   => '<div class="modal-body">' . $this->app->appTemplateRender('policy') . '</div>',
                                'footer' => false
                            ]
                        ],
                        $policy
                    )
                )
            );
        endif;

        config(['cookie-law' => $this->all()]);

    }

    /**
     * Définition d'un attribut de configuration.
     *
     * @param string $key Clé d'indexe de l'attribut. Syntaxe à point permise.
     * @param mixed $value Valeur de l'attribut.
     *
     * @return $this
     */
    public function set($key, $value)
    {
        Arr::set($this->attributes, $key, $value);

        return $this;
    }

    /**
     * Initialisation des scripts de l'interface utilisateur
     *
     * @return void
     */
    public function wp_enqueue_scripts()
    {
        if(config('cookie-law.wp_enqueue_scripts', true)) :
            partial('cookie-notice')->enqueue_scripts();
            partial('modal')->enqueue_scripts();
            \wp_enqueue_style('tiFyCookieLaw');
        endif;
    }

    /**
     * Pied de page de l'interface utilisateur du site
     *
     * @return string
     */
    public function wp_footer()
    {
        if(config('cookie-law.display', true)) :
            echo $this->display();
        endif;
    }

    /**
     * A l'issue du chargement complet de Wordpress.
     *
     * @return void
     */
    public function wp_loaded()
    {
        $this->parse();
    }
}
