<?php

namespace App\Controller\User;

use App\Boundary\Input\User\UserRequest;
use App\Boundary\Output\FormatInterface;
use App\Boundary\Output\User\UserResponse;
use App\UseCase\User\PostUserUseCase;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PostUserController extends AbstractController
{
    public function __construct(private PostUserUseCase $useCase, private SerializerInterface $serializer)
    {
    }

    /**
     * @OA\Tag(name="User")
     * @OA\RequestBody(description="Post user", @Model(type=UserRequest::class))
     * @OA\Response(response="200", description="Post user", @Model(type=UserResponse::class))
     */
    #[Route('/api/user', name: 'post_user', methods: ['POST'], format: FormatInterface::JSON)]
    public function __invoke(Request $request): Response
    {
        $request = $this->serializer->deserialize($request->getContent(), UserRequest::class, FormatInterface::JSON);
        $response = new UserResponse();

        $this->useCase->execute($request, $response);

        return $this->json($response->getResult());
    }
}
