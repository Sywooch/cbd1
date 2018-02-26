<?php
/**
 * Created by PhpStorm.
 * User: slava
 * Date: 19.01.17
 * Time: 14:55
 */

namespace app\components;

use api\Documents;
use yii\base\Component;
use Yii;

/**
 * @property string $url
 * @property string $path
 * @property string $fullpath
 * @property string $login
 * @property string $password
 * */

class ApiUpload extends Component
{

    public $url = 'http://upload.docs-sandbox.ea.openprocurement.org';
    public $path = '/';
    public $login = 'umstrade.com.ua';
    public $password = 'a095a7b6f1dd4092a54c6fd0b23670a0';


    public function getFullpath(){
        return $this->url . $this->path;
    }

    public function upload($document){
        return $this->register($document);
    }

    private function register($document){
        $login = $this->login;
        $password = $this->password;

        $uploadPath = $this->request('register', 'POST', [
            'data' => [
                'hash' => 'md5:' . md5_file($document),
            ],
        ], [
            'Authorization' => 'Basic '. base64_encode("$login:$password") ,
        ]);

        return $this->doUpload($uploadPath['upload_url'], $document);
    }

    private function doUpload($address, $document){
        $login = $this->login;
        $password = $this->password;

        $file = file_get_contents($document);

        define('MULTIPART_BOUNDARY', '--------------------------'.microtime(true));

        $header = 'Content-Type: multipart/form-data; boundary='.MULTIPART_BOUNDARY."\r\n".
            "Authorization:Basic " . base64_encode("$login:$password") . "\r\n";

        $content =  "--".MULTIPART_BOUNDARY."\r\n".
            "Content-Disposition: form-data; name=\"file\"; filename=\"".basename($document)."\"\r\n".
            "Content-Type: text/plain\r\n\r\n".
            $file."\r\n";
        $content .= "--".MULTIPART_BOUNDARY."--\r\n";
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => $header,
                'content' => $content,
            ]
        ]);

        // TRY SENDING FORM DATA WITH FILE
        try{
            $data = json_decode(file_get_contents($address, false, $context), true);
        }catch (\Exception $e){
            return false;
        }

        return array_merge(
            $data,
            [
                'address' => str_replace($this->url . $this->path . 'upload/', '', $address),
            ]);
    }

    public function reUpload(Documents $document, $path){
        return $this->doUpload($this->url . $this->path . 'upload/' . $document->id, $path);
    }

    private function request($address='', $method='GET', $data = [], $additionalHeaders = []){
        $headers = "";
        foreach($additionalHeaders as $index => $header){
            $headers .= $index . ":" . $header . "\r\n";
        }
        $postdata = json_encode($data);
        $opts = [
            'http' => [
                'method' => $method,
                'header' => "Content-type:application/json\r\n" . $headers,
                'content' => $method == "POST" ? $postdata : [],
            ]
        ];
        try{
            $data = json_decode(
                file_get_contents($this->fullpath . $address, false, stream_context_create($opts)),
                true
            );
        }
        catch(\Exception $e){
            Yii::error("An error has been occured while getting data from '" . $this->fullpath . $address . "'");
            $data = false;
        }
        return $data;
    }

}
