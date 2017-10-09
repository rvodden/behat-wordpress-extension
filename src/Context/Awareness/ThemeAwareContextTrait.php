<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Awareness;

/**
 *
 * @author voddenr
 *        
 */
trait ThemeAwareContextTrait {
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

