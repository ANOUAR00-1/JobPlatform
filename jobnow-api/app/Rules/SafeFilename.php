<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SafeFilename implements ValidationRule
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

        $filename = $value->getClientOriginalName();

        // Check for dangerous characters
        $dangerousChars = ['..', '/', '\\', '<', '>', ':', '"', '|', '?', '*', "\0"];
        foreach ($dangerousChars as $char) {
            if (str_contains($filename, $char)) {
                $fail('The :attribute filename contains invalid characters.');
                return;
            }
        }

        // Check for executable extensions
        $dangerousExtensions = ['exe', 'bat', 'cmd', 'sh', 'php', 'phtml', 'js', 'jar', 'vbs', 'com', 'pif', 'scr'];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($extension, $dangerousExtensions)) {
            $fail('The :attribute has a forbidden file extension.');
            return;
        }

        // Check filename length
        if (strlen($filename) > 255) {
            $fail('The :attribute filename is too long.');
            return;
        }
    }
}
