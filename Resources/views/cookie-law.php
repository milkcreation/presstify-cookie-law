<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 */
?>
<?php $this->insert('privacy-policy', $this->all()); ?>

<?php
echo partial(
    'cookie-notice',
    [
        'accept'        => false,
        'attrs'         => [
            'id'    => $this->get('id'),
        ],
        'content'       => $this->fetch('notice', $this->all()),
        'cookie_name'   => 'CookieLaw',
        'cookie_expire' => YEAR_IN_SECONDS,
        'dismiss'       => false,
        'type'          => 'info',
    ]
);
?>
