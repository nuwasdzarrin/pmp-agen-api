<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Routing\Controller as BaseController;
use League\Flysystem\FileNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StorageController extends BaseController
{
    /**
     * Invoke single action controller.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @var \Symfony\Component\HttpFoundation\StreamedResponse $response
     */
    public function __invoke($dir, $filename)
    {
        try {
            $path = $dir.'/'.$filename;
            return Storage::response($path, null, ['Accept-Range' => 'bytes']);
        } catch (FileNotFoundException $exception) {
            throw new HttpException(404, $exception->getMessage(), $exception);
        }
    }
}
