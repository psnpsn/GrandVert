<?php

namespace WebApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use WebApiBundle\Entity\Task;
use WebApiBundle\WebApiBundle;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@WebApi/Default/index.html.twig');
    }

    public function getAllAction()
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('WebApiBundle:Task')
            ->findAll();

        $serializer = new Serializer([new ObjectNormalizer()]);

        $formatted = $serializer->normalize($tasks);

        return new JsonResponse($formatted);
    }

    public function addTaskAction(Request $request)
    {
        $task = new Task();
        $task->setName($request->get('name'));
        $task->setStatus($request->get('status'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();


        $serializer = new Serializer([new ObjectNormalizer()]);

        $formatted = $serializer->normalize($task);

        return new JsonResponse($formatted);
    }



}

