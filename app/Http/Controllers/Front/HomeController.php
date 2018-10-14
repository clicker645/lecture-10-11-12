<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Shop\Categories\Category;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Shop\Products\Product;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $category;
    private $product;

    /**
     * HomeController constructor.
     * @param Category $category
     */
    public function __construct(Category $category, Product $product)
    {
        $this->category = $category;
        $this->product = $product;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $product = [
            'name' => 'Sasfhhjd Nnfjdhj HFFHGDGS hd',
            'description' => 'kj;fdgfh jdkjhg  kjfdhgjhd    ',
            'price' => '23.59',
            'status' => '0',
            'quantity' => '18',
            'category_id' => '1',
        ];
        $product = $this->product->available()->get();
        dd($product);

        /*        $cat1 = $this->categoryRepo->findCategoryById(1);
                $cat2 = $this->categoryRepo->findCategoryById(2);

                return view('front.index', compact('cat1', 'cat2'));*/
    }

}
