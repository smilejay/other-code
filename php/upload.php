<?php
// 实现多个文件上传并保存的后端

//设置编码为UTF-8，以避免中文乱码
header('Content-Type:text/html;charset=utf-8');

//获取多个文件的信息，注意：这里的键名不包含[]
$fileArray = $_FILES['upload_file'];

$upload_dir = 'img/'; //保存上传文件的目录

foreach ( $fileArray['error'] as $key => $error ) {
	if ( $error == UPLOAD_ERR_OK ) { //PHP常量UPLOAD_ERR_OK值为0，表示文件上传成功
		$temp_name = $fileArray['tmp_name'][$key];
		$file_name = $fileArray['name'][$key];
		move_uploaded_file($temp_name, $upload_dir.$file_name);
		echo '上传[文件'.$key.':'.$file_name.']成功!</br>';
	}else {
		echo '上传[文件'.$key.':'.$file_name.']失败!</br>';
	}
}
?>
