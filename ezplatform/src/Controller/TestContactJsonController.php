<?php


namespace App\Controller;

use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestContactJsonController extends AbstractController
{

    /** @var \eZ\Publish\API\Repository\SearchService */
    private $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * @Route("/test-contact-json/{contentId}", name="test-contact-json", requirements={"contentId"="[^/]*", "relation"="[0-9]+[0-9]*"})
     */
    public function testContactJson(string $contentId)
    {
        $contentId = intval($contentId);
        $query = new Query();
        $query->filter = new Criterion\ContentId([$contentId]);
        $searchResults = $this->searchService->findContent($query);

        $result = [];

        if($searchResults->totalCount > 0){
            foreach ($searchResults->searchHits as $searchHit) {
                $valuesObject = $searchHit->valueObject;
                if($valuesObject->getContentType()->identifier === "test_contact"){
                    $fields = [];
                    foreach ($valuesObject->getFields() as $key => $field){
                        $fields[$key] = $field->value;
                    }
                    $result[] = $fields;
                }
            }
        }else {
            $result = "No result";
        }

        $result = json_encode($result);

        $response = new JsonResponse($result);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        return $response;
    }

}