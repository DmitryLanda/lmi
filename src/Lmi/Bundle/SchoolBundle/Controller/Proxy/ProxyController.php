<?php
/*
* Copyright © FarHeap Solutions
*
* For a full copyright notice, see the COPYRIGHT file.
*/

namespace Lmi\Bundle\SchoolBundle\Controller\Proxy;

use Lmi\Bundle\SchoolBundle\Service\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */
class ProxyController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function sendRequestAction(Request $request)
    {
        $url = $request->get('url');
        $method = strtoupper($request->getMethod());
        $data = $request->get('data');
        $headers = $request->get('headers', array());
        $mimeType = 'application/json; charset=utf-8; type=entry';

        if ($image = $request->files->get('image')) {
            $data = $this->getImageContent($image);
            $mimeType = 'image/*';
        }

        $response = new Response();

        switch ($method) {
            case 'GET':
                $apiResponse = $this->getConnection()->sendGetRequest($url, true);

                $response->setStatusCode(200)
                    ->setContent($apiResponse->getContent())
                    ->headers->add($apiResponse->getHeaders());

                break;
            case 'POST':
                $apiResponse = $this->getConnection()->sendPostRequest($url, $data, $mimeType);

                $response->setStatusCode(201)
                    ->setContent($apiResponse->getContent())
                    ->headers->add($apiResponse->getHeaders());

                break;
            case 'PUT':
                $apiResponse = $this->getConnection()->sendPutRequest($url, $data, $mimeType);

                $response->setStatusCode(200)
                    ->setContent($apiResponse->getContent())
                    ->headers->add($apiResponse->getHeaders());

                break;
            case 'DELETE':
                $this->getConnection()->sendDeleteRequest($url);

                $response->setStatusCode(204);

                break;
            default:
                $response->setContent(sprintf('Method %s not allowed', $method))
                    ->setStatusCode(405);
        }

        return $response;
    }

    /**
     * @param UploadedFile $image
     * @return string
     */
    private function getImageContent(UploadedFile $image)
    {
        $image = $image->move('/tmp/media/');

        return file_get_contents($image->getRealPath());
    }

    /**
     * @return Connection
     */
    private function getConnection()
    {
        return $this->container->get('yandex.fotki.connection');
    }
}
