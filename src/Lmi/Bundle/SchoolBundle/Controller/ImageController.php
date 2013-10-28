<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/ 
namespace Lmi\Bundle\SchoolBundle\Controller;

use Lmi\Bundle\SchoolBundle\Entity\Image;
use Lmi\Bundle\SchoolBundle\Form\Map\Image as ImageMap;
use Lmi\Bundle\SchoolBundle\Form\ImageType;
use Lmi\Bundle\SchoolBundle\Form\TestType;
use Lmi\Bundle\SchoolBundle\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        /** @var $image Image */
        $image = $this->get('lmi_school.service.image')->get($id);

        switch ($size) {
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
                $imagePath = $image->getThumb();
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
     * @return array
     */
    public function addAction()
    {
        return $this->render('LmiSchoolBundle:Image:add.html.twig', array(
            'form' => $form = $this->createForm(new ImageType(), new ImageMap())->createView()
        ));
    }

    /**
     * @param Request $request
     */
    public function uploadAction(Request $request)
    {
        $form = $this->createForm(new ImageType());
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var ImageService $imageService  */
            $imageService = $this->get('lmi_school.service.image');

            /** @var UploadedFile $file  */
            $file = $form->get('file')->getData();

            print '<pre>'; var_dump($imageService->save($file, '')); print '</pre>'; die();
        }

        return new Response('smth goes wrong');
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function yandexAction()
    {
        $form = $this->createForm(new TestType());
        return array(
            'form' => $form->createView()
        );
    }

}
