<?php

namespace App\Controller;

use App\Consumer\PunkApi;
use App\Services\ValidateParams;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class BeerByFood extends AbstractController
{
    /**
     * @param string $food
     * @return JsonResponse
     */
    public function __invoke(string $food)
    {
        $page = isset($_GET["page"]) ? $_GET["page"] : 1;
        $per_page = isset($_GET["per_page"]) ? $_GET["per_page"] : 25;
        $array_beers = array();

        $validation = ValidateParams::validate($page, $per_page);

        if($validation['valid']){
            try {
                $array_data = PunkApi::getBeerByFood($food, $page, $per_page);
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

}