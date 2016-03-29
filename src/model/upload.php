<?php
class uploadModel implements IModel
{
    public $config = array();

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function run()
    {
        if (($this->config['requiresAuth'] && Core::$models['auth']->authenticated) || !$this->config['requiresAuth'])
        {
            if (isset(Core::$REQUESTS['image'])) {
                $uploadDir = $this->config['directory'];
                $imagePath = $_FILES["image"]["name"];
                $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
                $fileName = $this->generateUniqueName();
                $filePath = $uploadDir . $fileName . '.' . $imageType;


                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    die('UPLOAD_ERROR_1');
                }

                if (file_exists($filePath)) {
                    die('UPLOAD_ERROR_2');
                }

                if ($_FILES["image"]["size"] > 500000) {
                    die('UPLOAD_ERROR_3');
                }

                if ($imageType != "jpg" && $imageType != "png" && $imageType != "jpeg"
                    && $imageType != "gif"
                ) {
                    die('UPLOAD_ERROR_4');
                }

                if ($uploadOk == 0) {
                    return false;
                } else {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $filePath)) {
                        die($fileName);
                    } else {
                        die('UPLOAD_ERROR_5');
                    }
                }
            }
        }
        else
        {
            die('UPLOAD_ERROR_0');
        }
    }

    private function generateUniqueName() // NOT UNIQUE YET
    {
        $charset = array(
            'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
            'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
            '0','1','2','3','4','5','6','7','8','9'
        );

        $r = '';

        for ($c=0; $c<5; $c++)
        {
            $r .= $charset[rand(0,count($charset)-1)];
        }

        return $r;
    }
}