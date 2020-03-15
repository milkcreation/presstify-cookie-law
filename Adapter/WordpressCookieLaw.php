<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw\Adapter;

use tiFy\Plugins\CookieLaw\Contracts\{CookieLaw as CookieLawContract, WordpressCookieLaw as WordpressCookieLawContract};
use tiFy\Plugins\CookieLaw\CookieLaw;
use tiFy\Wordpress\Proxy\PageHook;

class WordpressCookieLaw extends CookieLaw implements WordpressCookieLawContract
{
    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'in_footer'          => true,
            'page-hook'          => true
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

        if ($this->get('in_footer')) {
            add_action('wp_footer', function () { echo $this; }, 999999);
        }

        return $this;
    }
}
