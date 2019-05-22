<?php

namespace WebApiBundle\Controller;

use JardinBundle\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class NoteController extends Controller
{
    public function addNoteAction(Request $request)
    {
        $note = new Note();
        $note->setContenu($request->get('contenu'));
        $note->setDateN($request->get('date'));
        $note->setJardinId($request->get('jardin'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($note);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);

        $formatted = $serializer->normalize($note);

        return new JsonResponse($formatted);
    }

    public function getNotesAction(Request $request)
    {
        $date = $request->get('date');
        $newDate = date_create_from_format("Y-n-d", $date);

        $notes = $this->getDoctrine()->getManager()
            ->getRepository('JardinBundle:Note')
            ->findBy(["dateN" => $newDate,"jardinId" => $request->get('jardin')]);



        $serializer = new Serializer([new ObjectNormalizer()]);

        $formatted = $serializer->normalize($notes);

        return new JsonResponse($formatted);
    }

    public function getNoteAction(Request $request)
    {
        $date = $request->get('date');
        $newDate = date_create_from_format("Y-n-d", $date);

        $note = $this->getDoctrine()->getManager()
            ->getRepository('JardinBundle:Note')
            ->findOneBy(["dateN" => $newDate,"jardinId" => $request->get('jardin')]);

        $mynote = [
            'id'=>$note->getId(),
            'contenu'=>$note->getContenu(),
            'date'=>$note->getDateN()->format('Y-m-d'),
            'jardin_id'=>$note->getJardinId()->getId()

        ];

        $serializer = new Serializer([new ObjectNormalizer()]);

        $formatted = $serializer->normalize($mynote);

        return new JsonResponse($formatted);
    }

    public function updateNoteAction(Request $request)
    {
        $note = $this->getDoctrine()->getManager()
            ->getRepository('JardinBundle:Note')
            ->find($request->get('id'));

        $note->setContenu($request->get('contenu'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($note);
        $em->flush();

        $mynote = [
            'id'=>$note->getId(),
            'contenu'=>$note->getContenu(),
            'date'=>$note->getDateN()->format('Y-m-d'),
            'jardin_id'=>$note->getJardinId()->getId()

        ];

        $serializer = new Serializer([new ObjectNormalizer()]);

        $formatted = $serializer->normalize($mynote);

        return new JsonResponse($formatted);
    }

    public function deleteNoteAction(Request $request)
    {
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $note = $em->getRepository('JardinBundle:Note')->find($id);
        $em->remove($note);
        $em->flush();

        $mynote = [
            'id'=>$note->getId(),
            'contenu'=>$note->getContenu(),
            'date'=>$note->getDateN()->format('Y-m-d'),
            'jardin_id'=>$note->getJardinId()->getId()

        ];

        $serializer = new Serializer([new ObjectNormalizer()]);

        $formatted = $serializer->normalize($mynote);

        return new JsonResponse($formatted);
    }
}
