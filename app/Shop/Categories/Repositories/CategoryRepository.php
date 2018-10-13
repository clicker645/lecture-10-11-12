<?php

namespace App\Shop\Categories\Repositories;

use App\Repositories\BaseRepository;
use App\Shop\Categories\Category2;
use App\Shop\Categories\Exceptions\CategoryInvalidArgumentException;
use App\Shop\Categories\Exceptions\CategoryNotFoundException;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
//use App\Shop\Products\Product;
//use App\Shop\Products\Transformations\ProductTransformable;
//use App\Shop\Tools\UploadableTrait;
use App\Shop\Products\Exceptions\ProductNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    // use UploadableTrait, ProductTransformable;

    /**
     * CategoryRepository constructor.
     * @param Category2 $category
     */
    public function __construct(Category2 $category)
    {
        parent::__construct($category);
        $this->model = $category;
    }

    /**
     * List all the categories
     *
     * @param string $order
     * @param string $sort
     * @param array $except
     * @return \Illuminate\Support\Collection
     */
    public function listCategories(string $order = 'id', string $sort = 'desc', $except = []) : Collection
    {
        return $this->model->orderBy($order, $sort)->get()->except($except);
    }

    /**
     * Create the category
     *
     * @param array $params
     *
     * @return Category2
     * @throws CategoryInvalidArgumentException
     * @throws CategoryNotFoundException
     */
    public function createCategory(array $params) : Category2
    {
        try {
            $collection = collect($params);
            if (isset($params['name'])) {
                $slug = str_slug($params['name']);
            }

            if (isset($params['cover']) && ($params['cover'] instanceof UploadedFile)) {
                $cover = $this->uploadOne($params['cover'], 'categories');
            }

            $merge = $collection->merge(compact('slug', 'cover'));

            $category = new Category2($merge->all());

            if (isset($params['parent'])) {
                $parent = $this->findCategoryById($params['parent']);
                $category->parent()->associate($parent);
            }

            $category->save();
            return $category;
        } catch (QueryException $e) {
            throw new CategoryInvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Update the category
     *
     * @param array $params
     *
     * @return Category2
     * @throws CategoryNotFoundException
     */
    /*public function updateCategory(array $params) : Category
    {
        $category = $this->findCategoryById($this->model->id);
        $collection = collect($params)->except('_token');
        $slug = str_slug($collection->get('name'));

        if (isset($params['cover']) && ($params['cover'] instanceof UploadedFile)) {
            $cover = $this->uploadOne($params['cover'], 'categories');
        }

        $merge = $collection->merge(compact('slug', 'cover'));
        if (isset($params['parent'])) {
            $parent = $this->findCategoryById($params['parent']);
            $category->parent()->associate($parent);
        }

        $category->update($merge->all());
        return $category;
    }*/

    /**
     * @param int $id
     * @return Category2
     * @throws CategoryNotFoundException
     */
    public function findCategoryById(int $id) : Category2
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new CategoryNotFoundException($e->getMessage());
        }
    }

    /**
     * Delete a category
     *
     * @return bool
     * @throws \Exception
     */
//    public function deleteCategory() : bool
//    {
//        return $this->model->delete();
//    }
//
//    /**
//     * Associate a product in a category
//     *
//     * @param Product $product
//     * @return \Illuminate\Database\Eloquent\Model
//     */
//    /*public function associateProduct(Product $product)
//    {
//        return $this->model->products()->save($product);
//    }*/
//
    /**
     * @return Collection
     * @throws ProductNotFoundException
     */
    public function findProducts() : Collection
    {
        try {
            return $this->model->products;
        } catch (ModelNotFoundException $e) {
            throw new ProductNotFoundException($e->getMessage());
        }
    }

//    /**
//     * @param array $params
//     */
//    public function syncProducts(array $params)
//    {
//        $this->model->products()->sync($params);
//    }
//
//
//    /**
//     * Detach the association of the product
//     *
//     */
//    public function detachProducts()
//    {
//        $this->model->products()->detach();
//    }
//
//    /**
//     * @param $file
//     * @param null $disk
//     * @return bool
//     */
//    public function deleteFile(array $file, $disk = null) : bool
//    {
//        return $this->update(['cover' => null], $file['category']);
//    }

    /**
     * Return the category by using the slug as the parameter
     *
     * @param string $slug
     *
     * @return Category2
     * @throws CategoryNotFoundException
     */
    public function findCategoryBySlug(string $slug) : Category2
    {
        try {
            return $this->findOneByOrFail('slug', $slug);
        } catch (ModelNotFoundException $e) {
            throw new CategoryNotFoundException($e);
        }
    }

    /**
     * @return mixed
     */
    public function findParentCategory()
    {
        return $this->model->parent;
    }

    /**
     * @return mixed
     */
    public function findChildren()
    {
        return $this->model->children;
    }
}
