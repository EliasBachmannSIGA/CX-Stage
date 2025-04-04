<?php

/**
 * @package    Grav\Common\Errors
 *
 * @copyright  Copyright (c) 2015 - 2025 Trilby Media, LLC. All rights reserved.
 * @license    MIT License; see LICENSE file for details.
 */

namespace Grav\Common\Errors;

use ErrorException;
use InvalidArgumentException;
use RuntimeException;
use Whoops\Handler\Handler;
use Whoops\Util\Misc;
use Whoops\Util\TemplateHelper;

/**
 * Class SimplePageHandler
 * @package Grav\Common\Errors
 */
class SimplePageHandler extends Handler
{
    /** @var array */
    private $searchPaths = [];
    /** @var array */
    private $resourceCache = [];

    public function __construct()
    {
        // Add the default, local resource search path:
        $this->searchPaths[] = __DIR__ . '/Resources';
    }

    /**
     * @return int
     */
    public function handle()
    {
        $inspector = $this->getInspector();

        $helper = new TemplateHelper();
        $templateFile = $this->getResource('layout.html.php');
        $cssFile      = $this->getResource('error.css');

        $code = $inspector->getException()->getCode();
        if (($code >= 400) && ($code < 600)) {
            $this->getRun()->sendHttpCode($code);
        }
        $message = $inspector->getException()->getMessage();

        if ($inspector->getException() instanceof ErrorException) {
            $code = Misc::translateErrorCode($code);
        }

        $vars = array(
            'stylesheet' => file_get_contents($cssFile),
            'code'        => $code,
            'message'     => htmlspecialchars(strip_tags(rawurldecode($message)), ENT_QUOTES, 'UTF-8'),
        );

        $helper->setVariables($vars);
        $helper->render($templateFile);

        return Handler::QUIT;
    }

    /**
     * @param string $resource
     * @return string
     * @throws RuntimeException
     */
    protected function getResource($resource)
    {
        // If the resource was found before, we can speed things up
        // by caching its absolute, resolved path:
        if (isset($this->resourceCache[$resource])) {
            return $this->resourceCache[$resource];
        }

        // Search through available search paths, until we find the
        // resource we're after:
        foreach ($this->searchPaths as $path) {
            $fullPath = "{$path}/{$resource}";

            if (is_file($fullPath)) {
                // Cache the result:
                $this->resourceCache[$resource] = $fullPath;
                return $fullPath;
            }
        }

        // If we got this far, nothing was found.
        throw new RuntimeException(
            "Could not find resource '{$resource}' in any resource paths (searched: " . implode(', ', $this->searchPaths). ')'
        );
    }

    /**
     * @param string $path
     * @return void
     */
    public function addResourcePath($path)
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException(
                "'{$path}' is not a valid directory"
            );
        }

        array_unshift($this->searchPaths, $path);
    }

    /**
     * @return array
     */
    public function getResourcePaths()
    {
        return $this->searchPaths;
    }
}
