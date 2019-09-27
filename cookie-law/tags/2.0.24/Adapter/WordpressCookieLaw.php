<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw\Adapter;

use Psr\Container\ContainerInterface as Container;
use tiFy\Plugins\CookieLaw\Contracts\{CookieLaw as CookieLawContract, WordpressCookieLaw as WordpressCookieLawContract};
use tiFy\Plugins\CookieLaw\CookieLaw;
use tiFy\Wordpress\Proxy\{PageHook, Partial};

class WordpressCookieLaw extends CookieLaw implements WordpressCookieLawContract
{
    /**
     * CONSTRUCTEUR.
     *
     * @param Container $container Instance du conteneur d'injection de dépendance.
     *
     * @return void
     */
    public function __construct(?Container $container = null)
    {
        parent::__construct($container);

        add_action('init', function () {
            wp_register_style(
                'CookieLaw', class_info($this)->getUrl() . '/Resources/assets/css/styles.css',
                ['dashicons'],
                180921
            );
        });

        add_action('wp_enqueue_scripts', function () {
            if ($this->get('wp_enqueue_scripts')) {
                Partial::get('modal')->enqueue();
                Partial::get('cookie-notice')->enqueue();
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
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'page-hook'          => true,
            'wp_enqueue_scripts' => true,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function parse(): CookieLawContract
    {
        parent::parse();

        if (($page_hook = $this->get('page-hook'))) {
            $defaults = [
                'admin'               => true,
                'id'                  => get_option('wp_page_for_privacy_policy') ?: 0,
                'desc'                => '',
                'display_post_states' => false,
                'edit_form_notice'    => __(
                    'Vous éditez actuellement la page d\'affichage de politique de confidentialité.', 'tify'
                ),
                'listorder'           => 'menu_order, title',
                'object_type'         => 'post',
                'object_name'         => 'page',
                'option_name'         => 'wp_page_for_privacy_policy',
                'show_option_none'    => '',
                'title'               => __('Page d\'affichage de politique de confidentialité', 'tify'),
            ];
            PageHook::set('cookie-law', is_array($page_hook) ? array_merge($defaults, $page_hook) : $defaults);

            if (($hook = PageHook::get('cookie-law')) && ($post = $hook->post())) {
                $this->set('privacy_policy', [
                    'content'   => $post->getContent(),
                    'title'     => $post->getTitle(),
                    'permalink' => $post->getPermalink(),
                ]);
            }
        }

        return $this;
    }
}
