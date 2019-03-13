<?php

namespace App\Wrapper;

use App\Model\BackgroundAttachment;
use App\Model\BackgroundInfo;
use App\Model\BackgroundQuestion;
use App\Model\BusinessExperience;
use App\Model\EducationalBackground;
use App\Model\FamilyMember;
use App\Model\Reference;
use App\Model\TrainingExperience;
use App\Model\WorkExperience;

use App\Wrapper\CWrapper;

class CBackgroundInfoWrapper extends CWrapper
{

    /**
     * Create a new CBackgroundInfoWrapper instance.
     * @return void
     */
    public function __construct()
    {
        $this->attributes = [
            'backgroundInfo' => null,
            'familyMembers' => null,
            'backgroundQuestions' => null,
            'educationalBackgrounds' => null,
            'trainingExperiences' => null,
            'businessExperiences' => null,
            'workExperiences' => null,
            'references' => null,
            'backgroundAttachments' => null
        ];

        $this->parentName = 'backgroundInfo';
        $this->parentModel = new BackgroundInfo();
        
        $this->childSchemas = [
            'backgroundInfoFamilyMembers' => [
                'name' => 'familyMembers', 
                'model' => new FamilyMember()
            ],
            'backgroundInfoBackgroundQuestions' => [
                'name' => 'backgroundQuestions', 
                'model' => new BackgroundQuestion()
            ],
            'backgroundInfoEducationalBackgrounds' => [
                'name' => 'educationalBackgrounds', 
                'model' => new EducationalBackground()
            ],
            'backgroundInfoTrainingExperiences' => [
                'name' => 'trainingExperiences', 
                'model' => new TrainingExperience()
            ],
            'backgroundInfoBusinessExperiences' => [
                'name' => 'businessExperiences', 
                'model' => new BusinessExperience()
            ],
            'backgroundInfoWorkExperiences' => [
                'name' => 'workExperiences', 
                'model' => new WorkExperience()
            ],
            'backgroundInfoReferences' => [
                'name' => 'references', 
                'model' => new Reference()
            ],
            'backgroundInfoBackgroundAttachments' => [
                'name' => 'backgroundAttachments', 
                'model' => new BackgroundAttachment()
            ],
        ];
    }

    // TODO: Try to implement this factory-style
    public function convertToList($collection)
    {
        $output = [];

        foreach($collection as $dataNode){
            $output[] = (new CBackgroundInfoWrapper())->convert($dataNode);
        }

        return $output;
    }
}
