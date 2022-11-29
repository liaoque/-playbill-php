<?php


class UploadsController extends \Yaf_Controller_Abstract
{
    public function indexAction()
    {

        $factory = new \Oss\Factory();
        $file = new \Upload\File('file', $factory->getStorage());

        // Optionally you can rename the file on upload
        $new_filename = uniqid();
        $file->setName($new_filename);

        // Validate file upload
        // MimeType List => http://www.iana.org/assignments/media-types/media-types.xhtml
        $file->addValidations(array(
            new \Upload\Validation\Mimetype(array('image/png', 'image/gif', 'image/jpeg', 'image/jpg')),
            new \Upload\Validation\Size('5M')
        ));

        $data = array(
            'name' => $file->getNameWithExtension(),
            'extension' => $file->getExtension(),
            'mime' => $file->getMimetype(),
            'size' => $file->getSize(),
            'dimensions' => $file->getDimensions()
        );

        $file->upload();
        $url = \AppUtils\Config::baseUrl();
        $data['url'] = $url . $factory->getStorage()->getFilePath();

        return $this->getView()->assign($data);
    }
}
