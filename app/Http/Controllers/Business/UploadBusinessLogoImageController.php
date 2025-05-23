<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessResource;
use App\Models\Business;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

#[Group('Business')]
class UploadBusinessLogoImageController extends Controller
{
    /**
     * Rota para upload de imagem de logo do negócio
     */
    public function __invoke(Request $request, string $businessId)
    {
        $request->validate([
            'logo_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $business = Business::find($businessId);

        if (!$business) {
            return $this->error('Negócio não encontrado', Response::HTTP_NOT_FOUND);
        }

        if ($business->user_id !== Auth::id()) {
            return $this->error('Você não tem permissão para atualizar a imagem do banner do negócio', Response::HTTP_FORBIDDEN);
        }

        if ($request->hasFile('logo_image')) {
            $file = $request->file('logo_image');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = 'businesses/' . $business->id . '/logo-images/' . $fileName;

            $disk = config('app.env') === 'local' ? 'public' : 's3';

            $uploaded = Storage::disk($disk)->put($filePath, file_get_contents($file), 'public');

            if (!$uploaded) {
                return $this->error('Falha no upload da imagem', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($business->logo_image && Storage::disk($disk)->exists($business->logo_image)) {
                Storage::disk($disk)->delete($business->logo_image);
            }

            $business->update(['logo_image' => $filePath]);

            return $this->success('Imagem de capa do negócio atualizada com sucesso', Response::HTTP_OK, BusinessResource::make($business));
        }

        return $this->error('Falha no upload da imagem', Response::HTTP_BAD_REQUEST);
    }
}
