<?php

namespace Adopet\Utils;

class PhotoUpload
{
    const EXTENSAO = ['jpeg', 'jpg', 'png'];

    public function addPhoto($path, $upload, $object)
    {
        $extension = pathinfo($upload['name'], PATHINFO_EXTENSION);
        if (!in_array($extension, self::EXTENSAO)) {
            unset($object,$_FILES);
            $obError = new Errors();
            $obError->addMessage('upload', "Formato inválido");
            return false;
        }

        $photoTmp = $upload['tmp_name'];     // armazena o nome temporario da foto
        $newNamePhoto = uniqid().".$extension";               // cria uma novo nome para a foto ser unica e não haver substituição de arquivo
        move_uploaded_file($photoTmp,
            $path.$newNamePhoto);  // move o arquivo temporario com novo nome para a pasta de destino

        if (file_exists($path.$object->getPhoto()) && $object->getPhoto() !== "") { // verifica se existe alguma foto para aquele perfil
            unlink($path.$object->getPhoto()); //exclui a foto antiga do perfil
        }
        $object->setPhoto($newNamePhoto);
        return true;
    }
}