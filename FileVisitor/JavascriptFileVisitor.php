<?php

namespace Adsum\TranslationBundle\FileVisitor;

use JMS\TranslationBundle\Model\FileSource;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Model\MessageCatalogue;
use JMS\TranslationBundle\Translation\Extractor\FileVisitorInterface;

/**
 * Description of JavascriptFileVisitor
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class JavascriptFileVisitor implements FileVisitorInterface
{

    public function visitFile(\SplFileInfo $file, \JMS\TranslationBundle\Model\MessageCatalogue $catalogue)
    {
        if ('js' !== strtolower($file->getExtension())) {
            return;
        }
        $input = file_get_contents($file->getRealPath());

        $content = preg_replace("/[\r\t ]+/", "", $input);

        $matches = array();

        preg_match_all("/\@adsum_translation\('([.\w]*)','([.\w]*)'\)/", $content, $matches);

        $keys = $matches[1];
        $domains = $matches[2];

        foreach ($keys as $index => $key) {
            $message = new Message($key, $domains[$index]);
            $message->addSource(new FileSource((string) $file));
            $catalogue->add($message);
        }
    }

    public function visitPhpFile(\SplFileInfo $file, MessageCatalogue $catalogue, array $ast)
    {

    }

    public function visitTwigFile(\SplFileInfo $file, \JMS\TranslationBundle\Model\MessageCatalogue $catalogue, \Twig_Node $ast)
    {

    }

}
