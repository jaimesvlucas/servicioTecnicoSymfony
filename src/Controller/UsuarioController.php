<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Usuario;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UsuarioController extends AbstractController
{
    /**
     * @Route("/registrar", name="registrar")
     */
    public function registrar(Request $request, UserPasswordEncoderInterface $encoder, SluggerInterface $slugger): Response{
        $usuario = new Usuario(); 
        $form=$this->createFormBuilder($usuario)
                ->add('email', TextType::class)
                ->add('password', PasswordType::class)
                ->add('nombre', TextType::class)
                ->add('apellidos', TextType::class)
                ->add('tlf', TextType::class)
                ->add('foto', FileType::class, [
                    'label' => 'Foto (Img file)',

                    // unmapped means that this field is not associated to any entity property
                    'mapped' => false,

                    // make it optional so you don't have to re-upload the PDF file
                    // every time you edit the Product details
                    'required' => false,

                    // unmapped fields can't define their validation using annotations
                    // in the associated entity, so you can use the PHP constraint classes
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/png',
                                'image/gif',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid img document',
                        ])
                    ],
                ])
                ->add('registrar', SubmitType::class, ['label'=>'Registrar'])
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $form->getData();
            $foto = $form->get('foto')->getData();
            if($foto){
                $nombreOriginal =pathinfo($foto->getClientOriginalName(), PATHINFO_FILENAME);
                $guardarNombre = $slugger->slug($nombreOriginal);
                $nuevoNombre = $guardarNombre.'-'.uniqid().'.'.$foto->guessExtension();
                try {
                    $foto->move(
                        $this->getParameter('rutaImagenes'),
                        $nuevoNombre
                    );
                } catch (FileException $e) {

                }
            }
            $usuario->setFoto($nuevoNombre);
            //codificamos el password
            $usuario->setPassword($encoder->encodePassword($usuario, $usuario->getPassword()));      
            //guardamos el nuevo articulo en la base de datos
            $em=$this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();
            return $this->redirectToRoute('inicio');
        }
        return $this->render('usuario/registrar.html.twig', ['form' => $form->createView()]);
    }
}
