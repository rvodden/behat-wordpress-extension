<?php
declare(strict_types=1);
namespace PaulGibbs\WordpressBehatExtension\Driver\Wpcli\Element;

use UnexpectedValueException;
use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;
use function PaulGibbs\WordpressBehatExtension\Util\buildCLIArgs;
use PaulGibbs\WordpressBehatExtension\Driver\Element\Interfaces\TermElementInterface;
use PaulGibbs\WordpressBehatExtension\Driver\Wpcli\WpcliDriverInterface;

/**
 * WP-CLI driver element for taxonomy terms.
 */
class TermElement extends BaseElement implements TermElementInterface
{
    /**
     * @var WpcliDriverInterface $driver
     */
    protected $driver;

    public function __construct(WpcliDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Create an item for this element.
     *
     * @param array $args Data used to create an object.
     *
     * @return mixed The new item.
     */
    public function create($args)
    {
        $wpcli_args = buildCLIArgs(
            array(
                'description',
                'parent',
                'slug',
            ),
            $args
        );

        array_unshift($wpcli_args, $args['taxonomy'], $args['term'], '--porcelain');
        $term_id = (int) $this->driver->wpcli('term', 'create', $wpcli_args)['stdout'];

        return $this->get($term_id);
    }

    /**
     * Retrieve an item for this element.
     *
     * @param int|string $id   Object ID.
     * @param array      $args Optional data used to fetch an object.
     *
     * @throws \UnexpectedValueException
     *
     * @return mixed The item.
     */
    public function get($id, $args = [])
    {
        $wpcli_args = buildCLIArgs(
            array(
                'field',
                'fields',
            ),
            $args
        );

        array_unshift($wpcli_args, $args['taxonomy'], $id, '--format=json');
        $term = $this->driver->wpcli('term', 'get', $wpcli_args)['stdout'];
        $term = json_decode($term);

        if (! $term) {
            throw new UnexpectedValueException(sprintf('[W503] Could not find term with ID %d', $id));
        }

        return $term;
    }

    /**
     * Delete an item for this element.
     *
     * @param int|string $id   Object ID.
     * @param array      $args Optional data used to delete an object.
     */
    public function delete($id, $args = [])
    {
        $wpcli_args = [
            $args['taxonomy'],
            $id,
        ];

        $this->driver->wpcli('term', 'delete', $wpcli_args);
    }
}
