<?php
/**
 * @var tiFy\Contracts\Views\ViewInterface $this
 */
?>

<?php
echo partial('tag', [
    'tag'     => 'a',
    'attrs'   => [
        'class'       => 'CookieLaw-button CookieLaw-button--accept',
        'href'        => "#{$this->get('id')}",
        'aria-toggle' => 'accept'
    ],
    'content' => __('Accepter', 'theme')
]);