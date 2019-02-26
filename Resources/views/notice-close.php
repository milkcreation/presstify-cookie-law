<?php
/**
 * Cookie Law - Notification | Fermeture.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\View\ViewController $this
 */
?>
<?php
echo partial('tag', [
    'tag'     => 'a',
    'attrs'   => [
        'class'       => 'CookieLaw-close',
        'href'        => "#{$this->get('id')}",
        'data-toggle' => 'notice.dismiss'
    ],
    'content' => __('Fermer', 'tify')
]);