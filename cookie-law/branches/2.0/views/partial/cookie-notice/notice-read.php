<?php
/**
 * @var tiFy\Plugins\CookieLaw\CookieLawView $this
 */
if ($this->modal()) :
    echo $this->modal()->trigger([
        'attrs'   => [
            'class'  => 'CookieLaw-button CookieLaw-button--accept',
        ],
        'content' => __('En savoir plus', 'tify')
    ]);
else :
    echo partial('tag', [
        'tag'     => 'a',
        'attrs'   => [
            'class'  => 'CookieLaw-button CookieLaw-button--accept',
            'href'   => $this->get('privacy_policy.permalink'),
            'target' => '_blank'
        ],
        'content' => __('En savoir plus', 'tify')
    ]);
endif;