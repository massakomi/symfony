<?php


namespace App\Controller\Admin;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepositoryInterface;
use App\Service\User\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends BaseController
{

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserRepositoryInterface $userRepository,
                                UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    /**
     * @Route("/admin/user", name="admin_user")
     * @return Response
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Пользователи';
        $forRender['users'] = $this->userRepository->getAll();
        $forRender['alias'] = 'user';
        return $this->render('admin/user/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/user/create", name="admin_user_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        return $this->updateAction($request, 0);
    }

    /**
     * @Route("/admin/user/update/{userId}", name="admin_user_update")
     * @param Request $request
     * @param int $userId
     * @return RedirectResponse|Response
     */
    public function updateActionx(Request $request, int $userId)
    {
        if ($userId) {
            $user = $this->userRepository->getOne($userId);
        } else {
            $user = new User();
        }

        $formUser = $this->createForm(UserType::class, $user);
        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()){
            $user->setPlainPassword('');
            if ($formUser->get('plainPassword')->getData()) {
                $user->setPlainPassword($formUser->get('plainPassword')->getData());
            }
            if ($userId) {
                $this->userService->handleUpdate($user);
            } else {
                $this->userService->handleCreate($user);
            }
            $this->addFlash('success', $userId ? 'Изменения сохранены!' : 'Пользователь создан!');
            return $this->redirectToRoute('admin_user');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = $userId ? 'Изменение' : 'Создание';
        $forRender['form'] = $formUser->createView();
        return $this->render('admin/form.html.twig', $forRender);
    }


}
