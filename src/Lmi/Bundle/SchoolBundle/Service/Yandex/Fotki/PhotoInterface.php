<?php
/*
* Copyright © FarHeap Solutions
*
* For a full copyright notice, see the COPYRIGHT file.
*/

/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */
interface PhotoInterface
{
    /**
     * @return string
     */
    public function getAlternativeLink();

    /**
     * @return string
     */
    public function getExtraLarge();

    /**
     * @return string
     */
    public function getLarge();

    /**
     * @return string
     */
    public function getMedium();

    /**
     * @return string
     */
    public function getSmall();

    /**
     * @return string
     */
    public function getThumb();

    /**
     * @return integer
     */
    public function getImageId();
}
 