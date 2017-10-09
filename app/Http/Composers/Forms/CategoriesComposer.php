<?php

namespace App\Http\Composers\Forms;

use Illuminate\View\View;
use App\Category;

class CategoriesComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $categories;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(Category $category)
    {
        // Dependencies automatically resolved by service container...
        $this->categories = $category;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        $view->with('categories', $this->categories->get());
    }

    # default categories
    protected function default_categories($entity)
    {
        if (is_array(old('categories')))
        {
            return old('categories');
        }
        
        return isset($entity)
            ? $entity->categories->pluck('id')->toArray()
            : [];
    }
}