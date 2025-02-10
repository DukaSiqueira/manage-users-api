<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API de Usuários",
 *      description="API para gerenciamento de usuários",
 *      @OA\Contact(email="suporte@meusite.com")
 * )
 *
 * @OA\Tag(
 *     name="Users",
 *     description="Gerenciamento de usuários"
 * )
 *
 * @OA\Components(
 *     @OA\Schema(
 *         schema="User",
 *         title="Usuário",
 *         description="Modelo de um usuário",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="João Silva"),
 *         @OA\Property(property="email", type="string", format="email", example="joao@email.com"),
 *         @OA\Property(property="created_at", type="string", format="date-time"),
 *         @OA\Property(property="updated_at", type="string", format="date-time")
 *     )
 * )
 */
class UserController extends Controller
{
    /**
     * Lista os usuários paginados.
     *
     * @OA\Get(
     *     path="/api/users",
     *     summary="Listar usuários",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários paginada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", example="http://seu-api.test/api/users?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://seu-api.test/api/users?page=5"),
     *                 @OA\Property(property="prev", type="string", example=null),
     *                 @OA\Property(property="next", type="string", example="http://seu-api.test/api/users?page=2")
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="path", type="string", example="http://seu-api.test/api/users"),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="to", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=50)
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $users = User::paginate(10);
        return response()->json($users);
    }

    /**
     * Cria um novo usuário.
     *
     * @OA\Post(
     *     path="/api/users",
     *     summary="Criar usuário",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="email", type="string", format="email", example="joao@email.com"),
     *             @OA\Property(property="password", type="string", format="password", example="123456"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            "message" => "User created successfully",
            "data" => $user
        ], Response::HTTP_CREATED);
    }

    /**
     * Exibe um usuário específico.
     *
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Obter um usuário",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json(["data" => $user], Response::HTTP_OK);
    }

    /**
     * Atualiza um usuário existente.
     *
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Atualizar usuário",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="email", type="string", format="email", example="joao@email.com"),
     *             @OA\Property(property="password", type="string", format="password", example="nova_senha"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $data = $request->only(['name', 'email']);
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        return response()->json("User updated successfully", Response::HTTP_OK);
    }

    /**
     * Exclui um usuário.
     *
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Deletar usuário",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário deletado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    
        return response()->json(["message" => "User deleted successfully"], Response::HTTP_OK);
    }
}


