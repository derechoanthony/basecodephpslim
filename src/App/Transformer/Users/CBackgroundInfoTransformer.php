<?php

namespace App\Transformer\Users;

use App\Transformer\CTransformer;
use App\Helper\CCommon;
use \Datetime;

class CBackgroundInfoTransformer extends CTransformer {

    /**
     * Transform the personal info response 
     *
     * @param array $item
     * @return json object
     */
    public function transform($item)
    {
        $data = [      
            'backgroundInfoId' => $item->id,
            'backgroundInfoUserId' => $item->user_id,
            'backgroundInfoCurrentSalary' => $item->current_salary,
            'backgroundInfoCurrentBonuses' => $item->current_bonuses,
            'backgroundInfoFamilyMembers' =>  $this->subTransformFamilyMembers($item->familyMembers),
            'backgroundInfoBackgroundQuestions' => $this->subTransformBackgroundQuestions($item->backgroundQuestions),
            'backgroundInfoEducationalBackgrounds' => $this->subTransformEducationalBackgrounds($item->educationalBackgrounds),
            'backgroundInfoTrainingExperiences' => $this->subTransformTrainingExperiences($item->trainingExperiences),
            'backgroundInfoBusinessExperiences' => $this->subTransformBusinessExperiences($item->businessExperiences),
            'backgroundInfoWorkExperiences' => $this->subTransformWorkExperiences($item->workExperiences),
            'backgroundInfoReferences' => $this->subTransformReferences($item->references),
            'backgroundInfoBackgroundAttachments' => $this->subTransformBackgroundAttachments($item->backgroundAttachments)      
        ];

        return $data;
    }

    private function subTransformFamilyMembers($list){
        $output = [];

        foreach($list as $member){
            $output[] = [
                'familyMemberId' => $member->id,
                'familyMemberFirstName' => $member->first_name,
                'familyMemberMiddleName' => $member->middle_name,
                'familyMemberLastName' => $member->last_name,
                'familyMemberBirthdate' => $member->birthdate ? $member->birthdate->format(CCommon::DATE_FORMAT_FOR_DISPLAY) : null,
                'familyMemberOccupation' => $member->occupation,
                'familyMemberBusiness' => $member->business,
                'familyMemberRole' => $member->role
            ];
        }

        return $output;
    }

    private function subTransformBackgroundQuestions($list){
        $output = [];

        foreach($list as $question){
            $output[] = [
                'backgroundQuestionId' => $question->id,
                'backgroundQuestionQuestionKey' => $question->question_key,
                'backgroundQuestionAnswer' => ($question->answer) ? 1 : 0,
                'backgroundQuestionRemarks' => $question->remarks
            ];
        }

        return $output;
    }

    private function subTransformEducationalBackgrounds($list){
        $output = [];

        foreach($list as $education){
            $output[] = [
                'educationalBackgroundId' => $education->id,
                'educationalBackgroundTitle' => $education->title,
                'educationalBackgroundSchoolName' => $education->school_name,
                'educationalBackgroundSchoolAddress' => $education->school_address,
                'educationalBackgroundInclusiveYears' => $education->inclusive_years,
                'educationalBackgroundDegree' => $education->degree,
                'educationalBackgroundAwards' => $education->awards
            ];
        }

        return $output;
    }

    private function subTransformTrainingExperiences($list){
        $output = [];

        foreach($list as $training){
            $output[] = [
                'trainingExperienceId' => $training->id,
                'trainingExperienceTrainingTitle' => $training->training_title,
                'trainingExperienceOrganizer' => $training->organizer,
                'trainingExperienceDateAttended' => $training->date_attended ? $training->date_attended->format(CCommon::DATE_FORMAT_FOR_DISPLAY) : null
            ];
        }

        return $output;
    }

    private function subTransformBusinessExperiences($list){
        $output = [];

        foreach($list as $business){
            $temp = [
                'businessExperienceId' => $business->id,                
                'businessExperienceBusinessName' => $business->business_name,
                'businessExperiencePosition' => $business->position,
                'businessExperienceTelNum' => $business->tel_num,
                'businessExperienceBusinessNature' => $business->business_nature,
                'businessExperienceYearsExperience' => $business->years_experience,
                'businessExperienceOwnershipType' => $business->ownership_type,
                'businessExperienceCity' => [
                    'cityId' => ($business->city === null) ? null : $business->city->id,
                    'cityName' => ($business->city === null) ? null : $business->city->name
                ],
                'businessExperienceProvince' => [
                    'provinceId' => ($business->city === null)? null : $business->city->province->id,
                    'provinceName' => ($business->city === null)? null : $business->city->province->name
                ],
                'businessExperienceStreet' => $business->street,
                'businessExperienceCountry' => $business->country,
                'businessExperienceZipcode' => $business->zipcode
            ];

            $output[] = $temp;
        }

        return $output;
    }

    private function subTransformWorkExperiences($list){
        $output = [];

        foreach($list as $work){
            $output[] = [
                'workExperienceId' => $work->id,
                'workExperienceCompanyName' => $work->company_name,
                'workExperiencePosition' => $work->position,
                'workExperienceBusinessType' => $work->business_type,
                'workExperienceAddress' => $work->address,
                'workExperienceDateEmployed' => $work->date_employed ? $work->date_employed : null,
                'workExperienceResponsibilities' => $work->responsibilities
            ];
        }

        return $output;
    }

    private function subTransformReferences($list){
        $output = [];

        foreach($list as $reference){
            $output[] = [
                'referenceId' => $reference->id,
                'referenceReferenceName' => $reference->reference_name,
                'referencePosition' => $reference->position,
                'referenceReferenceType' => $reference->reference_type,
                'referenceRelationship' => $reference->relationship,
                'referenceYearsKnown' => $reference->years_known,
                'referenceContactNum' => $reference->contact_num
            ];
        }

        return $output;
    }

    private function subTransformBackgroundAttachments($list){
        $output = [];

        foreach($list as $attachment){
            $output[] = [
                'backgroundAttachmentId' => $attachment->id,
                'backgroundAttachmentFileName' => $attachment->file_name,
                'backgroundAttachmentAttachmentType' => $attachment->attachment_type,
            ];
        }

        return $output;
    }
}