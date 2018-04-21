<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element;


/**
 * WP-CLI driver element for site cache.
 */
class CacheElement
{
    /**
     * Clear object cache.
     *
     * @param int   $id   Not used.
     * @param array $args Not used.
     */
    public function update($id, $args = [])
    {
        $this->getDriver()->wpcli('cache', 'flush');
    }


    /*
     * Convenience methods.
     */

    /**
     * Alias of update().
     *
     * @see update()
     */
    public function clear()
    {
        $this->update(0);
    }
}
