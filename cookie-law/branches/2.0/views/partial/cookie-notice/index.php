<?php
/**
 * @var tiFy\Plugins\CookieLaw\CookieLawView $this
 */
?>
<?php $this->insert('partial/cookie-notice/privacy-policy', $this->all()); ?>

<?php echo partial('cookie-notice', [
    'attrs'   => [
        'id' => $this->get('id'),
        'class' => '%s CookieLaw'
    ],
    'content' => $this->fetch('partial/cookie-notice/notice', $this->all()),
    'cookie'  => [
        'name'   => 'CookieLaw',
        'expire' => YEAR_IN_SECONDS,
    ],
    'dismiss' => false,
    'type'    => 'info',
    'trigger' => false,
]);