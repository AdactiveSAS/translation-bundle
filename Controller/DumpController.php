<?php

namespace Adsum\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

class DumpController extends Controller
{

    /**
     * @Route("/dump", name="adsum_translation_dump")
     *
     */
    public function dumpAction()
    {
        $response = new Response();

        if (!static::executeCommand('bazinga:js-translation:dump')) {
            $response->setStatusCode(500);
            return $response;
        }
        if (!static::executeCommand('assetic:dump --force')) {
            $response->setStatusCode(500);
            return $response;
        }
        if (!static::executeCommand('assetic:dump --force --env=prod --no-debug')) {
            $response->setStatusCode(500);
            return $response;
        }
        if (!static::executeCommand('cache:clear --env=prod --no-debug')) {
            $response->setStatusCode(500);
            return $response;
        }
        if (!static::executeCommand('cache:clear')) {
            $response->setStatusCode(500);
            return $response;
        }

        $response->setStatusCode(200, "Ok");

        return $response;
    }

    protected static function executeCommand($cmd, $timeout = 2000)
    {
        $php = escapeshellarg(self::getPhp());

        $process = new Process($php . ' ../app/console ' . $cmd, null, null, null, $timeout);
        $process->run();

        if (!$process->isSuccessful()) {
            echo($process->getErrorOutput());
        }
        if ($process->isSuccessful()) {
            echo($process->getOutput());
        }

        return $process->isSuccessful();
    }

    protected static function getPhp()
    {
        $phpFinder = new PhpExecutableFinder;
        if (!$phpPath = $phpFinder->find()) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }

}
