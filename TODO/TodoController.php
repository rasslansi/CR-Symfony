<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->has('todos')){
            $todos = [
                'achat'=>'acheter clé usb',
                'cours'=>'Finaliser mon cours',
                'correction'=>'corriger mes examens'
            ];
            $session->set('todos',$todos);
            $this->addFlash('info','La liste de TODO vient d etre initialisée');
        }
        return $this->render('todo/index.html.twig');
    }
    #[Route('/todo/add/{name}/{content}',name: 'todo.add')]
    public function addTodo(Request $request,$name,$content): RedirectResponse
    {
        $session = $request->getSession();
        if($session->has('todos')){
            $todos = $session->get('todos');
            if (isset($todos[$name])){
                $this->addFlash('error','le todo existe deja');
            } else{
                $todos[$name]=$content;
                $session->set('todos',$todos);
                $this->addFlash('succes','le todo a été ajouté avec succès');
            }
        }else{
            $this->addFlash('error','La liste de TODO n est pas encore initialisée');
        }

        return $this->redirectToRoute('app_todo');
    }
    #[Route('/todo/delete/{name}',name: 'todo.delete')]
    public function deleteToDo(Request $request,$name):RedirectResponse
    {
        $session = $request->getSession();
        if (!$session->has('todos')){
            $this->addFlash('error','La liste de TODO n est pas encore initialisée');
        }else{
            $todos = $session->get('todos');
            if (isset($todos[$name])){
                unset($todos[$name]);
                $session->set('todos',$todos);
                $this->addFlash('succes','Le TODO a été supprimé avec succès');
            } else{
                $this->addFlash('error',"Le TODO que vous voulez supprimer n'existe pas !");
            }
        }
        return $this->redirectToRoute('app_todo');
    }
    #[Route('/todo/reset',name: 'todo.reset')]
    public function resetTodo(Request $request):RedirectResponse
    {
        $session = $request->getSession();
        $session->remove('todos');
        return $this->redirectToRoute('app_todo');
    }


}
