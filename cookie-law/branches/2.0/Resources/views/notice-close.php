<?php
/**
 * Cookie Law - Notification | Fermeture.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Plugins\CookieLaw\CookieLawView $this
 */
echo partial('tag', [
    'tag'     => 'a',
    'attrs'   => [
        'class'       => 'CookieLaw-close',
        'href'        => "#{$this->get('attrs.id')}",
        'data-toggle' => 'notice.dismiss'
    ],
    'content' => __('Fermer', 'tify')
]);