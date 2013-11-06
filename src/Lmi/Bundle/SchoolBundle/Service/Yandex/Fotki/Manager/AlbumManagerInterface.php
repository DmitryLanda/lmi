<?php
/*
* Copyright © FarHeap Solutions
*
* For a full copyright notice, see the COPYRIGHT file.
*/

/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */
interface AlbumManagerInterface
{
    /**
     * @param integer $id
     * @return AlbumInterface|null
     */
    public function getById($id);

    /**
     * @param string $title
     * @return AlbumInterface|null
     */
    public function getByTitle($title);

    /**
     * @param string $title
     * @param AlbumInterface|null $parent
     * @return AlbumInterface
     */
    public function create($title, AlbumInterface $parent = null);

    /**
     * @return AlbumInterface[]
     */
    public function getAll();
}
 