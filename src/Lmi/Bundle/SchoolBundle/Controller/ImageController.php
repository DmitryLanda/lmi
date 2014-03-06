<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/
namespace Lmi\Bundle\SchoolBundle\Controller;

use Gaufrette\Filesystem;
use Gaufrette\Adapter\Local as LocalAdapter;
use Lmi\Bundle\SchoolBundle\Form\Map\Image as ImageMap;
use Lmi\Bundle\SchoolBundle\Form\ImageType;
use Lmi\Bundle\SchoolBundle\Model\Image;
use Lmi\Bundle\SchoolBundle\Model\Manager\AlbumManager;
use Lmi\Bundle\SchoolBundle\Model\Manager\ImageManager;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Model\ImageInterface;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\YandexFotkiService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class ImageController extends Controller
{
    public function getAction($id, $size)
    {
        $response = new Response();
        $response->setETag(md5($id . md5($size)));
        $response->setPublic();
        if ($response->isNotModified($this->getRequest())) {
            $response->setNotModified();

            return $response;
        }

        $image = $this->getImageManager()->getOneById($id);

        switch ($size) {
            case 'orig':
                $imagePath = $image->getOriginal();
                break;
            case 'extra':
                $imagePath = $image->getExtraLarge();
                break;
            case 'large':
                $imagePath = $image->getLarge();
                break;
            case 'medium':
                $imagePath = $image->getMedium();
                break;
            case 'small':
                $imagePath = $image->getSmall();
                break;
            case 'thumb':
                $imagePath = $image->getThumbnail();
                break;
            default:
                $imagePath = $image->getSmall();
                break;
        }

        $response = new StreamedResponse(function() use ($imagePath) {
                print file_get_contents($imagePath);
            },
            200,
            array('Content-Type' => 'image/png'));
        $response->setPublic()
            ->setETag(md5($id . md5($size)));

        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $image = $request->files->get('image');
        $image = $image->move('/tmp/media/');
        $content = file_get_contents($image->getRealPath());

        $url = null;
        if ($albumId = $request->get('album')) {
            $album = $this->getAlbumManager()->getOneById($albumId);
            $url = $album->getPhotoCollectionUrl();
        }

        $this->getImageManager()->create(md5(time()), $content, $url);

        return new Response('', 201);
    }

    /**
     * @return Response
     */
    public function removeAction()
    {
        $ids = $this->getRequest()->get('id');
        $ids = (array) $ids;

        foreach ($ids as $id) {
            $this->getImageManager()->remove($id);
        }

        return new Response('', 204);
    }


    public function yandexAction(Request $request)
    {
        return $this->render('LmiSchoolBundle:Image:yandex.html.twig', array());
    }

    /**
     * @return ImageManager
     */
    private function getImageManager()
    {
        return $this->get('yandex.fotki.manager.image');
    }

    /**
     * @return AlbumManager
     */
    private function getAlbumManager()
    {
        return $this->get('yandex.fotki.manager.album');
    }

}
