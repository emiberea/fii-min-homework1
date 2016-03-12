<?php

namespace MIN\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $computeService = $this->get('min_app.service.compute');
        $functionService = $this->get('min_app.service.function');

//        $rastrigin = $functionService->rastrigin(1);
//        $rastrigin = $functionService->rastriginHillClimbing(10, 1);
//        $griewangk = $functionService->griewangkHillClimbing(10, 1);
        $rosenbrock = $functionService->rosenbrockHillClimbing(10, 1);
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
        return $this->render('MINAppBundle:Default:index.html.twig');
    }
}
