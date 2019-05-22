<?php

namespace WebApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder;

class UserController extends Controller
{
    public function checkPasswordAction(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $response = new Response();
        $response->headers->set("Content-Type","text/plain");

        $user_manager = $this->get('fos_user.user_manager');
        $factory = $this->get('security.encoder_factory');

        $user = $user_manager->findUserByUsernameOrEmail($username);

        if($user == null){
            $response->setContent("Incorrect");
            return $response;
        }

        $encoder = $factory->getEncoder($user);
        $encodedPw = $encoder->EncodePassword($password, $user->getSalt());

        if($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())){
            $response->setContent("OK");
        }else {
            $response->setContent("FALSE");
        }

        return $response;
    }

}
