<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
		$this->loadMainCategories($manager);
		$this->loadElectronics($manager);
		$this->loadComputers($manager);
    }

	private function loadMainCategories($manager)
	{
		foreach ($this->getMainCategoriesData() as [$name]) {
			$category = new Category();
			$category->setName($name);
			$manager->persist($category);
		}

		$manager->flush();
    }

	private function loadElectronics($manager)
	{
		$this->loadSubcategories($manager, 'Electronics', 1);
    }

	private function loadComputers($manager)
	{
		$this->loadSubcategories($manager, 'Computers', 6);
    }

	private function loadSubcategories($manager, $parent_name, $parent_id)
	{
		$parent = $manager->getRepository(Category::class)->find($parent_id);
		$methodName = "get{$parent_name}Data";

		foreach ($this->$methodName() as [$name]) {
			$category = new Category();
			$category->setName($name);
			$category->setParent($parent);
			$manager->persist($category);
		}

		$manager->flush();
    }

	private function getMainCategoriesData()
	{
		return [
			['Electronics', 1],
			['Toys', 2],
			['Books', 3],
			['Movies', 4]
		];
    }

	private function getElectronicsData()
	{
		return [
			['Cameras', 5],
			['Computer', 6],
			['Cell phones', 3],
		];
    }

	private function getComputersData()
	{
		return [
			['Laptop', 8],
			['Desktop', 9],
		];
    }
}
