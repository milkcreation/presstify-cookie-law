<?php
/**
 * @var tiFy\Plugins\CookieLaw\CookieLawView $this
 */
echo partial('tag', [
    'tag'     => 'button',
    'attrs'   => [
        'class'       => 'CookieLaw-close',
        'data-toggle' => 'notice.dismiss',
        'aria-label'  => __('Fermeture de la fenêtre', 'tify'),
    ],
    'content' => '',
]);