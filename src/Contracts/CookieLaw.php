<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw\Contracts;

use Exception;
use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Partial\Modal;
use tiFy\Contracts\View\Engine as ViewEngine;
use tiFy\Contracts\Filesystem\LocalFilesystem;
use tiFy\Contracts\Support\ParamsBag;

interface CookieLaw
{
    /**
     * Récupération de l'instance de l'extension.
     *
     * @return static
     *
     * @throws Exception
     */
    public static function instance(): CookieLaw;

    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Récupération de l'instance de l'adapteur
     *
     * @return CookieLawAdapter|null
     */
    public function adapter(): ?CookieLawAdapter;

    /**
     * Initialisation du gestionnaire.
     *
     * @return static
     */
    public function boot(): CookieLaw;

    /**
     * Récupération de paramètre|Définition de paramètres|Instance du gestionnaire de paramètre.
     *
     * @param string|array|null $key Clé d'indice du paramètre à récupérer|Liste des paramètre à définir.
     * @param mixed $default Valeur de retour par défaut lorsque la clé d'indice est une chaine de caractère.
     *
     * @return mixed|ParamsBag
     */
    public function config($key = null, $default = null);

    /**
     * Récupération du conteneur d'injection de dépendances.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;

    /**
     * Récupération d'un service fourni par le conteneur d'injection de dépendance.
     *
     * @param string $name
     *
     * @return callable|object|string|null
     */
    public function getProvider(string $name);

    /**
     * Récupération de l'instance de la fenêtre modale.
     *
     * @return Modal|null
     */
    public function modal(): ?Modal;

    /**
     * Traitement des attributs de configuration.
     *
     * @return static
     */
    public function parseConfig(): CookieLaw;

    /**
     * Résolution de service fourni.
     *
     * @param string $alias
     *
     * @return object|mixed|null
     */
    public function resolve(string $alias);

    /**
     * Vérification de résolution possible d'un service fourni.
     *
     * @param string $alias
     *
     * @return bool
     */
    public function resolvable(string $alias): bool;

    /**
     * Chemin absolu vers une ressources (fichier|répertoire).
     *
     * @param string|null $path Chemin relatif vers la ressource.
     *
     * @return LocalFilesystem|string|null
     */
    public function resources(?string $path = null);

    /**
     * Définition de l'adapteur associé.
     *
     * @param CookieLawAdapter $adapter
     *
     * @return static
     */
    public function setAdapter(CookieLawAdapter $adapter): CookieLaw;

    /**
     * Définition des paramètres de configuration.
     *
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return static
     */
    public function setConfig(array $attrs): CookieLaw;

    /**
     * Définition du conteneur d'injection de dépendances.
     *
     * @param Container $container
     *
     * @return static
     */
    public function setContainer(Container $container): CookieLaw;

    /**
     * Affichage.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Récupération d'une instance du gestionnaire de gabarits d'affichage ou affichage d'un gabarit.
     *
     * @param string|null $name
     * @param array $data
     *
     * @return ViewEngine|string
     */
    public function view(?string $name = null, array $data = []);

    /**
     * Controleur de traitement de la requête HTTP XHR.
     *
     * @return array
     */
    public function xhrModal(): array;
}
