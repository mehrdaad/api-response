<?php

namespace EllipseSynergie\ApiResponse\Laravel;

use EllipseSynergie\ApiResponse\AbstractResponse;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Illuminate\Support\Facades\Response as IlluminateResponse;
use Illuminate\Validation\Validator;

/**
 * Class Response
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package EllipseSynergie\ApiResponse\Laravel
 * @author Maxime Beaudoin <maxime.beaudoin@ellipse-synergie.com>
 */
class Response extends AbstractResponse
{
    /**
     * @param array $array
     * @param array $headers
     * @return \Illuminate\Http\Response
     */
    public function withArray(array $array, array $headers = array())
    {
        return IlluminateResponse::json($array, $this->statusCode, $headers);
    }

    /**
     * Respond with a paginator, and a transformer.
     *
     * @param mixed $item Data to be wrapped with League\Fractal\Resource\Item
     * @param callable|League\Fractal\Resource\ResourceInterface $item
     *
     * @return \Illuminate\Http\Response
     */
    public function withPaginator(Paginator $paginator, $callback)
    {
        $resource = new Collection($paginator->getCollection(), $callback);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $rootScope = $this->fractal->createData($resource);

        return $this->withArray($rootScope->toArray());
    }
    
    /**
     * Generates a Response with a 400 HTTP header and a given message from validator
     *
     * @param $validator
     * @return \Illuminate\Http\Response
     */
    public function errorWrongArgsValidator(Validator $validator)
    {
        return $this->errorWrongArgs($validator->messages()->toArray());
    }
} 
