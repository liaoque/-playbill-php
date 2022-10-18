<?php


class UploadsController extends \Yaf_Controller_Abstract
{
    public function indexAction()
    {
        $appDirectory = Yaf_Dispatcher::getInstance()->getApplication()->getAppDirectory();
        $storage = new \Upload\Storage\FileSystem($appDirectory . '../public/upload');
        $file = new \Upload\File('file', $storage);

        // Optionally you can rename the file on upload
        $new_filename = uniqid();
        $file->setName($new_filename);

        // Validate file upload
        // MimeType List => http://www.iana.org/assignments/media-types/media-types.xhtml
        $file->addValidations(array(
            // Ensure file is of type "image/png"
            new \Upload\Validation\Mimetype(array('image/png', 'image/gif', 'image/jpeg', 'image/jpg')),

            //You can also add multi mimetype validation
            //new \Upload\Validation\Mimetype(array('image/png', 'image/gif'))

            // Ensure file is no larger than 5M (use "B", "K", M", or "G")
            new \Upload\Validation\Size('5M')
        ));

//        // Access data about the file that has been uploaded
//        $data = array(
//            'name' => $file->getNameWithExtension(),
//            'extension' => $file->getExtension(),
//            'mime' => $file->getMimetype(),
//            'size' => $file->getSize(),
//            'md5' => $file->getMd5(),
//            'dimensions' => $file->getDimensions()
//        );

        $file->upload();

    }
}
