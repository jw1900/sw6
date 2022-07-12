<?php declare(strict_types=1);

namespace TestPlugin\Storefront\Controller;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItemFactoryRegistry;
use Shopware\Core\Checkout\Cart\SalesChannel\CartService;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\ContainsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Storefront\Framework\Routing\StorefrontResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(defaults={"_routeScope"={"storefront"}})
 */
class ProductController extends StorefrontController
{

    private LineItemFactoryRegistry $factory;
    private CartService $cartService;
    private $productRepository;

    /**
    * @var EntityRepositoryInterface
    */

    public function __construct(EntityRepositoryInterface $productRepository)
    {
        $this->productRepository= $productRepository;
    }

    /**
    * @Route("/hintproducts", name="frontend.test.hintproducts", defaults={"auth_required"=false}, defaults={"XmlHttpRequest"=true}, methods={"GET"})
     */   
    public function hintProducts(Request $request, SalesChannelContext $context, Context $con): Response
    {

        $criteria = new Criteria();
        $criteria->addFilter(new ContainsFilter('productNumber', $_GET["val"]));
        $criteria->addFilter(new EqualsFilter('active', true));
        $criteria->addFilter(new EqualsFilter('childCount', null));

        $productsAllInfo = $this->productRepository->search($criteria, $con)->getEntities();
        
        $products = [];
        foreach ($productsAllInfo as $prod) {
            if($prod){
                $products[] = ([
                    'number' => $prod->productNumber,
                    'id' => $prod->id
                ]);    
            }
        }
        $response = new JsonResponse();
        $response->setData($products);
        return $response;
    }

    /**
    * @Route("/checkproduct", name="frontend.test.checkproduct", defaults={"auth_required"=false}, defaults={"XmlHttpRequest"=true}, methods={"GET"})
     */   
    public function checkProduct(Request $request, SalesChannelContext $context, Context $con): Response
    {

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('productNumber', $_GET["val"]));
        $criteria->addFilter(new EqualsFilter('active', true));
        $criteria->addFilter(new EqualsFilter('childCount', null));

        $prod = $this->productRepository->search($criteria, $con)->first();

        $product = [];
        if($prod){
            $product[] = ([
                'info' => $prod->id
            ]);
        }else{
            $product[] = ([
                'info' => 'none'
            ]);
        }
        
        $response = new JsonResponse();
        $response->setData($product);
        return $response;
    }

    /**
     * @Route("/addtocart", name="frontend.test.addtocart", methods={"GET", "POST"}, defaults={"XmlHttpRequest"=true})
     */
    public function addToCart(LineItemFactoryRegistry $factory, CartService $cartService, Cart $cart, SalesChannelContext $context, Request $request): Response
    {
        $this->factory = $factory;
        $this->cartService = $cartService;
        
        $artid = $request->request->all('artid');
        $artqty = $request->request->all('artqty');
        
        $i=0;
        foreach ($artid as $id) {
            if($id != ""){
                $lineItem = $this->factory->create([
                    'type' => LineItem::PRODUCT_LINE_ITEM_TYPE,
                    'referencedId' => $id,
                    'quantity' => (int) $artqty[$i],
                    'payload' => ['key' => 'value']
                ], $context);
                $this->cartService->add($cart, $lineItem, $context);
            }
            $i++;
        }
        return $this->redirectToRoute('frontend.checkout.cart.page');
    }


}
