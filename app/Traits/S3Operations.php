<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use FFMpeg;

trait S3Operations {
    // Função para deletar arquivos
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

    // // Compactar imagem
    // private function compressImage($file) {
    //     $image = Image::make($file)->encode('jpg', 75); // Compactação 75%
    //     $tempPath = tempnam(sys_get_temp_dir(), 'image');
    //     $image->save($tempPath);
    //     return $tempPath;
    // }

    // // Compactar áudio ou vídeo
    // private function compressMedia($file, $type) {
    //     $tempPath = tempnam(sys_get_temp_dir(), $type);
    //     $ffmpeg = FFMpeg\FFMpeg::create();
    //     $media = $ffmpeg->open($file);
        
    //     // Compactação simples (ajuste conforme necessário)
    //     $format = ($type === 'audio')
    //         ? new FFMpeg\Format\Audio\Mp3()
    //         : new FFMpeg\Format\Video\X264('aac', 'libx264');
        
    //     $format->setAudioKiloBitrate(128); // Ajuste o bitrate
    //     $format->setKiloBitrate(1000); // Bitrate para vídeo
        
    //     $media->save($format, $tempPath);
    //     return $tempPath;
    // }

    public function storeProfilePhoto(Request $request) {
        if($request->hasFile('profile_photo')) {
            return $request->file('profile_photo')->store('beatflow/profile_photos', 's3');
        }
    }

    public function updateProfilePhoto(Request $request) {
        if($request->hasFile('profile_photo')) {
            $this->deleteFile($request->oldProfilePhoto);
            return $this->storeProfilePhoto($request);
        } else if(isset($request['oldProfilePhoto'])) {
            return $request->oldProfilePhoto;
        }
    }

    public function storePostMedia(Request $request) {
        if($request->hasFile('media_path')) {
            return $request->file('media_path')->store('beatflow/post_media', 's3');
        }
    }

    public function updatePostMedia(Request $request) {
        if($request->hasFile('media_path')) {
            $this->deleteFile($request->oldMedia);
            return $this->storePostMedia($request);
        } else if(isset($request['oldMedia'])) {
            return $request->oldMedia;
        }
    }

    // public function storePostMedia(Request $request) {
    //     if ($request->hasFile('media_path')) {
    //         $file = $request->file('media_path');
    //         $tempPath = null;
            
    //         switch($request->media_type) {
    //             case 'image':
    //                 $tempPath = $this->compressImage($file);
    //                 break;
    //             case 'video':
    //                 $tempPath = $this->compressMedia($file, 'video');
    //                 break;
    //             case 'audio':
    //                 $tempPath = $this->compressMedia($file, 'audio');
    //                 break;
    //             default:
    //                 // Caso não for informado o tipo
    //                 return response()->json(['error' => 'Invalid media type'], 400);
    //         }

    //         // Armazenar o arquivo temporário no S3
    //         $path = Storage::disk('s3')->putFile('beatflow/post_media', new \Illuminate\Http\File($tempPath));

    //         // Deletar o arquivo temporário após o upload
    //         unlink($tempPath);

    //         return $path;
    //     }

    //     return response()->json(['error' => 'No media file uploaded'], 400);
    // }

    // // Atualizando e armazenando mídia do post na S3 com compactação
    // public function updatePostMedia(Request $request) {
    //     if ($request->hasFile('media_path')) {
    //         // Validando request e deletando mídia antiga do S3
    //         $this->deleteFile($request->oldMedia);
    //         return $this->storePostMedia($request);
    //     } elseif (isset($request['oldMedia'])) {
    //         // Caso request não passe na validação continuará com a mídia antiga
    //         return $request->oldMedia;
    //     }
    // }
}
