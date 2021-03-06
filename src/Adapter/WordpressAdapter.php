<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw\Adapter;

use tiFy\Plugins\CookieLaw\Contracts\CookieLaw as CookieLawContract;
use tiFy\Plugins\CookieLaw\Contracts\WordpressAdapter as WordpressAdapterContract;
use tiFy\Plugins\CookieLaw\CookieLawAwareTrait;
use tiFy\Wordpress\Proxy\PageHook;

class WordpressAdapter implements WordpressAdapterContract
{
    use CookieLawAwareTrait;

    /**
     * @param CookieLawContract $cookieLaw
     *
     * @return void
     */
    public function __construct(CookieLawContract $cookieLaw)
    {
        $this->setCookieLaw($cookieLaw);
    }

    /**
     * @inheritDoc
     */
    public function parseConfig(): CookieLawContract
    {
        $conf = $this->cl()->config();

        if (!$conf->has('page-hook')) {
            $conf->set('page-hook', true);
        }

        if (!$conf->has('in_footer')) {
            $conf->set('in_footer', true);
        }

        if ($page_hook = $conf->get('page-hook')) {
            $defaults = [
                'admin'               => true,
                'id'                  => get_option('wp_page_for_privacy_policy') ?: 0,
                'desc'                => '',
                'display_post_states' => false,
                'edit_form_notice'    => __(
                    'Vous √©ditez actuellement la page d\'affichage de politique de confidentialit√©.', 'tify'
                ),
                'listorder'           => 'menu_order, title',
                'object_type'         => 'post',
                'object_name'         => 'page',
                'option_name'         => 'wp_page_for_privacy_policy',
                'show_option_none'    => '',
                'title'               => __('Page d\'affichage de politique de confidentialit√©', 'tify'),
            ];
            PageHook::set('cookie-law', is_array($page_hook) ? array_merge($defaults, $page_hook) : $defaults);

            if (($hook = PageHook::get('cookie-law')) && ($post = $hook->post())) {
                $conf->set('privacy_policy', [
                    'content'   => $post->getContent(),
                    'title'     => $post->getTitle(),
                    'permalink' => $post->getPermalink(),
                ]);
            }
        }

        if ($conf->get('in_footer')) {
            add_action('wp_footer', function () { echo $this->cl()->render(); }, 999999);
        }

        return $this->cl();
    }
}
