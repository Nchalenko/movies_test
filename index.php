<?php

require_once 'db.class.php';
require_once 'movies.class.php';

$db = new DB('localhost', 'root', '', 'webbylab_test');

if (isset($_GET['abc'])) {
	$all = movies::listABC($db);
} elseif (isset($_GET['title'])) {
	$all = movies::findByTitle($_GET['title'], $db);
} elseif (isset($_GET['star'])) {
	$all = movies::findByActor($_GET['star'], $db);
} elseif (isset($_GET['delete'])) {
	$all = movies::delete($_GET['delete'], $db);
	header('location: /');
} elseif (isset($_POST['title']) && isset($_POST['year']) && isset($_POST['format'])) {
	movies::add($db);
	header('location: /');
} elseif (isset($_GET['import'])) {
	movies::import($db);
	header('location: /');
} else {
	$all = movies::showALL($db);
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Библиотека фильмов</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="/">Мои фильмы</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<a style="margin-top: 8px;" class="btn btn-info" href="?abc">Отобразить в алфавитном порядке</a>
			<form method="get" action="/?title" name="title" class="navbar-form navbar-right">
				<div class="form-group">
					<button type="submit" class="btn btn-success">НАЙТИ</button>
					<input name="title" type="text" placeholder="фильм по названию" class="form-control">
				</div>
			</form>
			<form method="get" action="/?star" name="star" class="navbar-form navbar-right">
				<div class="form-group">
					<button type="submit" class="btn btn-success">НАЙТИ</button>
					<input name="star" type="text" placeholder="фильм по актеру" class="form-control">
				</div>
			</form>
		</div>
	</div>
</nav>
<br><br><br><br>
<div class="container">
	<button type="button" class="btn btn-success" data-toggle="modal" data-target="#add">Добавить фильм</button>
	<div class="modal fade" id="add" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Добавить новый фильм:</h4>
				</div>
				<div class="modal-body">
					<form action="/" name="form" method="post">
						<div class="form-group">
							<input style="margin: 10px;" name="title" id="title" type="text" class="form-control" placeholder="Название фильма">
							<input style="margin: 10px;" name="year" id="year" type="text" class="form-control" placeholder="Год выпуска">
							<input style="margin: 10px;" name="format" id="format" type="text" class="form-control" placeholder="Формат">
							<input style="margin: 10px;" name="stars" id="stars" type="text" class="form-control" placeholder="Список актеров">
							<input style="margin-left: 45%;" type="submit" class="btn btn-lg btn-success">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<button style="margin-left: 80%" type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Импорт</button>

	<!-- ИМПОРТ -->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Добавить файл</h4>
				</div>
				<div class="modal-body">
					<form action="?import" name="" method="post" enctype="multipart/form-data">
						<label for="exampleInputFile">
							Прикрепите файл:
						</label>
						<input type="file" name="file" id="exampleInputFile"/>
						<br>
						<button type="submit" class="btn btn-success">
							Импортировать
						</button>
						<br>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<br>
<div class="container">
	<table class="table table-stripped stuff">
		<th>Название</th>
		<th>Год</th>
		<th>Формат</th>
		<th>Актерский состав:</th>
		<th></th>
		<tr>
			<?php
			foreach ($all as $movie){ ?>
			<td><?= htmlentities($movie['Title']) ?></td>
			<td><?= htmlentities($movie['ReleaseYear']) ?></td>
			<td><?= htmlentities($movie['Format']) ?></td>
			<td><?= htmlentities($movie['Stars']) ?></td>
			<td><a class="btn btn-sm btn-danger" href="?delete=<?= $movie['id'] ?>" onclick="return confirmDelete()">Удалить</a></td>
		</tr>
		<?php } ?>
	</table>
</div>
<br><br><br>
<div id="footer">
	<div class="container">
		<p class="muted credit text-right">Movies library © 2016 <a href="https://github.com/Nchalenko/movies_test">Github</a>.</p>
	</div>
</div>
<script>
	function confirmDelete() {
		if (confirm('Подвердить удаление')) {
			return true;
		} else {
			return false;
		}
	}
</script>
</body>
</html>