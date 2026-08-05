<?php

namespace App\Controller\Admin;

use App\Entity\Profile;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/profile')]
#[IsGranted('ROLE_ADMIN')]
class ProfileController extends AbstractController
{
    #[Route('', name: 'admin_profile', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        // Singleton : on récupère la ligne existante ou on en crée une.
        $profile = $em->getRepository(Profile::class)->getSingleton() ?? new Profile();

        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $cvUpload */
            $cvUpload = $form->get('cvFileUpload')->getData();

            if ($cvUpload instanceof UploadedFile) {
                $originalName = pathinfo($cvUpload->getClientOriginalName(), PATHINFO_FILENAME);
                $safeName = $slugger->slug($originalName)->lower();
                $newFilename = $safeName . '-' . uniqid() . '.' . $cvUpload->guessExtension();

                $targetDir = $this->getParameter('cv_directory');

                try {
                    $cvUpload->move($targetDir, $newFilename);

                    // Suppression de l'ancien CV si présent.
                    $old = $profile->getCvFile();
                    if ($old && is_file($targetDir . '/' . $old)) {
                        @unlink($targetDir . '/' . $old);
                    }

                    $profile->setCvFile($newFilename);
                } catch (FileException) {
                    $this->addFlash('danger', "Échec de l'upload du CV.");

                    return $this->redirectToRoute('admin_profile');
                }
            }

            $profile->setUpdatedAt(new \DateTimeImmutable());

            if (!$profile->getId()) {
                $em->persist($profile);
            }
            $em->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès.');

            return $this->redirectToRoute('admin_profile');
        }

        return $this->render('admin/profile/edit.html.twig', [
            'form' => $form->createView(),
            'profile' => $profile,
        ]);
    }
}
