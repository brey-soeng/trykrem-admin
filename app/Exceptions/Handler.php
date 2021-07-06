<?php

namespace App\Exceptions;

use App\Models\ExceptionError;
use App\Services\ApiCodeService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;
use Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class Handler extends ExceptionHandler
{
    public $logId;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * @param Throwable $exception
     * @return bool
     */
    protected function isUnauthorizedHttpException(Throwable $exception): bool
    {
        return $exception instanceof AuthorizationException  ||
            $exception instanceof UnauthorizedHttpException ||
            $exception instanceof AuthenticationException;
    }

    /**
     * @param Throwable $exception
     * @return bool
     */
    protected function isValidationException(Throwable $exception): bool
    {
        return $exception instanceof ValidationException;
    }

    /**
     * @param Throwable $exception
     * @return bool
     */
    protected function isAuthorizationException(Throwable $exception): bool
    {
        return $exception instanceof UnauthorizedException ||
            ($exception instanceof HttpException && $exception->getStatusCode() === ApiCodeService::HTTP_FORBIDDEN);
    }

    /**
     * @param Throwable $exception
     * @return bool
     */
    protected function isThrottleRequestsException(Throwable $exception): bool
    {
        return $exception instanceof ThrottleRequestsException;
    }

    /**
     * @param Throwable $exception
     * @return bool
     */
    protected function isNotFoundHttpException(Throwable $exception): bool
    {
        return $exception instanceof NotFoundHttpException;
    }

    /**
     * @param Throwable $exception
     * @return bool
     */
    protected function isMethodNotAllowedHttpException(Throwable $exception): bool
    {
        return $exception instanceof MethodNotAllowedHttpException;
    }

    /**
     * @param Throwable $exception
     * @return bool
     */
    protected function isSuspiciousOperationException(Throwable $exception): bool
    {
        return $exception instanceof SuspiciousOperationException;
    }

    /**
     * @param Throwable $exception
     * @return bool
     */
    protected function isMaintenanceModeException(Throwable $exception): bool
    {
        return $exception instanceof MaintenanceModeException
            || (
                $exception instanceof HttpException &&
                $exception->getStatusCode() === ApiCodeService::HTTP_SERVICE_UNAVAILABLE
            );
    }

    /**
     * @param Throwable $exception
     * @return bool
     */
    protected function isTokenExpiredException(Throwable $exception): bool
    {
        return $exception instanceof TokenExpiredException;
    }

    protected function exceptionError(Throwable $exception)
    {
        if (!$this->isUnauthorizedHttpException($exception) && !$this->isValidationException($exception) &&
            !$this->isThrottleRequestsException($exception) && !$this->isNotFoundHttpException($exception) &&
            !$this->isAuthorizationException($exception) && !$this->isMethodNotAllowedHttpException($exception) &&
            !$this->isSuspiciousOperationException($exception)  && !$this->isMaintenanceModeException($exception) &&
            !$this->isTokenExpiredException($exception)) {
            try {
                $log = ExceptionError::create([
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTrace(),
                    'trace_as_string' => $exception->getTraceAsString(),
                ]);
                $this->setLogId($log->getId());
            } catch (Exception $e) {
                Log::error($e);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getLogId()
    {
        return $this->logId;
    }

    /**
     * @param $logId
     */
    public function setLogId($logId): void
    {
        $this->logId = $logId;
    }

    /**
     * @return array
     */
    protected function context(): array
    {
        return array_merge(parent::context(), [

        ]);
    }

    /**
     * @param Throwable $e
     * @throws Exception|Throwable
     */
    public function report(Throwable $e)
    {
        $this->exceptionError($e);

        parent::report($e);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     * @return Response
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        App::setLocale($request->header('lang', config('app.locale')));
        if ($this->isUnauthorizedHttpException($e)) {
            return ResponseBuilder::asError(ApiCodeService::HTTP_UNAUTHORIZED)
                ->withHttpCode(ApiCodeService::HTTP_UNAUTHORIZED)
                ->withData()
                ->build();
        }
        if ($this->isTokenExpiredException($e)) {
            return ResponseBuilder::asError(ApiCodeService::HTTP_TOKEN_EXPIRED)
                ->withHttpCode(ApiCodeService::HTTP_TOKEN_EXPIRED)
                ->withData()
                ->build();
        }
        if ($this->isValidationException($e)) {
            return ResponseBuilder::asError(ApiCodeService::HTTP_UNPROCESSABLE_ENTITY)
                ->withHttpCode(ApiCodeService::HTTP_UNPROCESSABLE_ENTITY)
                ->withData($e->errors())
                ->build();
        }
        if ($this->isAuthorizationException($e)) {
            return ResponseBuilder::asError(ApiCodeService::HTTP_FORBIDDEN)
                ->withHttpCode(ApiCodeService::HTTP_FORBIDDEN)
                ->withData()
                ->build();
        }
        if ($this->isThrottleRequestsException($e)) {
            return ResponseBuilder::asError(ApiCodeService::HTTP_TOO_MANY_REQUEST)
                ->withHttpCode(ApiCodeService::HTTP_TOO_MANY_REQUEST)
                ->withData()
                ->build();
        }
        if ($this->isNotFoundHttpException($e)) {
            return ResponseBuilder::asError(ApiCodeService::HTTP_NOT_FOUND)
                ->withHttpCode(ApiCodeService::HTTP_NOT_FOUND)
                ->withData()
                ->build();
        }
        if ($this->isMethodNotAllowedHttpException($e)) {
            return ResponseBuilder::asError(ApiCodeService::HTTP_METHOD_NOT_ALLOWED)
                ->withHttpCode(ApiCodeService::HTTP_METHOD_NOT_ALLOWED)
                ->withData()
                ->build();
        }
        if ($this->isMaintenanceModeException($e)) {
            return ResponseBuilder::asError(ApiCodeService::HTTP_SERVICE_UNAVAILABLE)
                ->withHttpCode(ApiCodeService::HTTP_SERVICE_UNAVAILABLE)
                ->withData()
                ->build();
        }
        if (App::environment('local')) {
            return parent::render($request, $e);
        }
        return ResponseBuilder::asError(ApiCodeService::HTTP_INTERNAL_SERVER_ERROR)
            ->withHttpCode(ApiCodeService::HTTP_INTERNAL_SERVER_ERROR)
            ->withData([
                'errorId' => (string) $this->getLogId()
            ])
            ->build();
    }
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
