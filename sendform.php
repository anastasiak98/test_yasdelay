<?php
$user_url = stristr($_POST['link'], 'vk.com/');
$pos = strpos($user_url, '/');
$user_url = substr($user_url, $pos+1);
$send_text = $_POST['text'];
$send_file = $_POST['file'];

$method = 'messages.send'; // метод для отправки сообщения пользователю
$method_get_id = 'users.get'; // метод для получения id пользователя через ссылку на его страницу
$version = 5.131;
$token = '67464f5f593db207cbd985fdab159a31f3484b29eec39bcf35be51d492bdf0fee74c828b13f63c1e0c988'; // токен группы, от которой выполняется отправка
$random_id = 0;
$message = $send_text;

//получение id пользователя из заданной ссылки
$id_from_url = "https://api.vk.com/method/$method_get_id?user_ids=$user_url&fields=id&access_token=$token&v=5.131";

$ci_id = curl_init($id_from_url);
curl_setopt($ci_id, CURLOPT_URL, $id_from_url);
curl_setopt($ci_id, CURLOPT_HEADER, 0);
curl_exec($ci_id);
curl_close($ci_id);

$response_id = json_decode(file_get_contents($id_from_url));	// раскодировка json из ответа
foreach ($response_id as $key => $value) {
	if ($key == 'id')
		$user_id = $value;
}

// отправка сообщения в лс пользователю с id из ссылки
$url = "https://api.vk.com/method/$method?domain=$user_url&user_id=$user_id&random_id=$random_id&message=$message&attachment=$send_file&access_token=$token&v=5.131";

$ci = curl_init($url);
curl_setopt($ci, CURLOPT_URL, $url);
curl_setopt($ci, CURLOPT_HEADER, 0);
curl_exec($ci);
curl_close($ci);

// отправка изображения в лс пользователя
// адрес для загрузки фото
$method_load_photo = 'photos.getMessagesUploadServer'; // определение метода загрузки фото на сервер
$upload_server = "https://api.vk.com/method/$method_load_photo?access_token=$token&v=5.131"; // url запроса

// формирование запроса к серверу по url для получения адреса для загрузки фото
$ci_file = curl_init($upload_server);
curl_setopt($ci_file, CURLOPT_URL, $upload_server);
curl_setopt($ci_file, CURLOPT_HEADER, 0);
curl_setopt($ci_file, CURLOPT_RETURNTRANSFER, 1);
$response_file_url = curl_exec($ci_file);
curl_close($ci_file);	// в upload_server лежит upload_url по которой грузит фото на сервер

//$response_file = json_decode(file_get_contents($upload_server));	// раскодировка json из ответа
$response_file = json_decode($response_file_url, true);	// раскодировка json из ответа в массив
//if (is_array($response_file)) {
	foreach ( $response_file as $array_name => $array_value ) {
		foreach ( $array_value as $index => $value ) {	// получение адреса url в виде строки из запроса ci_file
			if ($index == 'upload_url')
				$url_upload_file = $value;		// в url_upload_file лежит ссылка-строка куда передать файл на сервере вк чтобы потом загрузить его в лс
		}
	}
//}

// формирование запроса к серверу по url чтобы передать файл на сервер
$ci_file_load = curl_init();
curl_setopt($ci_file_load, CURLOPT_URL, $url_upload_file);
curl_setopt($ci_file_load, CURLOPT_POST, 1);
curl_setopt($ci_file_load, CURLOPT_POSTFIELDS, [
                'datafile' => curl_file_create(realpath('foto.jpg') , 'multipart/form-data', 'test_name')
            ]
        );
//curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
$returned_fileName = curl_exec($ci_file_load);
curl_close($ci_file_load);

//отправка сообщения с изображением

?>

