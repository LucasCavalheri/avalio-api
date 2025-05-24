<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessResource;
use App\Models\Business;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

#[Group('Business')]
class GetUserBusinessesController extends Controller
{
    /**
     * Rota para buscar os negócios do usuário
     */
    public function __invoke(Request $request)
    {
        $businesses = Business::where('user_id', Auth::id())->with(['reviews'])->get();

        return $this->success('Negócios encontrados com sucesso', Response::HTTP_OK, BusinessResource::collection($businesses));
    }
}
