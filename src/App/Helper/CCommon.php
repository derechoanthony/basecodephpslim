<?php

namespace App\Helper;

class CCommon
{
    /**
     *  Data encryption
     */
    const ENCRYPT = 'encrypt';
    /**
     * Total number of items per page used in pagination
     *
     * @var string
     */
    const ITEMS_PER_PAGE = 10;
    const ITEMS_PER_PAGE_MEETING_INVITATION_PANE = 1;

    /**
     * Error message for pagination exceeding the max number of pages
     *
     * @var string
     */
    const PAGINATION_EXCEED_PAGES = 'Exceeded the number of pages to view';

    /**
     *  Date format that the front-end date picker accepts
     *
     * @var string
     */
    const DATE_FORMAT = 'Y-m-d';
    const DB_DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * Date format to be displayed
     *
     * @var string
     */
    const DATE_FORMAT_FOR_DISPLAY = 'm/d/Y';

    /**
     * Error message for empty metrics
     *
     * @var string
     */
    const METRICS_NOT_FOUND = 'Metrics not found';

    /**
     * Date format saved from the database (timestamp)
     *
     * @var string
     */
    const DB_FORMAT = 'Y-m-d';

    /**
     * Time format to be displayed
     *
     * @var string
     */
    const TIME_FORMAT_FOR_DISPLAY = 'G:i';

    /**
     * Cycle time string for values within the day
     *
     * @var string
     */
    const SAME_DAY_AGING_VALUE = 'Less than a day';

    /**
     * Starting phases for each type of application
     *
     * @var string
     */
    const STARTING_PHASE = [
        'A' => 1,
        'B' => 1,
        'C' => 2,
        'a' => 1,
        'b' => 1,
        'c' => 2,
    ];

    /**
     * Phase id of each activity phase
     *
     * @var string
     */
    const ACTIVITY_MAPPING = [
        '1' => 'initalAssessment',
        '2' => 'siteAcceptance',
        '3' => 'siteAssessment',
        '4' => 'ownershipDirection',
        '5' => 'sitePairing',
        '6' => 'corporateNameApproval',
        '7' => 'cibi',
        '8' => 'initialInterview',
        '9' => 'ha',
        '10' => 'gtm',
        '11' => 'finalInterview',
        '12' => 'bdd',
        '13' => 'fg',
        '14' => 'fa',
    ];

    /**
     * Phase id with the activity phase acronym from the request
     *
     * @var string
     */
    const ACTIVITY_PHASE = [
        '1' => 'ia',//inital assessment
        '2' => 'sac',//site acceptance
        '3' => 'sas',//site assessment
        '4' => 'od',//ownership direction
        '5' => 'sp',//site pairing
        '6' => 'cna',//corporate name approval
        '7' => 'cibi',//cibi
        '8' => 'ii',// initial interview
        '9' => 'ha',//harrison assessment
        '10' => 'gtm',//going through the motion
        '11' => 'fi',//final interview
        '12' => 'bdd',//business direction discussion
        '13' => 'fg',//franchise grant
        '14' => 'fa',//franchise agreement signing
    ];

    /**
     * Phase id's of the phase that the following role has access
     *
     * @var string
     */
    const FRANCHISING_ACTIVITY_ACCESS = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14'];
    const FRANCHISEE_ACTIVITY_ACCESS = ['2','4','5','6','7','8','9','10','11','12','13','14'];
    const OPERATIONS_ACTIVITY_ACCESS = ['2','3','4','5','6','7','8','9','10','11','12','13','14'];
    const CRE_ACTIVITY_ACCESS = ['1','2','3','4','5'];

    /**
     * Next phase id's for each phase for franchise application
     *
     * @var string
     */
    const SUCCEEDING_PHASES = [
        '1' => ['6'],
        '2' => ['4'],
        '3' => ['2'],
        '4' => ['5'],
        '5' => ['12'],
        '6' => ['7'],
        '7' => ['9'],
        '8' => ['10'],
        '9' => ['8'],
        '10' => ['11'],
        '11' => ['5'],
        '12' => ['13'],
        '13' => ['14'],
        '14' => ['14'],
    ];

    /**
     * Next phase id's for each phase for site referrals
     *
     * @var string
     */
    const SUCCEEDING_SITE_REFFERAL_PHASES = [
        '2' => '12',
        '3' => '2',
        '12' => '0',
    ];

    /**
     * Site related activities
     *
     * @var string
     */
    const SITE_ACTIVITIES = ['2','3','4'];

    /**
     * Site related activities
     *
     * @var string
     */
    const MULTIPLE_ACTIVITIES_PER_PHASE = ['2','3','4','6'];

    /**
     * The phase id for corporate name approval
     *
     * @var string
     */
    const CORPORATE_NAME_PHASE = 6;

    /**
    * The phase id for initial assessment
    *
    * @var string
    */
   const INITIAL_ASSESSMENT_PHASE = 1;

