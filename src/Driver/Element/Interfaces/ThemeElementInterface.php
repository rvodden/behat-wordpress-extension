<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces;

interface ThemeElementInterface
{
    /**
     * Switch active theme.
     *
     * @param string $id   Theme name to switch to.
     * @param array  $args Not used.
     */
    public function change($id, $args = []);
}
