<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Context\Traits;

use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\ThemeElementInterface;

/**
 * Provides driver agnostic logic (helper methods) relating to themes.
 */
trait ThemeAwareContextTrait
{
    use BaseAwarenessTrait;

    /**
     * @var ThemeElementInterface $themeElement;
     */
    protected $themeElement;

    /**
     * Switch active theme.
     *
     * @param string $theme
     */
    public function switchTheme(string $theme)
    {
        $this->themeElement->change($theme);
    }

    public function setThemeElement(ThemeElementInterface $themeElement)
    {
        echo "Setting the theme element\n";
        $this->themeElement = $themeElement;
    }
}
