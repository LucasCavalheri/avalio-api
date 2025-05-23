<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessResource;
use App\Models\Business;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

#[Group('Business')]
class GetBusinessController extends Controller
{
    /**
     * Rota para buscar um negócio
     *
     * @unauthenticated
     */
    public function __invoke(Request $request, string $id)
    {
        $business = Business::find($id);

        if (!$business) {
            return $this->error('Negócio não encontrado', Response::HTTP_NOT_FOUND);
        }

        return $this->success('Negócio encontrado com sucesso', Response::HTTP_OK, BusinessResource::make($business));
    }
}
