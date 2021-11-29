<?php

namespace App\Http\Controllers\Services;

use Intervention\Image\ImageManager;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client as GuzzleClient;
use Hash;
use App;
use Log;

Trait FileManipulationTrait
{
    function removeS3($url) {

        $rm_link = str_replace("https://s3.amazonaws.com/gree-app.com.br/","", $url);
        return Storage::disk('s3')->delete($rm_link);
    }

    function uploadS3($seq = 1, $img, $request, $dimension = 800, $whitFit = false, $ext = '') {
	
		$file = $img;
		$extension = $ext == '' ? $img->getClientOriginalExtension() : $ext;
		if ($extension != 'php' and $extension != 'html' and $extension != 'exe' and $extension != 'sh') {

			$validator = Validator::make(
				[
					'file' => $file,
				],
				[
					'file' => 'required|max:50000',
				]
			);

			if ($validator->fails()) {

				$request->session()->put('error', "Tamanho máximo da imagem é de 50mb, diminua a resolução/tamanho da mesma.");
				return array('success' => false, 'url' => '');
			} else {

				$name_file = $seq .'-'. date('YmdHis') .'.'. $extension;
				if ($extension == 'jpg' or $extension == 'jpeg' or $extension == 'png' or $extension == 'gif' or $extension == 'webp') {

					try {
						$manager = new ImageManager;
						$image_resize = $manager->make($file->getRealPath());

						if ($whitFit)
							$image_resize->fit($dimension);

						// resize the image to a width of custom(px) and constrain aspect ratio (auto height)
						$image_resize->resize($dimension, null, function ($constraint) {
							$constraint->aspectRatio();
						})->encode($extension, 100);

						Storage::disk('s3')->put($name_file, $image_resize, [
							'mimetype' => $file->getClientMimeType()
						]);
					} catch (\Exception $e) {
						$request->session()->put('error', "Arquivo ilegível, error: ". $e->getMessage());
						return array('success' => false, 'url' => '');
					}
				} else {

					Storage::disk('s3')->put($name_file, file_get_contents($file), [
						'mimetype' => $file->getClientMimeType()
					]);

				}

				$url = Storage::disk('s3')->url($name_file);
				return array('success' => true, 'url' => $url);
			}

		} else {

			$request->session()->put('error', "o formato: (". $extension .") do arquivo não é suportado em nosso servidor.");
			return array('success' => false, 'url' => '');
		}
    }

    // Utilize o formato PNG para essa função, pois só ela tem alpha.
    public function roundedCornersAndUploadS3($seq = 1, $img = '', $url = '', $request) {

        $file = $img == '' ? $url : $img;
        if ($url) {
            $path_info = pathinfo($url);
            $extension = $path_info['extension'];
        } else {
            $extension = $img->extension();
        }

        $name_file = $seq .'-'. date('YmdHis') .'.'. $extension;
        $manager = new ImageManager;
        if ($url) {
            $image_resize = $manager->make($file);
        } else {
            $image_resize = $manager->make($file->getRealPath());
        }

        $width = $image_resize->getWidth();
        $height = $image_resize->getHeight();
        $mask = $manager->canvas($width, $height);

        // draw a white circle
        $mask->circle($width, $width/2, $height/2, function ($draw) {
            $draw->background('#FFFFFF');
        });

        $image_resize->mask($mask, false);


        // resize the image to a width of 50 and constrain aspect ratio (auto height)
        $image_resize->resize(50, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode($extension, 100);

        Storage::disk('s3')->put($name_file, $image_resize);


        $url = Storage::disk('s3')->url($name_file);
        return array('success' => true, 'url' => $url);

    }

    function uploadS3Base64($seq = 1, $img, $request, $dimension = 800, $whitFit = false, $ext = '') {

        $file = $img;
        $extension = $ext == '' ? $extension = explode('/', mime_content_type($file))[1] : $ext;
        if ($extension != 'php' and $extension != 'html' and $extension != 'exe' and $extension != 'sh') {

            $name_file = $seq .'-'. date('YmdHis') .'.'. $extension;
            $manager = new ImageManager;
            $image_resize = $manager->make($file);

            if ($whitFit)
                $image_resize->fit($dimension);

            // resize the image to a width of custom(px) and constrain aspect ratio (auto height)
            $image_resize->resize($dimension, null, function ($constraint) {
                $constraint->aspectRatio();
            })->encode($extension, 100);

            Storage::disk('s3')->put($name_file, $image_resize);

            $url = Storage::disk('s3')->url($name_file);
            return array('success' => true, 'url' => $url);

        } else {

            $request->session()->put('error', "o formato: (". $extension .") do arquivo não é suportado em nosso servidor.");
            return array('success' => false, 'url' => '');
        }

    }

    public function fileManagerSVR_file($file, $url, $method = 'POST') {

        $client = new GuzzleClient();
        try {
            $response = $client->request($method, 'https://filemanager.gree.com.br/'.$url, [
                'multipart' => [
                    [
                        'name'      => 'file',
                        'filename' => $file->getClientOriginalName(),
                        'Mime-Type'=> $file->getMimeType(),
                        'contents' => fopen($file->getPathname(), 'r'),
                    ],
                ],
            ]);

            return json_decode($response->getBody());

        } catch (\Exception $exception) {
            return (object) ['success' => false, 'msg' => $exception->getMessage()];
        }
    }

    public function fileManagerSVR($fields, $url, $method = 'POST') {

        $client = new GuzzleClient();
        try {
            $response = $client->request($method, 'https://filemanager.gree.com.br/'.$url, [
                'form_params' => $fields,
            ]);

            return json_decode($response->getBody());

        } catch (\Exception $exception) {
			$response = $exception->getResponse();
            return json_decode($response->getBody());
        }
    }

}
