<?php

namespace MIP\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $computeService = $this->get('mip_app.service.compute');
        $functionService = $this->get('mip_app.service.function');

        $rastrigin = $functionService->rastrigin(1);
        die;
//        $N = $computeService->generateN(-600, 600, 1);
//        $byteArr = [];
//        $n = 10;
//        for ($i = 0; $i < $n; $i++) {
//            $byteArr[] = $computeService->generateBitString($N);
//        }
////
//        var_dump($byteArr);
//        var_dump($N);
//        var_dump(ceil($N));
//        var_dump(PHP_INT_MAX);
//        foreach ($byteArr as $byte) {
//            var_dump(bindec($byte));
//        }
//        die;
        return $this->render('MIPAppBundle:Default:index.html.twig');
    }
}
