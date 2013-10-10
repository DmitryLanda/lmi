<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/ 
namespace Lmi\Bundle\SchoolBundle\Twig;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class LmiSchoolExtension extends \Twig_Extension
{
    private $educationMap;

    private $categoryMap;

    function __construct(array $educationMap, array $categoryMap)
    {
        $this->educationMap = $educationMap;
        $this->categoryMap = $categoryMap;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('humanize_education', array($this, 'humanizeEducation')),
            new \Twig_SimpleFilter('humanize_category', array($this, 'humanizeCategory')),
        );
    }

    /**
     * @param integer|string $key
     * @return string
     */
    public function humanizeEducation($key)
    {
        $educationTranslatable = $this->educationMap[$key];

        if (!$educationTranslatable) {
            $educationTranslatable = 'lmi.school.common.teacher.education.not_specified';
        }

        return $educationTranslatable;
    }

    /**
     * @param integer|string $key
     * @return string
     */
    public function humanizeCategory($key)
    {
        $categoryTranslatable = $this->categoryMap[$key];

        if (!$categoryTranslatable) {
            $categoryTranslatable = 'lmi.school.common.teacher.category.not_specified';
        }

        return $categoryTranslatable;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'lmi_school_extension';
    }

}
