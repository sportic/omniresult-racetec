<?php

namespace Sportic\Timing\RaceTecClient\Models;

/**
 * Class Result
 * @package Sportic\Timing\RaceTecClient\Models
 */
class Result extends AbstractModel
{
    protected $posGen;
    protected $bib;
    protected $fullName;
    protected $href;
    protected $time;
    protected $category;
    protected $posCategory;
    protected $gender;
    protected $posGender;

    /**
     * @return array
     */
    public static function getLabelMaps()
    {
        return [
            'posGen'      => 'Pos',
            'bib'         => 'Race No',
            'fullName'    => 'Name',
            'time'        => 'Time',
            'category'    => 'Category',
            'posCategory' => 'Cat Pos',
            'gender'      => 'Gender',
            'posGender'   => 'Gen Pos'
        ];
    }
}
