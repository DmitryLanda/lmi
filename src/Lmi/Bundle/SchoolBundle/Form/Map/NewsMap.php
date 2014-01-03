<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/

namespace Lmi\Bundle\SchoolBundle\Form\Map;

use DateTime;
use Lmi\Bundle\SchoolBundle\Entity\Image;
use Lmi\Bundle\SchoolBundle\Entity\News;
use Lmi\Bundle\SchoolBundle\Form\Type\ImageType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class NewsMap
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $content;

    /**
     * @var DateTime
     */
    private $showDate;

    /**
     * @var array
     */
    private $images;

    /**
     * @var string
     */
    private $author;

    /**
     * @var array
     */
    private $currentImages = array();

    /**
     * @param string $content
     * @return NewsMap
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $images
     * @return NewsMap
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @return array
     */
    public function getCurrentImages()
    {
        return $this->currentImages;
    }

    /**
     * @param array $imageIds
     * @return NewsMap
     */
    public function setCurrentImages(array $imageIds)
    {
        $this->currentImages = $imageIds;

        return $this;
    }

    /**
     * @param \DateTime $showDate
     * @return NewsMap
     */
    public function setShowDate($showDate)
    {
        $this->showDate = $showDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getShowDate()
    {
        return $this->showDate;
    }

    /**
     * @param string $title
     * @return NewsMap
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $author
     * @return NewsMap
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param News $news
     * @return NewsMap
     */
    public function fillFromModel(News $news)
    {
        $this->setShowDate($news->getShowDate())
            ->setTitle($news->getTitle())
            ->setContent($news->getContent())
            ->setAuthor($news->getAuthor())
            ->setCurrentImages($news->getImages());

        return $this;
    }

    /**
     * @param News $news
     * @return NewsMap
     */
    public function updateModel(News $news)
    {
        $news->setShowDate($this->getShowDate())
            ->setTitle($this->getTitle())
            ->setContent($this->getContent());

        return $this;
    }
}
