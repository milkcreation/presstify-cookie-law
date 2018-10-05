<?php
/**
 * @var tiFy\Contracts\Views\ViewInterface $this
 */
?>

<?php
echo partial('tag', [
    'tag'     => 'a',
    'attrs'   => [
        'class'       => 'CookieLaw-close',
        'href'        => "#{$this->get('id')}",
        'aria-toggle' => 'dismiss'
    ],
    'content' => __('Fermer', 'theme')
]);