<?php

namespace App\Controller\Admin;

use App\Entity\Skill;
use App\Form\SkillType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/skills')]
class SkillsController extends AbstractController
{
    #[Route('', name: 'admin_skills')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $skills = $em->getRepository(Skill::class)->findAll();

        return $this->render('admin/skills/listSkills.html.twig', [
            'skills' => $skills,
            'user' => $user
        ]);
    }

    #[Route('/create', name: 'admin_skills_create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createSkills(Request $request, EntityManagerInterface $em): Response
    {
        $newSkill = new Skill();
        $form = $this->createForm(SkillType::class, $newSkill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($newSkill);
            $em->flush();

            $this->addFlash('success', 'Compétence créée avec succès.');

            return $this->redirectToRoute('admin_skills');
        }

        return $this->render('admin/skills/addSkills.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_skills_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function editSkills(Request $request, EntityManagerInterface $em, Skill $skill): Response
    {
        $skill = $em->getRepository(Skill::class)->find($skill->getId());
        if (!$skill) {
            throw $this->createNotFoundException('Compétence non trouvée.');
        }

        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', sprintf(
                'La compétence "%s" a été mise à jour avec succès.',
                $skill->getName()
            ));

            return $this->redirectToRoute('admin_skills');
        }

        return $this->render('admin/skills/editSkills.html.twig', [
            'skill' => $skill,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_skills_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteSkills(Request $request, EntityManagerInterface $em, Skill $skill): Response
    {
        if ($this->isCsrfTokenValid('delete' . $skill->getId(), $request->request->get('_token'))) {
            $em->remove($skill);
            $em->flush();

            $this->addFlash('success', 'Compétence supprimée avec succès.');
        }

        return $this->redirectToRoute('admin_skills');
    }
}
