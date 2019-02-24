<?php

namespace ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NotificationController extends Controller
{
    public function sendNotificationAction(Request $request)
    {
        $manager = $this->get('mgilet.notification');
        $notif = $manager->createNotification('Hello world!');
        $notif->setMessage('This a notification.');
        $notif->setLink('https://symfony.com/');
        // or the one-line method :
        // $manager->createNotification('Notification subject', 'Some random text', 'https://google.fr/');

        // you can add a notification to a list of entities
        // the third parameter `$flush` allows you to directly flush the entities
        $manager->addNotification(array($this->getUser()), $notif, true);

    }
}
