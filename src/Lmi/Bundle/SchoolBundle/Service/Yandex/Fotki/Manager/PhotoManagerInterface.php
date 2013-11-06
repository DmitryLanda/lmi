<?php
/*
* Copyright © FarHeap Solutions
*
* For a full copyright notice, see the COPYRIGHT file.
*/

namespace Lmi\Bundle\SchoolBundle\Service\Yandex\Fotki\Manager;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */
interface PhotoManagerInterface
{

    public function getById($id);

    public function save(File $file, $album);
}
 