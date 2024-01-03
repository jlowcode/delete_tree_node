<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\Utilities\ArrayHelper;

// Require the abstract plugin class
require_once COM_FABRIK_FRONTEND . '/models/plugin-list.php';

class PlgFabrik_ListDelete_tree_node extends PlgFabrik_List
{
	protected $acl = array();

	protected $buttonPrefix = 'delete';

	protected $result = null;

	protected function getImageName()
	{
		return 'delete';
	}

	protected function buttonLabel()
	{
		return 'Delete';
	}

	/**
	 * Can the plug-in select list rows
	 *
	 * @return  bool
	 */
	public function canSelectRows()
	{
		return true;
	}

	public function button(&$args)
	{
		parent::button($args);
		return true;
	}

	/**
	 * Get the id, name, params of the parent column
	 *
	 * @return  array
	 */
	public function getDatabaseJoin () {
		$id = $this->getParams()["parent_column"];

		if ($id)
		{
			$db    = FabrikWorker::getDbo();
			$query = $db->getQuery(true);
			$query->select("id, name, params")->from("#__fabrik_elements")->where("id = " . $id);
			$db->setQuery($query);
			$result = $db->loadAssoc();
			$result["params"] = json_decode($result["params"]);
		}

		return $result;
	}

	/**
	 * Delete rows in database
	 *
	 * @param array $ids   ids of the rows to delete
	 * @param $table_name   table's name
	 */
	public function deleteRows ($ids, $table_name) {
		$db = FabrikWorker::getDbo();
		foreach ($ids as $id) {
			$db->setQuery(
				"DELETE FROM " . $table_name .
				" WHERE id = " . $id
			);
			try
			{
				$db->execute();
			}
			catch (RuntimeException $e)
			{
				$err = new stdClass;
				$err->error = $e->getMessage();
				echo json_encode($err);
				exit;
			}
		}
	}

	/**
	 * Get the childs of an element
	 *
	 * @param $id   id of the element
	 * @param $table_name   table's name
	 * @param $column   column name of the database join element
	 *
	 * @return array
	 */
	public function getChilds ($id, $table_name, $column) {
		$db = FabrikWorker::getDbo();
		$result = array();
		$query = $db->getQuery(true);
		$query->select("id")->from($table_name)->where($column . " = " . $id);
		$db->setQuery($query);
		$childs = $db->loadAssocList();
		foreach ($childs as $child) {
			$result[] = $child["id"];
		}
		return $result;
	}

	/**
	 * Delete the selected rows and associates their childs to their parent
	 * @param $ids   ids of selected rows
	 * @param $table_name   table's name
	 * @param $column   column name of the database join element
	 *
	 */
	public function deleteDBJoinSingle ($ids, $table_name, $column) {
		$db = FabrikWorker::getDbo();
		$ids_to_delete = array();
		foreach ($ids as $id)
		{
			$query = $db->getQuery(true);
			$query->select($column)->from($table_name)->where("id = " . $id);
			$db->setQuery($query);
			$parent_id = $db->loadResult();

			$childs = $this->getChilds($id, $table_name, $column);
			if ($parent_id) {
				foreach ($childs as $child)
				{
					$db->setQuery(
						"UPDATE " . $table_name .
						" SET " . $column . " = '" . $parent_id . "'" .
						" WHERE id = " . $child
					);
					$db->execute();
				}
			}
			else {
				foreach ($childs as $child)
				{
					$db->setQuery(
						"UPDATE " . $table_name .
						" SET " . $column . " = NULL"  .
						" WHERE id = " . $child
					);
					$db->execute();
				}
			}

			if (!array_search($id, $ids_to_delete)) {
				$ids_to_delete[] = $id;
			}
		}
		$this->deleteRows($ids_to_delete, $table_name);
	}

	/**
	 * Delete the selected rows and all of their childs
	 * @param $ids   ids of selected rows
	 * @param $table_name   table's name
	 * @param $column   column name of the database join element
	 *
	 */
	public function deleteDBJoinRecursively ($ids, $table_name, $column) {
		if (!$ids) {
			return;
		}
		else {
			$this->deleteRows($ids,$table_name);
			foreach ($ids as $id) {
				$this->deleteDBJoinRecursively($this->getChilds($id,$table_name,$column), $table_name, $column);
			}
		}
	}

	/**
	 * Main function to get data via ajax and call the methods
	 */
	public function ondelete () {
		$selectedIds = $_POST["selectedIds"];
		$option_delete = $_POST["option_delete"];
		$table_name = $_POST["table_name"];
		$db_join = $_POST["db_join_column"];

		if (!$db_join) {
			$this->deleteRows($selectedIds, $table_name);
		}
		else {
			if ($option_delete === '1') {
				$this->deleteDBJoinRecursively($selectedIds, $table_name, $db_join["name"]);
			}
			else {
				$this->deleteDBJoinSingle($selectedIds, $table_name, $db_join["name"]);
			}
		}
	}

	public function onloadJavascriptInstance($args)
	{
		$opts             = $this->getElementJSOptions();
		$opts->table_name = $this->getModel()->getTable()->get("db_table_name");
		$opts->db_join_column = $this->getDatabaseJoin();
		$opts->option_delete = $this->getParams()["option_delete"];
		$opts->url_site = COM_FABRIK_LIVESITE;
		$opts             = json_encode($opts);
		$this->jsInstance = "new FbListDelete_tree_node($opts)";

		return true;
	}

	public function loadJavascriptClassName_result()
	{
		return 'FbListDelete_tree_node';
	}
}
