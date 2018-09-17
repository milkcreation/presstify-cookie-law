<?php
?>

<?php
echo partial(
    'cookie_notice',
    [
        'attrs'         => $attrs,
        'content'       => $content . $policy,
        'accept'        => $accept,
        'dismiss'       => $dismiss,
        'cookie_name'   => $cookie_name,
        'cookie_hash'   => $cookie_hash,
        'cookie_expire' => $cookie_expire,
    ]
);