<?php

	namespace App\Utils;

	use App\Twig\AppExtension;
	use App\Utils\AbstractClasses\CategoryTreeAbstract;

	class CategoryTreeFrontPage extends CategoryTreeAbstract
	{
		public $html_1 = '<ul>';
		public $html_2 = '<li>';
		public $html_3 = '<a href="';
		public $html_4 = '">';
		public $html_5 = '</a>';
		public $html_6 = '</li>';
		public $html_7 = '</ul>';

		public $slugger, $mainParentName, $mainParentId, $currentCategoryName;

		public function getCategoryListAndParent(int $id): string
		{
			$this->slugger = new AppExtension();
			$parentData = $this->getMainParent($id);
			$this->mainParentName = $parentData['name'];
			$this->mainParentId = $parentData['id'];
			$key = array_search($id, array_column($this->categoriesArrayFromDB, 'id'));
			$this->currentCategoryName = $this->categoriesArrayFromDB[$key]['name'];

			$categories_array = $this->buildTree($parentData['id']);

			return $this->getCategoryList($categories_array);
		}
		
		public function getCategoryList(array $categories_array)
		{
			$this->categoryList .= $this->html_1;
			foreach ($categories_array as $value) {
				$catName            = $this->slugger->slugify($value['name']);
				$catID              = $value['id'];
				$url                = $this->urlGenerator->generate('video_list',
				                                                    ['categoryname' => $catName, 'id' => $catID]);
				$this->categoryList .= $this->html_2 . $this->html_3 . $url . $this->html_4 . $catName . $this->html_5;

				if (!empty($value['children'])) {
					$this->getCategoryList($value['children']);
				}
				$this->categoryList .= $this->html_6;
			}
			$this->categoryList .= $this->html_7;

			return $this->categoryList;
		}

		public function getMainParent(int $id): array
		{
			$key = array_search($id, array_column($this->categoriesArrayFromDB, 'id'));

			if ($this->categoriesArrayFromDB[$key]['parent_id'] != null) {
				return $this->getMainParent($this->categoriesArrayFromDB[$key]['parent_id']);
			} else {
				return [
					'id' => $this->categoriesArrayFromDB[$key]['id'],
					'name' => $this->categoriesArrayFromDB[$key]['name']
				];
			}
		}

	}
