<?php
/*
* Copyright © FarHeap Solutions
*
* For a full copyright notice, see the COPYRIGHT file.
*/

/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */
interface AlbumInterface
{
    public function getId();

    public function getName();

    public function getPhotoCollectionLink();

    public function getSelfLink();

    public function getAlternativeLink();

    public function getLargeCoverThumb();

    public function getSmallCoverThumb();
}
 