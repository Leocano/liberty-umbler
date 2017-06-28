<?php 

class SubcategoryDAO{
	public function getSubcategoryByCategoryId($id_category){
		$db = Database::getInstance();

		$db->query(
			"
			SELECT
				*
			FROM
				tb_subcategory
			WHERE
				id_category = ?
			"
			,
			array(
				$id_category
			)
		);

		return $db->getResults();
	}

	public function getSubcategoryById($id_subcategory){
		$db = Database::getInstance();

		$db->query(
			"
			SELECT
				*
			FROM
				tb_subcategory
			WHERE
				id_subcategory = ?
			"
			,
			array(
				$id_subcategory
			)
		);

		return $db->getResults();
	}
}