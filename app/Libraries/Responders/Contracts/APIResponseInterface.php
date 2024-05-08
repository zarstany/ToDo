<?php

namespace App\Libraries\Responders\Contracts;

use App\Http\V1\Transformers\TransformerAbstract;
use App\Libraries\Responders\HttpErrorObjectDTO;
use App\Libraries\Responders\HttpObjectDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;

interface APIResponseInterface
{
    public function respond(HttpObjectDTO $httpObject): JsonResponse;

    public function responseItem(HttpObjectDTO $httpObject, TransformerAbstract $transformer): JsonResponse;

    public function respondCollection(HttpObjectDTO $httpObject, TransformerAbstract $transformer): JsonResponse;

    public function respondError(HttpErrorObjectDTO $error, int $status, array $headers = []): JsonResponse;

    public function respondFormErrors(MessageBag $messageBag, int $status, array $headers = []): JsonResponse;
}
