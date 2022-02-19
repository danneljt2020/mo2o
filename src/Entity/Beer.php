<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BeerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\BeerByFood;

/**
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={
 *         "get",
 *         "get_by_food" = {
 *              "method" = "GET",
 *              "pagination_enabled" = true,
 *              "path" = "/food/search/{food}",
 *              "controller" = BeerByFood::class,
 *              "read"=false,
 *              "openapi_context" = {
 *              "parameters" = {
 *                  {
 *                      "name" = "food",
 *                      "in" = "path",
 *                      "description" = "Enter the food for search",
 *                      "type" = "string",
 *                      "required" = true,
 *                      "example"= "chicken",
 *                  },{
 *                      "name" = "page",
 *                      "in" = "query",
 *                      "description" = "Enter the page",
 *                      "type" = "string",
 *                      "required" = false,
 *                      "example"= "1",
 *                  },{
 *                      "name" = "per_page",
 *                      "in" = "query",
 *                      "description" = "Enter number of items per page",
 *                      "type" = "string",
 *                      "required" = false,
 *                      "example"= "25",
 *                  },
 *           },
 *         },
 *       }
 *     },
 *     normalizationContext={"groups"={"beer:read"}},
 * )
 * @ORM\Entity(repositoryClass=BeerRepository::class)
 */
class Beer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("beer:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("beer:read")
     *
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("beer:read")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     * @Groups("beer:read")
     */
    private $first_brewed;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("beer:read")
     */
    private $tagline;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("beer:read")
     */
    private $image_url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFirstBrewed(): ?string
    {
        return $this->first_brewed;
    }

    public function setFirstBrewed(?string $first_brewed): self
    {
        $this->first_brewed = $first_brewed;

        return $this;
    }

    public function getTagline(): ?string
    {
        return $this->tagline;
    }

    public function setTagline(?string $tagline): self
    {
        $this->tagline = $tagline;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

}
