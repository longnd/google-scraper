<?php

namespace App\EventSubscriber;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelInterface;

class UploadSubscriber implements EventSubscriberInterface
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function onKernelTerminate(PostResponseEvent $event)
    {
        $request = $event->getRequest();

        /*
         * After user upload CSV file contains search keywords, activate the worker to search
         * for those keywords and extract the data.
         * This just happens after the web send back the response to the user only.
         */
        if ('csvUpload' === $request->attributes->get('_route') && 'POST' === $request->getMethod()) {
            // run the worker to do the search and extract results
            $application = new Application($this->kernel);
            $application->setAutoExit(false);
            $input = new ArrayInput([
                'command' => 'app:scraper',
            ]);

            $output = new BufferedOutput();
            $application->run($input, $output);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.terminate' => 'onKernelTerminate',
        ];
    }
}
