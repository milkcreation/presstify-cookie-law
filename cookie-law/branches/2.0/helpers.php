<?php declare(strict_types=1);

use tiFy\Plugins\CookieLaw\Contracts\CookieLaw as CookieLawContract;
use tiFy\Plugins\CookieLaw\CookieLaw;

if (!function_exists('cookie_law')) {
    /**
     * Récupération de l'instance de gestionnaire de plugin.
     *
     * @return CookieLaw|null
     */
    function cookie_law(): ?CookieLawContract
    {
        return CookieLaw::instance();
    }
}