    /**
    * The phase id for site acceptance
    *
    * @var string
    */
   const SITE_ACCEPTANCE_PHASE = 2;

    /**
    * The phase id for site assessment
    *
    * @var string
    */
    const SITE_ASSESSMENT_PHASE = 3;

    /**
    * The phase id for ownership direction
    *
    * @var string
    */
    const OWNERSHIP_DIRECTION_PHASE = 4;


    /**
    * The phase id for site assessment
    *
    * @var string
    */
    const SITE_PAIRING_PHASE = 5;

    /**
    * The phase id for corporate name approval
    *
    * @var string
    */
    const CORPORATE_NAME_APPROVAL_PHASE = 6;
     /**
    * The phase id for CIBI
    *
    * @var string
    */
    const CIBI_PHASE = 7;
    /**
    * The phase id for initial interview
    *
    * @var string
    */
    const INITIAL_INTERVIEW_PHASE = 8;
    /**
    * The phase id for HA
    *
    * @var string
    */
    const HA_PHASE = 9;

    /**
    * The phase id for going-through-the-motion (GTM)
    *
    * @var string
    */
    const GOING_THROUGH_THE_MOTION_PHASE = 10;

    /**
    * The phase id for franchise agreement signing
    *
    * @var string
    */
    const FRANCHISE_AGREEMENT_SIGNING_PHASE = 14;

    /**
    * The phase id for business direction discussion
    *
    * @var string
    */
    const BUSINESS_DIRECTION_DISCUSSION_PHASE = 12;


    /**
    * The phase id for Franchise Grant Signing
    *
    * @var string
    */
    const FRANCHISE_GRANT_SIGNING_PHASE = 13;

    /**
    * The phase id for final interview
    *
    * @var string
    */
    const FINAL_INTERVIEW_PHASE = 11;
    /**
    * The type id for applications without site
    *
    * @var string
    */
   const APPLICATION_WITHOUT_SITE_TYPE = 1;

    /**
    * The type id for applications with site
    *
    * @var string
    */
   const APPLICATION_WITH_SITE_TYPE = 2;

    /**
    * The type id for site referrals
    *
    * @var string
    */
   const SITE_REFERRAL_TYPE = 3;

    /**
     * The prefix for all application module payload keys
     *
     * @var string
     */
    const APPLICATION_KEY_PREFIX = 'app';

    /**
     * The prefix for all user module payload keys
     *
     * @var string
     */
    const USER_KEY_PREFIX = 'user';

    /**
     * User not yet updated
     *
     * @var string
     */
    const NOT_YET_UPDATED_DATE = "Not yet updated";

    /**
     * User not yet updated
     *
     * @var string
     */
    const NOT_YET_UPDATED_BY = "None";

    /**
     * User Created action
     *
     * @var string
     */
    const EDIT_TRAIL_ADDED = "CREATE";

    /**
     * User Created remarks
     *
     * @var string
     */
    const EDIT_TRAIL_ADDED_REMARKS = "Initial Creation";

    /**
     * User Updated action
     *
     * @var string
     */
    const EDIT_TRAIL_UPDATE = "UPDATE";

    /**
     * User Updated remarks
     *
     * @var string
     */
    const EDIT_TRAIL_UPDATED_REMARKS = "Update user information";

    /**
     * User Archive action
     *
     * @var string
     */
    const EDIT_TRAIL_ARCHIVE = "ARCHIVE";

    /**
     * User Archive remarks
     *
     * @var string
     */
    const EDIT_TRAIL_ARCHIVE_REMARKS = "Archive user";

    /**
     * User Activate action
     *
     * @var string
     */
    const EDIT_TRAIL_ACTIVATE_REMARKS = "Reactivated archived user";

    /**
     * User Activate remarks
     *
     * @var string
     */
    const ADMINISTRATOR_ROLE = "1";
    const FRANCHISING_ROLE = "2";
    const OPERATIONS_ROLE = "3";
    const CRE_ROLE = "4";
    const FRANCHISEE_ROLE = "5";

    /**
     * Image directory list
     *
     * @var Array
     */
    const EXTENSION_DIRECTORY = [
        "image" => ["jpg", "jpeg", "gif", "png", "bmp"],
        "documents" => ["pdf", "xls","xlsx", "doc", "docx", "csv"],
        "all" => ["pdf", "xls", "doc", "docx", "csv", "jpg", "jpeg", "gif", "png", "bmp"]
    ];

    /* Application prefix
     * @var String
     *
     */
    const APPLICATION_PREFIX="A00";

    /**
     * Background Attachment types
     *
     * @var Array
     */
    const BACKGROUND_ATTACHMENT_TYPES = ["GOVID", "FINANCIAL", "SIGNATURE"];

    /**
     * Site Attachment types
     *
     * @var Array
     */
    const SITE_ATTACHMENT_TYPES = ["SITEPIC", "VICINITYMAP", "FLOORPLAN"];

