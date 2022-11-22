<?php


class UploadsController extends \Yaf_Controller_Abstract
{
    public function indexAction()
    {
        $appDirectory = Yaf_Dispatcher::getInstance()->getApplication()->getAppDirectory();
        $storage = new \Upload\Storage\FileSystem($appDirectory . '/../public/upload');
        $file = new \Upload\File('file', $storage);

        // Optionally you can rename the file on upload
        $new_filename = uniqid();
        $file->setName($new_filename);

        // Validate file upload
        // MimeType List => http://www.iana.org/assignments/media-types/media-types.xhtml
        $file->addValidations(array(
            new \Upload\Validation\Mimetype(array('image/png', 'image/gif', 'image/jpeg', 'image/jpg')),
            new \Upload\Validation\Size('5M')
        ));

        $nameWithExtension = $file->getNameWithExtension();
        $url = Yaf_Dispatcher::getInstance()->getApplication()->getConfig()->get('application.base_host');
        $data = array(
            'name' => $file->getNameWithExtension(),
            'extension' => $file->getExtension(),
            'mime' => $file->getMimetype(),
            'size' => $file->getSize(),
            'url' => "{$url}/upload/{$nameWithExtension}",
            'dimensions' => $file->getDimensions()
        );

        $file->upload();
        return $this->getView()->assign($data);
    }
}
