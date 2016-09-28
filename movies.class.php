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
		$id = intval($id);
		$sql = "DELETE FROM movies WHERE id ='{$id}'";
		return $db->query($sql);
	}


	public static function listABC($db)
	{
		$sql = "SELECT * FROM movies ORDER BY title COLLATE utf8_unicode_ci";
		return $db->query($sql);
	}

	public static function findByTitle($title, $db)
	{
		$title = $db->escape($title);
		$sql = "SELECT * FROM movies WHERE Title = '{$title}'";
		return $db->query($sql);
	}

	public static function findByActor($actor_name, $db)
	{
		$actor_name = $db->escape($actor_name);
		$sql = "SELECT * FROM movies WHERE Stars LIKE ('%{$actor_name}%')";
		return $db->query($sql);
	}

	public static function import($db)
	{
		if (!empty($_FILES)) {
			
			copy($_FILES['file']['tmp_name'], __DIR__ . basename($_FILES['file']['name']));
		}
		
		$file = __DIR__.$_FILES['file']['name'];
	
		$text = file_get_contents($file);

		$afterPreg = preg_split('%Title: |Release Year: |Format: |Stars: %', $text);

		$imp = implode($afterPreg);

		$good_text = trim($imp);

		file_put_contents($file, "\n" . $good_text);

		$sql = "LOAD DATA INFILE '{$file}'
				INTO TABLE movies
				FIELDS TERMINATED BY '\\n'";

		$db->query($sql);

		unlink($file);
	}
}
