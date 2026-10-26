<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;
use App\Models\AdmissionDB\AdReupload;
use App\Models\AdmissionDB\Year;

use App\Models\SettingDB\AdmissionMode;
use App\Models\SettingDB\Campus;

class AdReuploadController extends Controller
{
    /**
     * Image types that may be uploaded. The key is the MIME type detected from
     * the file CONTENTS and the value is the extension used when saving it.
     */
    protected const ALLOWED_IMAGE_TYPES = [
        'image/jpeg'     => 'jpg',
        'image/pjpeg'    => 'jpg',
        'image/png'      => 'png',
        'image/gif'      => 'gif',
        'image/webp'     => 'webp',
        'image/bmp'      => 'bmp',
        'image/x-ms-bmp' => 'bmp',
    ];

    /** Maximum size of a single uploaded image (5MB) */
    protected const MAX_IMAGE_SIZE = 5242880;

    public function repup()
    {
        $admissionmode = AdmissionMode::first();

        return view('portal.reuploadfile', compact('admissionmode'));
    }

    public function searchApplicant(Request $request)
    {
        $request->validate([
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'campus' => 'required|string',
        ]);

        $year = Year::where('status', 'On')->value('adyear');
        $applicant = Applicant::leftJoin('ad_reupload', 'ad_applicant_admission.id', '=', 'ad_reupload.appid')
            ->where('ad_applicant_admission.lname', $request->lastname)
            ->where('ad_applicant_admission.fname', $request->firstname)
            ->where('ad_applicant_admission.campus', $request->campus)
            ->where('ad_applicant_admission.year', $year)
            ->where('ad_reupload.status', 2)
            ->select('ad_applicant_admission.*', 'ad_reupload.reuploadallow')
            ->first();

        if ($applicant) {
            return response()->json([
                'success' => true,
                'applicant' => [
                    'admission_id' => $applicant->admission_id,
                    'lname' => $applicant->lname,
                    'fname' => $applicant->fname,
                    'primaryid' => $applicant->id,
                    'reuploadallow' => $applicant->reuploadallow,
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No applicant found.'
            ]);
        }
    }

    /**
     * Rejects any uploaded file that is not a real image.
     *
     * The MIME type is detected from the file contents (never from the name the
     * browser sent), so a renamed PDF/script cannot slip through. The extension
     * used when saving is also derived from that detected type.
     *
     * @param  array<string,string>  $fields  input name => human readable label
     * @return \Illuminate\Http\JsonResponse|null  the error response, or null when valid
     */
    protected function validateUploadedImages(Request $request, array $fields)
    {
        foreach ($fields as $field => $label) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $file = $request->file($field);

            if (! $file->isValid()) {
                return response()->json([
                    'error' => $label . ' could not be uploaded. Please select the file again.'
                ], 422);
            }

            $mime = $file->getMimeType();

            if (! isset(self::ALLOWED_IMAGE_TYPES[$mime])) {
                return response()->json([
                    'error' => $label . ' must be an image file (JPG, PNG, GIF, WEBP or BMP). '
                        . 'The file you selected is not a valid image.'
                ], 422);
            }

            if (@getimagesize($file->getRealPath()) === false) {
                return response()->json([
                    'error' => $label . ' is a corrupted or unreadable image. Please select a different file.'
                ], 422);
            }

            if ($file->getSize() > self::MAX_IMAGE_SIZE) {
                return response()->json([
                    'error' => $label . ' is too large (' . round($file->getSize() / 1048576, 2) . 'MB). '
                        . 'The maximum allowed size is 5MB.'
                ], 422);
            }
        }

        return null;
    }

    /**
     * Extension of an already validated image, taken from its real MIME type.
     */
    protected function imageExtension($file)
    {
        return self::ALLOWED_IMAGE_TYPES[$file->getMimeType()] ?? 'jpg';
    }

    /**
     * Keeps only safe characters in a file name.
     */
    protected function safeName($value)
    {
        return trim(preg_replace('/[^A-Za-z0-9 _\-]/', '', (string) $value));
    }

