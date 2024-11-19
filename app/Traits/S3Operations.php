<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Carbon\Carbon;

trait S3Operations {
    //Função para deletar arquivos
    public function deleteFile($file): void {
        if ($file != null) {
            // Lista de diretórios protegidos
            $protectedDirectories = [
                'beatflow/placeholder/'
            ];

            // Verifica se o arquivo está em um dos diretórios protegidos
            foreach ($protectedDirectories as $protectedDirectory) {
                if (str_contains($file, $protectedDirectory)) {
                    return; // Não deletar se estiver em um diretório protegido
                }
            }

            // Verifica se o arquivo existe e deleta
            if (Storage::disk('s3')->exists($file)) {
                Storage::disk('s3')->delete($file);
            }
        }
    }

    // Armazenando foto de perfil na S3
    public function storeProfilePhoto(Request $request){
        if($request->hasFile('profile_photo')){
            // caso request não passe na validação continuará com a imagem antiga
            return $request->file('profile_photo')->store('beatflow/profile_photos', 's3');
        }
    }

    // Atualizando e armazenando foto de perfil na S3
    public function updateProfilePhoto(Request $request){
        if($request->hasFile('profile_photo')){
            // validando request e deletando foto antiga do S3
            $this->deleteFile($request->oldProfilePhoto);
            return $request->file('profile_photo')->store('beatflow/profile_photos', 's3');
        } else if(isset($request['oldProfilePhoto'])){
            // caso request não passe na validação continuará com a imagem antiga
            return $request->oldProfilePhoto;
        }
    }

    // Armazenando media do post na S3
    public function storePostMedia(Request $request){
        if($request->hasFile('media_path')){
            // caso request não passe na validação continuará com a imagem antiga
            return $request->file('media_path')->store('beatflow/post_media', 's3');
        }
    }

    // Atualizando e armazenando media do post na S3
    public function updatePostMedia(Request $request){
        if($request->hasFile('media_path')){
            // validando request e deletando foto antiga do S3
            $this->deleteFile($request->oldMedia);
            return $request->file('media_path')->store('beatflow/post_media', 's3');
        } else if(isset($request['oldMedia'])){
            // caso request não passe na validação continuará com a imagem antiga
            return $request->oldMedia;
        }
    }
}
