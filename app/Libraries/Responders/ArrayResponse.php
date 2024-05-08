<?php

namespace App\Libraries\Responders;

use App\Http\V1\Controllers\Controller;
use App\Http\V1\Transformers\TransformerAbstract;
use App\Libraries\Responders\Contracts\APIResponseInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\MessageBag;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class ArrayResponse implements APIResponseInterface
{
    private Manager $fractal;

    public function __construct(Manager $fractal)
    {
        $this->fractal = $fractal;
    }

    public function respond(HttpObjectDTO $httpObject): JsonResponse
    {
        return response()->json($httpObject->getBody(), $httpObject->getStatus(), $httpObject->getHeaders());
    }

    public function responseItem(HttpObjectDTO $httpObject, TransformerAbstract $transformer): JsonResponse
    {
        $item = $httpObject->getItem();

        $resource = new Item($item, $transformer, $transformer->getResource());

        $rootScope = $this->fractal->createData($resource);
        $httpObject->setBody($rootScope->toArray());

        return $this->respond($httpObject);
    }

    public function respondCollection(HttpObjectDTO $httpObject, TransformerAbstract $transformer): JsonResponse
    {
        $collection = $httpObject->getCollection();

        if (is_array($collection) || $collection instanceof \Illuminate\Support\Collection) {
            $totalItems = count($collection);
            $collection = new LengthAwarePaginator($httpObject->getCollection(), $totalItems, $totalItems ?: 1);
        }

        /*
         ToDO: when fetching from limited pagination database, you should get the total results, not the list size
        if ($collection instanceof \Illuminate\Database\Eloquent\Collection) {
            $totalItems = count($collection);
            $collection = new LengthAwarePaginator($httpObject->getCollection(), $totalItems, $totalItems ?: 15);
        }*/

        $resource = new Collection($collection, $transformer, $transformer->getResource());

        if (! empty($httpObject->getMetadata())) {
            $resource->setMeta($httpObject->getMetadata());
        }

        $resource->setPaginator(new IlluminatePaginatorAdapter($collection));

        $rootScope = $this->fractal->createData($resource);
        $httpObject->setBody($rootScope->toArray());

        return $this->respond($httpObject);
    }

    public function respondError(HttpErrorObjectDTO $error, int $status, array $headers = []): JsonResponse
    {
        return response()->json($error->get(), $status, $headers);
    }

    public function respondFormErrors(MessageBag $messageBag, int $status, array $headers = []): JsonResponse
    {
        $errors = [];

        foreach ($messageBag->getMessages() as $field => $messages) {
            foreach ($messages as $message) {
                $httpErrorObject = new HttpErrorObjectDTO();

                $httpErrorObject->setStatus($status)
                    ->setTitle('Error in Field')
                    ->setDetail($message)
                    ->setCode('FORM_ERROR')
                    ->setSource([
                        'parameter' => $field,
                    ]);

                $errors[] = $httpErrorObject->get();
            }
        }

        $httpObject = new HttpObjectDTO();
        $httpObject->setBody($errors);
        $httpObject->setStatus(Controller::HTTP_BAD_REQUEST);

        return $this->respond($httpObject);
    }
}
