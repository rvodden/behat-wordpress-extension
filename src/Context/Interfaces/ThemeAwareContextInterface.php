<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Interfaces;

use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\ThemeElementInterface;

interface ThemeAwareContextInterface
{
    /**
     * Switch active theme.
     *
     * @param string $theme
     */
    public function switchTheme(string $theme);

    /**
     * Set the themeElement.
     *
     * @param ThemeElementInterface $themeElememt
     */
    public function setThemeElement(ThemeElementInterface $themeElememt);
}
