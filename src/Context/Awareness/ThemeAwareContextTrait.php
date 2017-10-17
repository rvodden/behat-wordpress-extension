<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Awareness;

trait ThemeAwareContextTrait
{

    /**
     * Switch active theme.
     *
     * @param string $theme
     */
    public function switchTheme($theme)
    {
        $this->getDriver()->theme->change($theme);
    }
}
