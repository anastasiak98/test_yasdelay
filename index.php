<html>
<head>
	Page for test task <br>
</head>

<body>
	Description & form

	<form enctype="multipart/form-data" action="sendform.php" name="feedback_form" method="post" target="self">
		<fieldset>
			<legend>Feedback form</legend>
			<p>
				<label>ссылка на страницу:</label>		<!-- поле ссылки на стр -->
				<input type="url" name="link" placeholder="ссылка на страницу получателя" required></p>
			<p>
				<label>текст сообщения:</label>
				<input type="text" name="text" placeholder="текст отправляемого сообщения" >
			</p>
			<p>
				<label>загрузить файл</label>			<!-- поле для загрузки файла -->
				<input type="file" name="file">
			</p>
			<p>
				<input type="submit" name="submit">		<!-- кнопка отправки -->
			</p>

		</fieldset>

	</form>
</body>
</html>