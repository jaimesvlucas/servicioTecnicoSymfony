<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cliente;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Entity\Incidencia;
use App\Entity\Usuario;
use App\Entity\LineasIncidencia;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IncidenciasController extends AbstractController
{
    /**
     * @Route("/insertar/incidencia/{id}", name="insertar_incidencia", requirements={"id"="\d+"})
     */
    public function registrar(Request $request, Cliente $cliente): Response{
        $incidencia = new Incidencia();
        $form=$this->createFormBuilder($incidencia)
                ->add('titulo', TextType::class)
                ->add('fecha_creacion', DateTimeType::class)
                ->add('estado', ChoiceType::class,[
                    'choices' => [
                        'iniciada' => 'iniciada',
                        'resuelta' => 'resuelta',
                        'en proceso'=> 'proceso']])
                ->add('enviar', SubmitType::class, ['label'=>'Insertar incidencia'])
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $incidencia = $form->getData();
            $incidencia->setCliente($cliente);
            $incidencia->setUsuario($this->getUser());
            $em=$this->getDoctrine()->getManager();
            $em->persist($incidencia);
            $em->flush();
            $repositorio=$this->getDoctrine()->getRepository(Incidencia::class);
            $incidencias = $repositorio->findByIdCliente($cliente->getId());
            return $this->render('clientes/ver_cliente.html.twig', ['cliente' => $cliente, 'incidencias'=>$incidencias]);
        }
         return $this->render('incidencias/insertar_incidencia.html.twig', ['formulario'=>$form->createView()]);
    }
    
    /**
     * @Route("/incidencias/borrar/{id}", name="borrar_incidencia",requirements={"id"="\d+"})
     */
    public function borrar(Incidencia $incidencia): Response{
        $cliente = $incidencia->getCliente();
        $repositorio=$this->getDoctrine()->getRepository(Incidencia::class);
        $repositorio->removeLineasIncidencias($incidencia);
        $em=$this->getDoctrine()->getManager();
        $em->remove($incidencia);
        $em->flush();
        $incidencias = $repositorio->findByIdCliente($cliente->getId());
        return $this->render('clientes/ver_cliente.html.twig', ['cliente' => $cliente, 'incidencias'=>$incidencias]);
    }
    
    /**
     * @Route("/incidencias/{id}", name="ver_incidencia", requirements={"id"="\d+"})
     * @param int $id
     */
    public function ver(Incidencia $incidencia){
        $repositorio=$this->getDoctrine()->getRepository(LineasIncidencia::class);
        $lineasIncidencias = $repositorio->findByIdincidencia($incidencia->getId());
        return $this->render('incidencias/ver_incidencia.html.twig', ['incidencia' => $incidencia, 'lineasIncidencias'=>$lineasIncidencias]);
    }
}
