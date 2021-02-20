<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Entity\Incidencia;
use App\Entity\Usuario;
use App\Entity\LineasIncidencia;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LineasIncidenciaController extends AbstractController
{
    /**
     * @Route("/lineas_incidencia/insertar/{id}", name="insertar_lineas_incidencia",requirements={"id"="\d+"})
     */
    public function registrar(Request $request, Incidencia $incidencia): Response
    {
        $lineaIncidencia = new LineasIncidencia();
        $form=$this->createFormBuilder($lineaIncidencia)
                ->add('texto', TextType::class)
                ->add('fecha_creacion', DateTimeType::class)
                ->add('enviar', SubmitType::class, ['label'=>'Insertar incidencia'])
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $lineaIncidencia = $form->getData();
            $lineaIncidencia->setIncidencia($incidencia);
            $em=$this->getDoctrine()->getManager();
            $em->persist($lineaIncidencia);
            $em->flush();
            $repositorio=$this->getDoctrine()->getRepository(LineasIncidencia::class);
            $lineasIncidencias = $repositorio->findByIdincidencia($incidencia->getId());
            return $this->render('incidencias/ver_incidencia.html.twig', ['incidencia' => $incidencia, 'lineasIncidencias'=>$lineasIncidencias]);
        }
         return $this->render('lineas_incidencia/insertar_lineaIncidencia.html.twig', ['formulario'=>$form->createView()]);
    }
    /**
     * @Route("/lineas_incidencia/borrar/{id}", name="borrar_lineas_incidencia",requirements={"id"="\d+"})
     */
    public function borrar(LineasIncidencia $lineaIncidencia): Response{
        $incidencia = $lineaIncidencia->getIncidencia();
        $em=$this->getDoctrine()->getManager();
        $em->remove($lineaIncidencia);
        $em->flush();
        $repositorio=$this->getDoctrine()->getRepository(LineasIncidencia::class);
        $lineasIncidencias = $repositorio->findByIdincidencia($incidencia->getId());
        return $this->render('incidencias/ver_incidencia.html.twig', ['incidencia' => $incidencia, 'lineasIncidencias'=>$lineasIncidencias]);
    }
    /**
     * @Route("/lineas_incidencia/editar/{id}", name="editar_lineas_incidencia",requirements={"id"="\d+"})
     */
    public function editar(Request $request, LineasIncidencia $lineaIncidencia): Response
    {
        $form=$this->createFormBuilder($lineaIncidencia)
                ->add('texto', TextType::class, [
                    'data'=> $lineaIncidencia->getTexto()
                ])
                ->add('fecha_creacion', DateTimeType::class, [
                    'data'=>$lineaIncidencia->getFechaCreacion()
                ])
                ->add('enviar', SubmitType::class, ['label'=>'Editar incidencia'])
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $lineaIncidencia = $form->getData();
            $em=$this->getDoctrine()->getManager();
            $em->persist($lineaIncidencia);
            $em->flush();
            $repositorio=$this->getDoctrine()->getRepository(LineasIncidencia::class);
            $lineasIncidencias = $repositorio->findByIdincidencia($lineaIncidencia->getIncidencia()->getId());
            return $this->render('incidencias/ver_incidencia.html.twig', ['incidencia' => $lineaIncidencia->getIncidencia(), 'lineasIncidencias'=>$lineasIncidencias]);
        }
         return $this->render('lineas_incidencia/insertar_lineaIncidencia.html.twig', ['formulario'=>$form->createView()]);
    }
}
