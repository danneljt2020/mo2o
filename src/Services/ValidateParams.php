<?php


namespace App\Services;


class ValidateParams
{

    /**
     * @param $page
     * @param $per_page
     * @return array
     */
    public static function validate($page, $per_page)
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