<?php declare(strict_types=1);

namespace tiFy\Plugins\CookieLaw\Contracts;

use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Partial\Modal;
use tiFy\Contracts\View\{PlatesFactory, PlatesEngine};
use tiFy\Contracts\Support\ParamsBag;

interface CookieLaw extends ParamsBag
{
    /**
     * Récupération de l'instance de l'extension gestion des inforamtions de contact.
     *
     * @return static|null
     */
    public static function instance(): ?CookieLaw;

    /**
     * Résolution de sortie de la classe en tant que chaîne de caractère.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Récupération du conteneur d'injection de dépendances.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function defaults(): array;

    /**
     * Récupération de la modale d'affichage de la politique de confidentialité.
     *
     * @return Modal|null
     */
    public function modal(): ?Modal;

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function parse(): CookieLaw;

    /**
     * Affichage.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Chemin absolu vers une ressources (fichier|répertoire).
     *
     * @param string $path Chemin relatif vers la ressource.
     *
     * @return string
     */
    public function resources($path = ''): string;

    /**
     * Définition du conteneur d'injection de dépendances.
     *
     * @param Container $container
     *
     * @return static
     */
    public function setContainer(Container $container): CookieLaw;

    /**
     * Récupération d'un instance du controleur de liste des gabarits d'affichage ou d'un gabarit d'affichage.
     * {@internal Si aucun argument n'est passé à la méthode, retourne l'instance du controleur de liste.}
     * {@internal Sinon récupére l'instance du gabarit d'affichage et passe les variables en argument.}
     *
     * @param string|null view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return PlatesFactory|PlatesEngine
     */
    public function viewer(?string $view = null, array $data = []);
}
