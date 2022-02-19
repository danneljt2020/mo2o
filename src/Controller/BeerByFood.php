<?php

namespace App\Controller;

use App\Consumer\PunkApi;
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
        $page = $_GET["page"];
        $per_page = $_GET["per_page"];
        $array_beers = array();

        try {
            $array_data = PunkApi::getBeerByFood($food, $page, $per_page);
        } catch (GuzzleException $e) {
            $array_data = [];
        }

        $validation = $this->validateQueryParams($page, $per_page);

        if(!$validation['valid']){
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
        }

        $response = new JsonResponse(['status' => Response::HTTP_BAD_REQUEST,
            'message' => "Invalid query params", 'data' => $array_beers]);

        if (!empty($array_data)) {
            foreach ($array_data as $beer) {
                $aux['id'] = $beer->id;
                $aux['name'] = $beer->name;
                $aux['description'] = $beer->description;
                array_push($array_beers, $aux);
            }

            $response = new JsonResponse(['status' => Response::HTTP_OK, 'message' => "success", 'data' => $array_beers]);
        }

        return $response;
    }


    /**
     * @param $page
     * @param $per_page
     * @return array
     */
    private function validateQueryParams($page, $per_page)
    {
        $respond = array(
            "valid" => true,
            "page" => "success",
            "per_page" => "success",
        );

        $page = intval($page);
        $per_page = intval($per_page);
        if (!is_int($page) or $page < 1) {
            $respond["valid"] = false;
            $respond["page"] = "error";
        }
        if (!is_int($per_page) or $per_page > 80 or $per_page < 1) {
            $respond["valid"] = false;
            $respond["per_page"] = "error";
        }
        return $respond;

    }
}