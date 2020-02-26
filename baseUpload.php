<?php 
//上传base64图片
function base64_upload($base64, $input_options=array()) {
    $base64_image = str_replace(' ', '+', $base64);
    //post的数据里面，加号会被替换为空格，需要重新替换回来，如果不是post的数据，则注释掉这一行
    //定制化设这 覆盖默认设置
    $default_options =  array();
    $default_options['custom_path'] =   date("Y/m/d/");
    $options    =   array_merge($default_options,$input_options);

    $options['save_path']   =   './'.$options['custom_path'];
    $savePath = $options['save_path'];
    // 检查上传目录
    if(!is_dir($savePath)) {
        // 检查目录是否编码后的
        if(is_dir(base64_decode($savePath))) {
            $savePath   =   base64_decode($savePath);
        }else{
            // 尝试创建目录
            if(!mkdir($savePath)){
                $this->error  =  '上传目录'.$savePath.'不存在';
                return false;
            }
        }
    }else {
        if(!is_writeable($savePath)) {
            $this->error  =  '上传目录'.$savePath.'不可写';
            return false;
        }
    }

    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)){
        //匹配成功
        if($result[2] == 'jpeg'){
            $image_name = uniqid().'.jpg';
            //纯粹是看jpeg不爽才替换的
        }else{
            $image_name = uniqid().'.'.$result[2];
        }
        $image_file = $options['save_path']."/{$image_name}";
        //服务器文件存储路径
        if (file_put_contents($image_file, base64_decode(str_replace($result[1], '', $base64_image)))){
            // var_dump($image_file);
            return $image_file;
        }else{
            return '';
        }
    }else{
        return '';
    }
}
$option['custom_path'] = "uploads/";
var_dump($_POST);
if (!empty($_POST['image'])) {
    $info = file_get_contents($_POST['image']);
    $size = strlen($info);  #  单位是字节
    var_dump($size);

    $url = base64_upload($_POST['image'],$option);
    var_dump($url);
}
