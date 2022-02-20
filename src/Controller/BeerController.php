<?php

namespace App\Controller;

use App\Consumer\PunkApi;
use App\Services\ValidateParams;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BeerController extends AbstractController
{

    /**
     * @Route("/api/beers", name="all_beers", methods={"GET"})
     * @return JsonResponse
     */
    public function getAllBeer(): JsonResponse
    {
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $per_page = isset($_GET["per_page"]) ? $_GET["per_page"] : 25;
        $array_beers = array();

        $validation = ValidateParams::validate($page, $per_page);

        if($validation['valid']){
            try {
                $array_data = PunkApi::getBeerAll($page, $per_page);
            } catch (GuzzleException $e) {
                $array_data = [];
            }
            $response = ['status' => Response::HTTP_NO_CONTENT,
                'message' => "Not results find", 'data' => $array_beers];
        }else{
            if($validation['page']=="error"){
                $param_page_error = array(
                    "location" => "Query",
                    "param" => "page",
                    "msg" => "Must be a number greater than 0",
                    "value" => $page,
                );
                array_push($array_beers,$param_page_error);

            }
            if($validation['per_page']=="error"){
                $param_per_page_error = array(
                    "location" => "Query",
                    "param" => "per_page",
                    "msg" => "Must be a number greater than 0 and less than 80",
                    "value" => $per_page,
                );
                array_push($array_beers,$param_per_page_error);
            }

            $response = ['status' => Response::HTTP_BAD_REQUEST,
                'message' => "Invalid query params", 'data' => $array_beers];
        }

        if (!empty($array_data)) {
            foreach ($array_data as $beer) {
                $aux['id'] = $beer->id;
                $aux['name'] = $beer->name;
                $aux['description'] = $beer->description;
                array_push($array_beers, $aux);
            }
            $response =['status' => Response::HTTP_OK, 'message' => "success", 'data' => $array_beers];
        }

        return new JsonResponse($response);

    }


    /**
     * @param $id
     * @Route("/api/beers/{id}", name="beer_by_id", methods={"GET"})
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function getById($id): JsonResponse
    {
        $response = new JsonResponse(['status' => Response::HTTP_NOT_FOUND, 'message' => "not found", 'data' => []]);

        if (is_int($id) or intval($id) > 1) {
            $array_data = PunkApi::getBeerById($id);
        } else {
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
