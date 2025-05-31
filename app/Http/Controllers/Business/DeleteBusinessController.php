<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

#[Group('Business')]
class DeleteBusinessController extends Controller
{
    /**
     * Rota para deletar um negócio
     */
    public function __invoke(Request $request, string $id)
    {
        $business = Business::find($id);

        if (! $business) {
            return $this->error('Negócio não encontrado', Response::HTTP_NOT_FOUND);
        }

        if ($business->user_id !== Auth::id()) {
            return $this->error('Você não tem permissão para deletar este negócio', Response::HTTP_FORBIDDEN);
        }

        $business->delete();

        return $this->success('Negócio deletado com sucesso', Response::HTTP_OK);
    }
}
