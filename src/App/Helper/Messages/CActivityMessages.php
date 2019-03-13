<?php

namespace App\Helper\Messages;

class CActivityMessages
{
    /**
     * Error messages for activity phases
     *
     * @var string
     */
    const INITIAL_ASSESSMENT = 'Initial Assessment not updated';
    const OWNERSHIP_DIRECTION = 'Ownership direction not updated';
    const SITE_ACCEPTANCE = 'Site acceptance not updated';
    const SITE_ASSESSMENT = 'Site assessment not updated';
    const CORPORATE_NAME = 'Corporate name approval not updated';
    const INTERVIEW_SUMMARY = 'Interview Summary not updated';
    const CIBI_MESSAGE = 'CIBI message not updated';

    /**
     * Success message for adding corporate name
     *
     * @var string
     */
    const ADDCORPORATENAME = 'Successfully added Corporate Name';

    /**
     * Error message for non existent activities
     *
     * @var string
     */
    const NON_EXISTENT = 'Activity doesnt exists';

    /**
     * Result value for non existent activities, used to check if the database returns null
     *
     * @var string
     */
    const NON_EXISTENT_RAW = 'NON_EXISTENT';

    /**
     * Error message requests that failed the phase checking
     *
     * @var string
     */
    const PHASE_ERROR = 'Activity not in the right phase';

    /**
     * Success message requests that passed the phase checking (BACKEND USE)
     *
     * @var string
     */
    const PHASE_SUCCESS = 'Activity in the right phase';

    /**
     * Error message for failed schedule activity processes
     *
     * @var string
     */
    const ACTIVITY_SCHEDULE = 'Activity not scheduled';

    /**
     * Error message for attempting to schedule an activity phase that is not capable of being scheduled
     *
     * @var string
     */
    const NOT_FOR_SCHEDULE = 'Activity is not for scheduling';

    /**
     * Error message for attempting to assess an activity phase that is not capable of being assessed
     *
     * @var string
     */
    const NOT_FOR_ASSESSMENT = 'Activity is not for assessment';

    /**
     * Error message for retrieving activities from an empty application
     *
     * @var string
     */
    const NO_ACTIVITIES = 'There are no activities in this application';

    /**
     * Success message after ownership direction;
     *
     * @var string
     */
    const COMPLETED_SITE_APPLICATION = 'Site application completed';
    /**
     * Error messages for null checking of activity phases
     *
     * @var string
     */
    const ACTIVITY_MESSAGES_MAPPING = [
        'ii' => 'Initial Assessment is empty',
        'sa' => 'Site Acceptance is empty',
        'od' => 'Ownership Direction is empty',
        'sp' => 'Site Pairing is empty',
        'cna' => 'Corporate Name Approval is empty',
        'cibi' => 'CIBI is empty',
        'ii' => 'Initial Interview is empty',
        'ha' => 'Harrison Assessment is empty',
        'gtm' => 'GTM is empty',
        'fi' => 'Final Interview is empty',
        'bdd' => 'Business Direction Discussion is empty',
        'fg' => 'Franchise Grant Signing is empty',
        'fa' => 'Franchise Assessment Signing is empty',
        ];

    /**
     * Error messages for position
     *
     * @var string
     */
    const POSITION_ERROR = 'Position doesnt exists';
        
    /**
     * Error messages for managers
     *
     * @var string
     */
    const MANAGERS_ERROR = 'No managers retrieved';

    /**
     * Error message for lacking interview information
     *
     * @var string
     */
    const INTERVIEW_LACKS_INFO = 'Interview request lacks information';
}
