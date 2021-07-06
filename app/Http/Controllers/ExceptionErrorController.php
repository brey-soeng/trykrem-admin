<?php

namespace App\Http\Controllers;

use App\Http\Requests\AmendedRequest;
use App\Http\Requests\ExceptionErrorRequest;
use App\Http\Requests\FileRequest;
use App\Models\ExceptionError;
use App\Services\ApiCodeService;
use App\Utils\FileSystem;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;

class ExceptionErrorController extends Controller
{
    /**
     * @param ExceptionErrorRequest $request
     * @return Response
     */
    public function logs(ExceptionErrorRequest $request): Response
    {
        $validated = $request->validated();
        return ResponseBuilder::asSuccess(ApiCodeService::HTTP_OK)
            ->withHttpCode(ApiCodeService::HTTP_OK)
            ->withData(ExceptionError::getList($validated))
            ->withMessage(__('message.common.search.success'))
            ->build();
    }

    /**
     * @param AmendedRequest $request
     * @return Response
     */
    public function amended(AmendedRequest $request): Response
    {
        $validated = $request->validated();
        $exception = ExceptionError::query()->find($validated['id']);
        $exception->solve();
        return ResponseBuilder::asSuccess(ApiCodeService::HTTP_OK)
            ->withHttpCode(ApiCodeService::HTTP_OK)
            ->withData($exception)
            ->withMessage(__('message.common.update.success'))
            ->build();
    }

    /**
     * @return Response
     */
    public function files(): Response
    {
      function formatBytes(int $size) : string
            {
                $units = ['B', 'KB', 'MB' , 'GB' , 'TB'];
                for($i = 0; $size >= 1024 && $i < 4; $i ++)
                {
                    $size /= 1024;
                }
                return round($size, 3) . $units[$i];
            }


        $disk = Storage::disk('logs');
        $fileArray =$disk->files('./');
        $files = [];
        Log::info('1',$fileArray);
        foreach ($fileArray as $file) {
            if($file === '.gitignore')
                continue;
            Log::info('1',[$disk->size($file)]);
            $files[] = [
                'type' => 'file',
                'size' => formatBytes($disk->size($file)),
                'name' => $file,
                'url' => asset('storage/' . $file),
                'lastModified' => Carbon::parse($disk->lastModified($file))->format('Y-m-d H:m:s'),
                'pathinfo' => pathinfo($file)
            ];
        }

        return response()->json(['file' => $files]);

    }
    /**
     * @param FileRequest $request
     * @return Response
     */
    public function file(FileRequest $request): Response
    {
        try {
            $validated = $request->validated();
            $fileSystem = new FileSystem('/', 'logs');
            $contents = $fileSystem->getDisk()->get($validated['file']);
            return ResponseBuilder::asSuccess(ApiCodeService::HTTP_OK)
                ->withHttpCode(ApiCodeService::HTTP_OK)
                ->withData([
                    'file' => $contents
                ])
                ->withMessage(__('message.common.search.success'))
                ->build();
        } catch (FileNotFoundException $exception) {
            return ResponseBuilder::asError(ApiCodeService::HTTP_BAD_REQUEST)
                ->withHttpCode(ApiCodeService::HTTP_BAD_REQUEST)
                ->withMessage($exception->getPrevious()->getMessage())
                ->build();
        }
    }
}
