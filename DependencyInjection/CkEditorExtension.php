<?php

namespace Prokl\CkEditorBundle\DependencyInjection;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Exception;
use InvalidArgumentException;
use Prokl\CkEditorBundle\Services\Installator;
use Local\SymfonyTools\Router\InitRouter;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class CkEditorExtension
 * @package Prokl\CkEditor\DependencyInjection
 *
 * @since 18.04.2021
 */
final class CkEditorExtension extends Extension
{
    private const DIR_CONFIG = '/../Resources/config';

    /**
     * @const BITRIX_MODULE_ID ID смежного по функционалу битриксового модуля.
     */
    private const BITRIX_MODULE_ID = 'prokl.ckeditor';

    /**
     * @const INIT_ROUTER_CLASS Инициализатор роутера.
     */
    private const INIT_ROUTER_CLASS = InitRouter::class;

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container) : void
    {
        if (!$this->checkDepends()) {
            return;
        }

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . self::DIR_CONFIG)
        );

        $loader->load('services.yaml');

        // Загрузка роутов.
        $this->loadRoutes(dirname(__DIR__).'/Resources/config', 'routes.yaml');

        $initializer = new Installator();
        $initializer->installAssets();
    }

    /**
     * @inheritDoc
     */
    public function getAlias() : string
    {
        return 'ckeditor';
    }

    /**
     * Проверка на конфликты - не установлен ли уже соответствующий модуль, среда.
     *
     * @return boolean
     * @throws LoaderException Когда что-то с Битриксом не так.
     */
    private function checkDepends() : bool
    {
        $moduleInstalled = Loader::includeModule(self::BITRIX_MODULE_ID);
        if ($moduleInstalled) {
            return false;
        }

        return true;
    }

    /**
     * Загрузить роуты в бандле.
     *
     * @param string $path   Путь к конфигу.
     * @param string $config Конфигурационный файл.
     *
     * @return void
     *
     * @throws InvalidArgumentException Нет класса-конфигуратора роутов.
     */
    protected function loadRoutes(string $path, string $config = 'routes.yaml') : void
    {
        $routeLoader = new \Symfony\Component\Routing\Loader\YamlFileLoader(
            new FileLocator($path)
        );

        $routes = $routeLoader->load($config);

        if (class_exists(self::INIT_ROUTER_CLASS)) {
            InitRouter::addRoutesBundle($routes);
            return;
        }

        throw new InvalidArgumentException(
            'Router initializer (class Routes) not exist.'
        );
    }
}
