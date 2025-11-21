<?php

namespace App\Controller\Admin; 

use App\Entity\Education;
use App\Form\EducationType;   
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;   
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/education')]
class EducationController extends AbstractController
{
    #[Route('', name: 'admin_education')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $educations = $em->getRepository(Education::class)
        ->createQueryBuilder('e')
        ->addSelect('(CASE WHEN e.yearEnd IS NULL THEN 1 ELSE 0 END) AS HIDDEN inProgress')
        ->orderBy('inProgress', 'DESC')
        ->addOrderBy('e.yearEnd', 'DESC')
        ->addOrderBy('e.yearStart', 'DESC')
        ->getQuery()
        ->getResult();

        return $this->render('admin/education/listEducation.html.twig', [
            'educations' => $educations,
            'user' => $user
        ]);
    }

    #[Route('/create', name: 'admin_education_create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createEducation(Request $request, EntityManagerInterface $em): Response
    {
        $newEducation = new Education();
        $form = $this->createForm(EducationType::class, $newEducation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($newEducation);
            $em->flush();

            $this->addFlash('success', 'Diplôme créé avec succès.');

            return $this->redirectToRoute('admin_education');
        }

        return $this->render('admin/education/addEducation.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/{id}/edit', name: 'admin_education_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]  
    public function editEducation(Request $request, EntityManagerInterface $em, Education $education): Response
    {
        $education = $em->getRepository(Education::class)->find($education->getId());
        if (!$education) {
            throw $this->createNotFoundException('Diplôme non trouvé.');
        }

        $form = $this->createForm(EducationType::class, $education);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', sprintf(
                'La compétence "%s" a été mise à jour avec succès.',
                $education->getTitle()
            ));
            return $this->redirectToRoute('admin_education');
        }
        return $this->render('admin/education/editEducation.html.twig', [
            'form' => $form->createView(),
            'education' => $education
        ]);       
    }

    #[Route('/{id}/delete', name: 'admin_education_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteEducation(Request $request, EntityManagerInterface $em, Education $education
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $education->getId(), $request->request->get('_token'))) {
            $em->remove($education);
            $em->flush();

            $this->addFlash('success', 'Diplôme supprimé avec succès.');
        }

        return $this->redirectToRoute('admin_education');
    }
}       