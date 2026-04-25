<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPDFContent implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value instanceof \Illuminate\Http\UploadedFile) {
            $fail('The :attribute must be a valid file.');
            return;
        }

        // Check file extension
        $extension = strtolower($value->getClientOriginalExtension());
        if (!in_array($extension, ['pdf', 'doc', 'docx'])) {
            $fail('The :attribute must be a PDF, DOC, or DOCX file.');
            return;
        }

        // Check MIME type
        $mimeType = $value->getMimeType();
        $allowedMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        if (!in_array($mimeType, $allowedMimes)) {
            $fail('The :attribute has an invalid file type.');
            return;
        }

        // Check magic bytes (file signature) for PDF
        if ($extension === 'pdf') {
            $handle = fopen($value->getRealPath(), 'rb');
            $header = fread($handle, 5);
            fclose($handle);

            // PDF files start with %PDF-
            if (substr($header, 0, 4) !== '%PDF') {
                $fail('The :attribute is not a valid PDF file.');
                return;
            }
        }

        // Check file size (max 5MB)
        if ($value->getSize() > 5 * 1024 * 1024) {
            $fail('The :attribute must not be larger than 5MB.');
            return;
        }
    }
}
