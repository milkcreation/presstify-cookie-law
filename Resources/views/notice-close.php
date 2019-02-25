<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
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
    'content' => __('Fermer', 'tify')
]);