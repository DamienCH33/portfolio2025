<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\ProjectsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProjectsController extends AbstractController
{
    private function getUploadDir(): string
    {
        return $this->getParameter('kernel.project_dir') . '/public/image/portfolio_media';
    }

    #[Route('/admin/projects', name: 'admin_projects')]
    public function index(EntityManagerInterface $em): Response
    {
        $projects = $em->getRepository(Project::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/projects/listProjects.html.twig', [
            'projects' => $projects,
            'user' => $this->getUser()
        ]);
    }

    #[Route('/admin/projects/create', name: 'admin_projects_create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createProjects(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectsType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {

                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = (string) $slugger->slug($originalFilename);

                $extension = $imageFile->guessExtension() ?? 'bin';

                $newFilename = $safeFilename . '-' . uniqid() . '.' . $extension;

                try {
                    $imageFile->move(
                        $this->getUploadDir(),
                        $newFilename
                    );

                    $project->setImage($newFilename);
                } catch (FileException) {

                    $this->addFlash('danger', "Erreur lors de l'upload de l'image !");
                }
            }

            $em->persist($project);
            $em->flush();

            $this->addFlash('success', 'Projet ajouté avec succès !');

            return $this->redirectToRoute('admin_projects');
        }

        return $this->render('admin/projects/addProjects.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/projects/{id}/edit', name: 'admin_projects_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function editProjects(Request $request, EntityManagerInterface $em, Project $project, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProjectsType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {

                $oldImagePath = $this->getUploadDir() . '/' . $project->getImage();

                if ($project->getImage() && is_file($oldImagePath)) {
                    unlink($oldImagePath);
                }

                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = (string) $slugger->slug($originalFilename);

                $extension = $imageFile->guessExtension() ?? 'bin';

                $newFilename = $safeFilename . '-' . uniqid() . '.' . $extension;

                try {

                    $imageFile->move(
                        $this->getUploadDir(),
                        $newFilename
                    );

                    $project->setImage($newFilename);
                } catch (FileException) {

                    $this->addFlash('danger', "Erreur lors de l'upload de l'image !");
                }
            }

            $em->flush();

            $this->addFlash(
                'success',
                sprintf('Le projet "%s" a été mis à jour avec succès.', $project->getTitle())
            );

            return $this->redirectToRoute('admin_projects');
        }

        return $this->render('admin/projects/editProjects.html.twig', [
            'form' => $form->createView(),
            'project' => $project
        ]);
    }

    #[Route('/admin/projects/{id}/delete', name: 'admin_projects_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteProjects(Request $request, EntityManagerInterface $em, Project $project): Response
    {
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->request->get('_token'))) {

            $imagePath = $this->getUploadDir() . '/' . $project->getImage();

            if ($project->getImage() && is_file($imagePath)) {
                unlink($imagePath);
            }

            $em->remove($project);
            $em->flush();

            $this->addFlash('success', 'Projet supprimé avec succès.');
        }

        return $this->redirectToRoute('admin_projects');
    }

    #[Route('/media/{filename}', name: 'project_image')]
    public function projectImage(string $filename): Response
    {
        $path = $this->getUploadDir() . '/' . $filename;

        if (!is_file($path)) {
            throw $this->createNotFoundException();
        }

        return $this->file($path);
    }
}