    /**
     * New franchisee id prefix
     *
     * @var string
     */
    const NEW_FRANCHISEE_ID_PREFIX ="A";
    /**
     * New franchisee id prefix
     *
     * @var string
     */
    const EXISTING_FRANCHISEE_ID_PREFIX ="F";

    /**
     * Franchisee id pad length
     *
     * @var string
     */
    const FRANCHISEE_ID_PAD_LENGTH = 7;

    /**
     * Franchisee id pad character
     *
     * @var string
     */
    const FRANCHISEE_ID_PAD_CHAR = "0";

    /**
     * Site number prefix
     *
     * @var string
     */
    const SITE_NUMBER_PREFIX ="CRE";

    /**
     * Site number pad lengtH
     *
     * @var string
     */
    const SITE_NUMBER_PAD_LENGTH = 4;

    /**
     * Activity status for declined
     *
     * @var string
     */
    const DECLINED_STATUS = 2;

    /**
     * Activity status for ACCEPTED
     *
     * @var string
     */
    const AFFIRMATIVE_STATUS = 1;

    /**
     * Activity status for PENDING
     *
     * @var string
     */
    const PENDING_STATUS = 3;

    /**
     * Activity status for SCHEDULED
     *
     * @var string
     */
    const SCHEDULED_STATUS = 4;

    /**
     * position id for jfc franchising managers
     *
     * @var string
     */
    const FRANCHISING_MANAGER_POSITION_ID = 3;

    /**
     * position id for jfc franchising managers
     *
     * @var string
     */
    const FRANCHISING_HEAD_POSITION_ID = ['2','5'];

    /**
     * regurlar user
     *
     * @var string
     */
    const USER_POSITION_ID = 2;

    /**
     * position id for jfc franchising managers
     *
     * @var string
     */
    const SIGNATURE_ATTACHMENT_TYPE = 'SIGNATURE';

    /**
     * Username for eSNDP Admin Account
     *
     * @var string
     */
    const ESNDP_ADMIN_USERNAME = 'admin';

    /**
     * Password for eSNDP Admin Account
     *
     * @var string
     */
    const ESNDP_ADMIN_PASSWORD = '1';

    /**
     * API Key for eSNDP Data API
     *
     * @var string
     */
    const ESNDP_API_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcHBfbmFtZSI6IkpGQy1GcmFuY2hpc2luZyJ9.EmrLuCbySlSETZBHO0EVK9IOEhHHdo9HJwjXM-0UuDE';

    /**
     * API URI for eSNDP Data API
     *
     * @var string
     */
    const ESNDP_API = 'franchising-uat.jfcgrp.com:3000/api/v1/';

    /**
     * Application status if rejected
     *
     * @var string
     */
    const APPLICATION_REJECTED_STATUS = 'Rejected';

    /**
     * Ownership direction results
     *
     * @var string
     */
    const OWNERSHIP_DIRECTION_RESULTS = ['Franchise','Company Owned'];

    /**
     * Request type for activities post/put
     *
     * @var string
     */
    const POST_REQUEST_ACTIVITIES = 'PUT';

    /**
     * Request type for activities retrieve
     *
     * @var string
     */
    const RETRIEVE_REQUEST_ACTIVITIES = 'GET';

    /**
     * Activity phases and their abbreviation
     *
     * @var string
     */
    const INITIAL_ASSESSMENT_ABV = 'ia';
    const SITE_ACCEPTANCE_ABV = 'sac';
    const SITE_ASSESSMENT_ABV = 'sas';
    const OWNER_DIRECTION_ABV = 'od';
    const SITE_PAIRING_ABV = 'sp';
    const CORPORATE_NAME_APPROVAL_ABV = 'cna';
    const CIBI_ABV = 'cibi';
    const INITIAL_INTERVIEW_ABV = 'ii';
    const HARISSON_ASSESSMENT_ABV = 'ha';
    const GOING_THROUGH_THE_MOTION_ABV = 'gtm';
    const FINAL_INTERVIEW_ABV = 'fi';
    const BUSINESS_DIRECTION_DISCUSSION_ABV = 'bdd';
    const FRANCHISE_GRANT_SIGNING_ABV = 'fg';
    const FRANCHISE_AGREEMENT_SIGNING_ABV = 'fa';

    /**
     * Activity phases for upload type
     *
     * @var string
     */
    const CIBI_UPLOAD_TYPE = 'CIBI';
    const HARISSON_ASSESSMENT_UPLOAD_TYPE = 'harrisonAssessment';
    const GOING_THROUGH_THE_MOTION_UPLOAD_TYPE = 'GTM';
    const INITIAL_INTERVIEW_UPLOAD_TYPE = 'interviewSummary';

    /**
     * SBUs where HA is mandatory
     *
     * @var string
     */
    const MANDATORY_HA_SBUS = [1, 2];

    const APPLICATION_REJECTED = 2;
}
