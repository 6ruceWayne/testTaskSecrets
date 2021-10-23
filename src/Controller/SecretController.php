<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Secret;
use App\Form\SecretFormType;
use App\Form\SecretRevealType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use SpecShaper\EncryptBundle\Encryptors\EncryptorInterface;

class SecretController extends AbstractController
{
    private $encryptor;
    // Inject the Encryptor from the service container at class construction
    public function __construct(EncryptorInterface $encryptor)
    {
        $this->encryptor = $encryptor;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(SecretFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $secret = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($secret);
            $entityManager->flush();

            $this->addFlash('success', 'Success! Your link is: ' . $this->generateUrl(
                'reveal_secret',
                array('id'=>$secret->getId()),
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        
            return $this->render('index.html.twig', [
                'secretForm' => $this->createForm(SecretFormType::class)->createView(),
            ]);
        }

        //return new Response((string)$form->createView());
        return $this->render('index.html.twig', [
            'secretForm' => $form->createView(),
        ]);
    }



    /**
     * @Route("/secret/{id}", name="reveal_secret")
     */
    public function show(Request $request, $id)
    {
        $defaultData = ['message' => 'Type your passphrase here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('passphrase', TextType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $passphrase = $form->getData()['passphrase'];
            $entityManager = $this->getDoctrine()->getManager();
            $secret = $entityManager->getRepository(Secret::class)->find($id);
            if (!$secret || $secret->getId() != $id || $passphrase != $this->encryptor->decrypt($secret->getPassphrase())) {
                $this->addFlash(
                    'error',
                    'Ooops, something is wrong, check your link or passphrase',
                    array()
                );
                return $this->render('show.html.twig', [
                    'revealForm' => $form->createView(),
                ]);
            }
            $entityManager->remove($secret);
            $entityManager->flush();
            return $this->render('show.html.twig', [
                    'secret' => $secret,
                    ]);
        }
        return $this->render('show.html.twig', [
            'revealForm' => $form->createView(),
        ]);
    }
}
