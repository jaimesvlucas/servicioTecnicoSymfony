<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cliente;
use App\Entity\Incidencia;
use App\Entity\LineasIncidencia;
use App\Entity\Usuario;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClientesController extends AbstractController
{
    /**
     * @Route("/clientes", name="inicio")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $repositorio=$this->getDoctrine()->getRepository(Cliente::class);
        $clientes=$repositorio->findAll();
        return $this->render('clientes/index.html.twig', ['clientes' => $clientes ,'last_username' => $lastUsername, 'error' => $error]);
    }
     /**
     * @Route("/clientes/insertar", name="insertar_cliente")
     */
    public function insertar(Request $request): Response{
        $cliente = new Cliente();
        $form=$this->createFormBuilder($cliente)
                ->add('nombre', TextType::class)
                ->add('apellidos', TextType::class)
                ->add('tlf', TextType::class)
                ->add('direccion', TextType::class)
                ->add('enviar', SubmitType::class, ['label'=>'Insertar cliente'])
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cliente = $form->getData();
            $em=$this->getDoctrine()->getManager();
            $em->persist($cliente);
            $em->flush();
            return $this->redirectToRoute('inicio');
        }
         return $this->render('clientes/insertar_cliente.html.twig', ['formulario'=>$form->createView()]);
    }
    
    /**
     * @Route("/clientes/borrar/{id}", name="borrar_cliente",requirements={"id"="\d+"})
     */
    public function borrar(Cliente $cliente): Response{
        $repositorio=$this->getDoctrine()->getRepository(Incidencia::class);
        $incidencias = $repositorio->findByIdCliente($cliente->getId());
        foreach($incidencias as $i){
            $repositorio->removeLineasIncidencias($i);
        }
        $repositorioI=$this->getDoctrine()->getRepository(Cliente::class);
        $repositorioI->removeIncidencias($cliente);
        $em=$this->getDoctrine()->getManager();
        $em->remove($cliente);
        $em->flush();
        
        return $this->redirectToRoute('inicio');
    }
    /**
     * @Route("/clientes/{id}", name="ver_cliente", requirements={"id"="\d+"})
     * @param int $id
     */
    public function ver(Cliente $cliente){
        $repositorio=$this->getDoctrine()->getRepository(Incidencia::class);
        $incidencias = $repositorio->findByIdCliente($cliente->getId());
        return $this->render('clientes/ver_cliente.html.twig', ['cliente' => $cliente, 'incidencias'=>$incidencias]);
    }
}
