<?php

namespace YOOtheme\GraphQL\Directive;

use YOOtheme\Container;
use YOOtheme\GraphQL\Type\Definition\Directive;
use YOOtheme\GraphQL\Type\Definition\FieldArgument;
use YOOtheme\GraphQL\Type\Definition\Type;

class BindDirective extends Directive
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct([
            'name' => 'bind',
            'args' => [
                new FieldArgument([
                    'name' => 'id',
                    'type' => Type::string(),
                ]),
                new FieldArgument([
                    'name' => 'class',
                    'type' => Type::string(),
                ]),
                new FieldArgument([
                    'name' => 'args',
                    'type' => Type::string(),
                ]),
            ],
            'locations' => ['OBJECT', 'ENUM_VALUE', 'FIELD_DEFINITION'],
        ]);

        $this->container = $container;
    }

    /**
     * Register service on container.
     *
     * @param array $params
     */
    public function __invoke(array $params)
    {
        if (!$this->container->has($params['id'])) {
            $service = $this->container->add($params['id']);

            if (isset($params['class'])) {
                $service->setClass($params['class']);
            }

            if (isset($params['args'])) {
                $service->setArguments(json_decode($params['args'], true));
            }
        }
    }
}