    /**
     * Removes the files that were written while the update failed.
     * A file that the database already pointed to is kept, so the applicant
     * never ends up with a broken document link.
     */
    protected function removeStoredFiles(array $stored, array $previous = [])
    {
        foreach ($stored as $field => $path) {
            if (($previous[$field] ?? null) === $path) {
                continue;
            }

            try {
                Storage::disk('public')->delete($path);
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }

    public function uploadDocuments(Request $request)
    {

        $year = Year::where('status', 'On')->value('adyear');

        $app_id = $request->input('id');

        $applicant = ApplicantDocs::where('app_id', $app_id)->first();
        $reupload = AdReupload::where('appid', $app_id)->first();

        if (!$applicant) {
            return response()->json(['error' => 'Applicant not found'], 404);
        }

        /* The file name is built from the record on the database and never from the
           request, so a client cannot inject a path or save under another name. */
        $applicantRow = Applicant::find($app_id);
        $lastname     = $applicantRow->lname ?? $request->input('lname', '');
        $firstname    = $applicantRow->fname ?? $request->input('fname', '');
        $admissionid  = $applicant->admission_id ?: ($applicantRow->admission_id ?? '');

        /* Only image files are accepted */
        $uploads = [
            'studiddoc_image' => [
                'label'  => 'School ID',
                'folder' => $year . '/studentIDfolder',
                'suffix' => '_studid',
            ],
            'proofdoc_image' => [
                'label'  => 'Proof of Equity Target Document',
                'folder' => $year . '/prooffolder',
                'suffix' => '_proof',
            ],
            'grade12File' => [
                'label'  => 'Grade 12 Requirement',
                'folder' => $year . '/applcntrequirement',
                'suffix' => '',
            ],
            'shsFile' => [
                'label'  => 'SHS Graduate Requirement',
                'folder' => $year . '/applcntrequirement',
                'suffix' => '',
            ],
            'transfereeFile' => [
                'label'  => 'Transferee Requirement',
                'folder' => $year . '/applcntrequirement',
                'suffix' => '',
            ],
            'alsFile' => [
                'label'  => 'ALS Requirement',
                'folder' => $year . '/applcntrequirement',
                'suffix' => '',
            ],
            'lifelongFile' => [
                'label'  => 'Life-long Learner Requirement',
                'folder' => $year . '/applcntrequirement',
                'suffix' => '',
            ],
        ];

        $labels = [];
        foreach ($uploads as $field => $upload) {
            $labels[$field] = $upload['label'];
        }

        if ($error = $this->validateUploadedImages($request, $labels)) {
            return $error;
        }

        $baseFilename = $this->safeName($lastname) . '_' . $this->safeName($firstname) . '_' . $this->safeName($admissionid);

        /* 1. Write the files to the storage first, the database is not touched yet */
        $stored = [];
        $previous = [];

        foreach ($uploads as $field => $upload) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $file = $request->file($field);
            $filename = $baseFilename . $upload['suffix'] . '.' . $this->imageExtension($file);
            $previous[$field] = $applicant->getAttribute($field);

            try {
                $path = $file->storeAs($upload['folder'], $filename, 'public');
            } catch (\Throwable $e) {
                report($e);
                $this->removeStoredFiles($stored, $previous);

                return response()->json([
                    'error' => 'The document could not be saved on the server. Please try again.'
                ], 500);
            }

            if ($path === false) {
                $this->removeStoredFiles($stored, $previous);

                return response()->json([
                    'error' => 'The document could not be saved on the server. Please try again.'
                ], 500);
            }

            $stored[$field] = $path;
        }

        if (empty($stored)) {
            return response()->json([
                'error' => 'No document was uploaded. Please select at least one file.'
            ], 422);
        }

        /* 2. Point the database to the saved files */
        try {
            DB::transaction(function () use ($applicant, $reupload, $stored) {
                foreach ($stored as $field => $path) {
                    $applicant->{$field} = $path;
                }

                if (! $applicant->save()) {
                    throw new \RuntimeException('The applicant documents could not be updated.');
                }

                /* The re-upload request is only closed once the documents are saved */
                if ($reupload) {
                    $reupload->status = 3;
                    $reupload->save();
                }
            });
        } catch (\Throwable $e) {
            report($e);
            $this->removeStoredFiles($stored, $previous);

            return response()->json([
                'error' => 'The documents could not be saved. Please try again.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Files updated successfully',
            // 'data' => [
            //     'studiddoc_image' => $applicant->studiddoc_image,
            //     'proofdoc_image' => $applicant->proofdoc_image,
            // ]
        ]);
    }

    public function repupredirectexpire()
    {
        $year = Year::where('status', 'On')->value('adyear');
        $email = session('email');

        return view('portal.uploadsuccesspage', compact('email', 'year'));
    }
}
