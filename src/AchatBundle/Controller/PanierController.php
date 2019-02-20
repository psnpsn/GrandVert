<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 19/02/2019
 * Time: 14:25
 */

namespace AchatBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class PanierController extends Controller
{
    public function conAction(Request $request)
    {
        $session = new Session();
        $panier=$session->get('panier');
        return $this->render('@Achat/panier/panier.html.twig',array("panier"=>$panier));
    }
    public function delAction(Request $request)
    {$test=false;
        $id=$request->get('id');
        $session = new Session();
        $panier=$session->get('panier');
        $i=0;
        foreach ($panier as $plant){
            if($id==$plant->getId())array_splice($panier, $i, 1);
            $i=$i+1;
        }
        $session->set('panier',$panier);
        return $this->render('@Achat/panier/panier.html.twig',array("panier"=>$panier));
    }
}