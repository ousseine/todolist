<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted("ROLE_USER")]
class TaskController extends AbstractController
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    #[Route('/', name: 'app_task_index', methods: ['GET', 'POST'])]
    public function index(TaskRepository $taskRepository, Request $request): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setSlug($this->slugger->slug($task->getName()));
            $task->setProfile($this->getUser());

            $taskRepository->save($task, true);

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findByDesc($this->getUser()),
            'tasksEnd' => $taskRepository->findBy([
                'profile' => $this->getUser(),
                'is_end' => true
            ]),
            'tasksImportant' => $taskRepository->findBy([
                'profile' => $this->getUser(),
                'is_important' => true,
                'is_end' => false
            ]),
            'tasksTodo' => $taskRepository->findBy([
                'profile' => $this->getUser(),
                'is_todo' => true,
                'is_end' => false
            ]),
            'form' => $form,
            'task' => $task,
        ]);
    }

    #[Route('/task/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Task $task, Request $request, TaskRepository $taskRepository): Response
    {
        if ($this->isCsrfTokenValid('edit'.$task->getId(), $request->request->get('_token'))) {
            $task->setName($request->request->get('task'));
            $task->setSlug($this->slugger->slug($task->getName()));

            $taskRepository->save($task, true);
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/task/{id}/end', name: 'app_task_end', methods: ['POST', 'GET'])]
    public function end(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        if ($this->isCsrfTokenValid('end'. $task->getId(), $request->request->get('_token'))) {
            if ($task->isIsEnd() === false) {
                $task->setIsEnd(true);
            } else {
                $task->setIsEnd(false);
            }
            $task->setUpdatedAt(new DateTime());

            $taskRepository->save($task, true);
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/task/{id}/important', name: 'app_task_important', methods: ['POST'])]
    public function important(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        if ($this->isCsrfTokenValid('important'. $task->getId(), $request->request->get('_token'))) {
            if ($task->isIsImportant() === false) {
                $task->setIsImportant(true);
            } else {
                $task->setIsImportant(false);
            }
            $task->setUpdatedAt(new DateTime());

            $taskRepository->save($task, true);
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/task/{id}/todo', name: 'app_task_todo', methods: ['POST', 'GET'])]
    public function todo(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        if ($this->isCsrfTokenValid('todo'. $task->getId(), $request->request->get('_token'))) {
            if ($task->isIsTodo() === false) {
                $task->setIsTodo(true);
            } else {
                $task->setIsTodo(false);
            }
            $task->setUpdatedAt(new DateTime());

            $taskRepository->save($task, true);
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/task/{id}/delete', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $taskRepository->remove($task, true);
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }
}
