<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/ 
namespace Lmi\Bundle\SchoolBundle\Service;

use Doctrine\ORM\EntityManager;
use Exception;
use Lmi\Bundle\SchoolBundle\Entity\Image;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\ImageFactory;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\YandexFotkiService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class ImageService
{
    private $imageHostingService;

    private $entityManager;

    private $imageRepository;

    private $logger;

    public function __construct(EntityManager $em, LoggerInterface $logger, array $yandexOptions)
    {
        $this->imageHostingService = new YandexFotkiService($yandexOptions['fotki']);
        $this->entityManager = $em;
        $this->imageRepository = $em->getRepository('LmiSchoolBundle:Image');
        $this->logger = $logger;
    }

    public function save(UploadedFile $uploadedFile, $albumType)
    {
        $file = $uploadedFile->move('/tmp', md5(time()));

        try {
            $this->logger->info('Uploading image to hosting...');
            $image = $this->imageHostingService->uploadImage($file, $albumType);

            $this->logger->info('Saving image information to database...');
            $this->entityManager->persist($image);
//            $this->entityManager->flush();
        } catch (Exception $e) {
            $this->logger->error('Uploading image failed');
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());

            $image = ImageFactory::createDefaultImage();
        }

        @unlink($file->getRealPath());

        return $image;
    }

    /**
     * @param $id
     * @return Image
     */
    public function get($id)
    {
        $this->logger->info('Fetching image information from database...');
        $image = $this->imageRepository->findOneByImageId($id);

        if (!$image) {
            $this->logger->info('Image information not found. Fetching it from hosting...');
            try {
                $image = $this->imageHostingService->getImage($id);

                $this->logger->info('Image information was found. Saving it to database...');
                $this->entityManager->persist($image);
                $this->entityManager->flush();
            } catch (Exception $e) {
                $this->logger->error('Fetching image failed');
                $this->logger->error($e->getMessage());
                $this->logger->error($e->getTraceAsString());

                $image = ImageFactory::createDefaultImage();
            }
        }

        return $image;
    }

}
