<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 * @Route("/api", name="post_api")
 */
class ProductController extends AbstractController
{
    /**
     * @param ProductRepository $productRepository
     * @return JsonResponse
     * @Route("/products", name="products", methods={"GET"})
     */
    public function getPosts(ProductRepository $productRepository){
        $data = $productRepository->findAll();
        return $this->response($data);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     * @return JsonResponse
     * @throws \Exception
     * @Route("/add", name="product_add", methods={"POST"})
     */
    public function addProduct(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository){

        try{
            $request = $this->transformJsonBody($request);

            if (!$request || !$request->get('name') || !$request->request->get('description') || !$request->get('weight')
                || !$request->get('color') || !$request->get('price') || !$request->get('stock')){
                throw new \Exception();
            }

            $product = new Product();
            $product->setName($request->get('name'));
            $product->setDescription($request->get('description'));
            $product->setStock($request->get('Stock'));
            $product->setColor($request->get('color'));
            $product->setWeight($request->get('weight'));
            $product->setPrice($request->get('price'));
            $product->setWeight($request->get('weight'));
            $entityManager->persist($product);
            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => "Post added successfully",
            ];
            return $this->response($data);

        }catch (\Exception $e){
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return $this->response($data, 422);
        }

    }


    /**
     * @param ProductRepository $productRepository
     * @param $id
     * @return JsonResponse
     * @Route("/product/{id}", name="/product_get", methods={"GET"})
     */
    public function getProduct(ProductRepository $productRepository, $id){
        $product =  $productRepository->find($id);

        if (!$product){
            $data = [
                'status' => 404,
                'errors' => "Post not found",
            ];
            return $this->response($data, 404);
        }
        return $this->response((array)$product);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     * @param $id
     * @return JsonResponse
     * @Route("/product/{id}", name="product_put", methods={"PUT"})
     */
    public function updatePost(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository, $id){

        try{
            $product = $productRepository->find($id);

            if (!$product){
                $data = [
                    'status' => 404,
                    'errors' => "Post not found",
                ];
                return $this->response($data, 404);
            }

            $request = $this->transformJsonBody($request);

            if (!$request || !$request->get('name') || !$request->request->get('description')|| !$request->get('weight')
                || !$request->get('color') || !$request->get('price') || !$request->get('stock')){
                throw new \Exception();
            }

            $product->setName($request->get('name'));
            $product->setDescription($request->get('description'));
            $product->setStock($request->get('Stock'));
            $product->setColor($request->get('color'));
            $product->setWeight($request->get('weight'));
            $product->setPrice($request->get('price'));
            $product->setWeight($request->get('weight'));
            $entityManager->flush();

            $data = [
                'status' => 200,
                'errors' => "Post updated successfully",
            ];
            return $this->response($data);

        }catch (\Exception $e){
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return $this->response($data, 422);
        }

    }


    /**
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     * @param $id
     * @return JsonResponse
     * @Route("/product/{id}", name="product_delete", methods={"DELETE"})
     */
    public function deletePost(EntityManagerInterface $entityManager, ProductRepository $productRepository, $id){
        $product =$productRepository->find($id);

        if (!$product){
            $data = [
                'status' => 404,
                'errors' => "Post not found",
            ];
            return $this->response($data, 404);
        }

        $entityManager->remove($product);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'errors' => "Post deleted successfully",
        ];
        return $this->response($data);
    }



    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param $status
     * @param array $headers
     * @return JsonResponse
     */
    public function response($data, $status = 200, $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }

    protected function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }

}