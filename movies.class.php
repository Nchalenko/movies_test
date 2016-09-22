<?php

class movies
{

	public static function showALL($db)
	{
		$sql = "SELECT * FROM movies";
		return $db->query($sql);
	}

	public static function add($db)
	{
		$title = $db->escape($_POST['title']);
		$year = $db->escape($_POST['year']);
		$format = $db->escape($_POST['format']);
		$stars = $db->escape($_POST['stars']);

		$sql = "INSERT INTO movies
				SET Title = '{$title}',
				ReleaseYear = '{$year}',
				Format = '{$format}',
				Stars = '{$stars}'";
		return $db->query($sql);
	}

	public static function delete($id, $db)
	{
		$sql = "DELETE FROM movies WHERE id ='{$id}'";
		return $db->query($sql);
	}

	public static function showInfo($id, $db)
	{
		$sql = "SELECT * FROM movies WHERE id ='{$id}'";
		return $db->query($sql);
	}

	public static function listABC($db)
	{
		$sql = "SELECT * FROM movies ORDER BY title";
		return $db->query($sql);
	}

	public static function findByTitle($title, $db)
	{
		$sql = "SELECT * FROM movies WHERE Title = '{$title}'";
		return $db->query($sql);
	}

	public static function findByActor($actor_name, $db)
	{
		$sql = "SELECT * FROM movies WHERE Stars LIKE ('%{$actor_name}%')";
		return $db->query($sql);
	}

	public static function import($db)
	{

		if (!empty($_FILES)) {
			copy($_FILES['file']['tmp_name'], "D:/" . basename($_FILES['file']['name']));
		}

		$text = file_get_contents("D:/" . $_FILES['file']['name']);

		$afterPreg = preg_split('%Title: |Release Year: |Format: |Stars: %', $text);

		$imp = implode($afterPreg);

		$good = trim($imp);

		file_put_contents("D:/" . $_FILES['file']['name'], "\n" . $good);

		$import = 'D:/' . $_FILES['file']['name'];

		$db = new DB('localhost', 'root', '', 'webbylab_test');


		$sql = "LOAD DATA INFILE '" . $import . "'
INTO TABLE movies
FIELDS TERMINATED BY '\\n'
LINES TERMINATED BY '\\n'";

		$db->query($sql);

		unlink($import);
		header('location: /');

	}
}