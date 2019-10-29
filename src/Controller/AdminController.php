<?php

namespace App\Controller;

use App\Entity\Category;
use App\Utils\CategoryTreeAdminList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_main_page")
     */
    public function index()
    {
        return $this->render('admin/my_profile.html.twig');
    }

    /**
     * @Route("/categories", name="categories")
     */
    public function categories(CategoryTreeAdminList $categories)
    {
	    $subcategories = $categories->getCategoryList($categories->buildTree());
	    dd($subcategories);
        return $this->render('admin/categories.html.twig', compact('categories', 'subcategories'));
    }

    /**
     * @Route("/delete-category/{id}", name="delete_category")
     */
    public function deleteCategory(Category $category)
    {
    	$entityManager = $this->getDoctrine()->getManager();
    	$entityManager->remove($category);
    	$entityManager->flush();

        return $this->redirectToRoute('categories');
    }

    /**
     * @Route("/edit-category", name="edit_category")
     */
    public function editCategory()
    {
        return $this->render('admin/edit_category.html.twig');
    }

    /**
     * @Route("/videos", name="videos")
     */
    public function videos()
    {
        return $this->render('admin/videos.html.twig');
    }

    /**
     * @Route("/upload-video", name="upload_video")
     */
    public function uploadVideo()
    {
        return $this->render('admin/videos.html.twig');
    }

    /**
     * @Route("/users", name="users")
     */
    public function users()
    {
        return $this->render('admin/videos.html.twig');
    }
}
