<?php

namespace APY\DataGridBundle\Grid;

use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Exception\InvalidArgumentException;
use APY\DataGridBundle\Grid\Exception\UnexpectedTypeException;
use Symfony\Component\DependencyInjection\Container;
use Twig\Environment ;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * A builder for creating Grid instances.
 *
 * @author  Quentin Ferrer
 */
class GridBuilder extends GridConfigBuilder implements GridBuilderInterface
{
    /**
     * The container.
     */
    private Container $container;

    private EntityManager $doctrine;

    private RequestStack $request_stack;

    private AuthorizationCheckerInterface $securityContext;

    private Environment $twig;

    private RouterInterface $router;

    /**
     * The factory.
     */
    private GridFactoryInterface $factory;

    /**
     * Columns of the grid builder.
     *
     * @var Column[]
     */
    private array $columns = [];

    /**
     * Constructor.
     *
     * @param Container            $container The service container
     * @param GridFactoryInterface $factory   The grid factory
     * @param string               $name      The name of the grid
     * @param array                $options   The options of the grid
     */
    public function __construct(Container $container, EntityManager $doctrine, RequestStack $request_stack, AuthorizationCheckerInterface $securityContext, RouterInterface $router, Environment $twig, GridFactoryInterface $factory, $name, array $options = [])
    {
        parent::__construct($name, $options);
        $this->router = $router;
        $this->request_stack = $request_stack;
        $this->doctrine = $doctrine;
        $this->container = $container;
        $this->factory = $factory;
        $this->securityContext = $securityContext;
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function add($name, $type, array $options = [])
    {
        if (!$type instanceof Column) {
            if (!is_string($type)) {
                throw new UnexpectedTypeException($type, 'string, APY\DataGridBundle\Grid\Column\Column');
            }

            $type = $this->factory->createColumn($name, $type, $options);
        }

        $this->columns[$name] = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new InvalidArgumentException(sprintf('The column with the name "%s" does not exist.', $name));
        }

        $column = $this->columns[$name];

        return $column;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return isset($this->columns[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        unset($this->columns[$name]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGrid()
    {
        $config = $this->getGridConfig();

        $grid = new Grid($this->container, $this->doctrine, $this->router,  $this->request_stack, $this->securityContext, $this->twig, $config->getName(), $config);

        foreach ($this->columns as $column) {
            $grid->addColumn($column);
        }

        if (!empty($this->actions)) {
            foreach ($this->actions as $columnId => $actions) {
                foreach ($actions as $action) {
                    $grid->addRowAction($action);
                }
            }
        }

        $grid->initialize();

        return $grid;
    }
}
