<?php

namespace App\Helper\Messages;

class CUploadMessages
{
    const SUCCESS = 'Successfully uploaded';
    const NO_IMAGE_UPLOADED = 'No image selected';
    const NO_FILE_UPLOADED = 'No file selected';
    const NO_DOCUMENT_UPLOADED = 'No document selected';
    const FAILED = 'An unexpected error occurred while uploading your image, please try again later';
    const UNKNOWN_IMAGE_ENCODING = 'Your image contains an unknown image file encoding. The file encoding type is not recognized, please use a different image';
    const UNKNOWN_IMAGE_FORMAT = 'Sorry, we could not upload this file. Try saving it in a different format and upload again';
    const UNKNOWN_IMAGE_DIRECTORY = 'Image directory not found';
    const UNKNOWN_IMAGE_BASENAME = 'Image basename not found';
    const UNKNOWN_IMAGE_UPLOADFILE = 'Index uploadedFile not found';
    const NOTFOUND_IMAGE_INDEX = '[] Index not found';
    const EMPTY_IMAGE_INDEX = '[] Index is empty';
    const FILE_OPTION_ERROR = 'File option is not in the list';
    const MAX_SIZE_LIMIT = 'Max size limit exceeded';
}
