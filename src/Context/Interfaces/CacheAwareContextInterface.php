<?php
namespace PaulGibbs\WordpressBehatExtension\Context\Interfaces;

use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\CacheElementInterface;

/**
 *
 * @author voddenr
 *
 */
interface CacheAwareContextInterface {
    public function clearCache();

    public function setCacheElement(CacheElementInterface $cacheElement);
}