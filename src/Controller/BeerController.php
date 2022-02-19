<?php

namespace App\Controller;

use App\Consumer\PunkApi;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BeerController extends AbstractController
{


    /**
     * @param $id
     * @return JsonResponse
     * @Route("/api/beer/{id}", name="beer_by_id", methods={"GET"})
     * @throws GuzzleException
     */
    public function getById($id): JsonResponse
    {
        $response = new JsonResponse(['status' => Response::HTTP_NOT_FOUND, 'message' => "not found", 'data' => []]);

        if(is_int($id) or intval($id) > 1){
            $array_data = PunkApi::getBeerById($id);
        }else{
            $param_id_error = array(
                "location" => "Path",
                "param" => "id",
                "msg" => "Must be a number greater than 0",
                "value" => $id,
            );
            $response = new JsonResponse(['status' => Response::HTTP_BAD_REQUEST, 'message' => "Invalid path params", 'data' => $param_id_error]);
        }

        if (!empty($array_data)) {
            $beer = array_pop($array_data);
            $beer_data['id'] = $beer->id;
            $beer_data['name'] = $beer->name;
            $beer_data['tagline'] = $beer->tagline;
            $beer_data['first_brewed'] = $beer->first_brewed;
            $beer_data['image_url'] = $beer->image_url;
            $beer_data['description'] = $beer->description;
            $response = new JsonResponse(['status' => Response::HTTP_OK, 'message' => "success", 'data' => $beer_data]);
        }

        return $response;

    }

}
