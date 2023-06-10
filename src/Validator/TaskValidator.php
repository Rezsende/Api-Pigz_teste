<?php
namespace App\Validator;


class TaskValidator
{
    public function validatePost(array $data): array
    {
        $errors = [];

        
        if (empty($data['title'])) {
            $errors[] = 'The title field is required!';
        } elseif (strlen($data['title']) < 3) {
            $errors[] = 'The title field must have at least three characters!';
        }
        return $errors;
    }


    public function validatePut(string $title, $fini): ?string
    {
        if (empty($title)) {
            return 'The title field is required!';
        }

        if (strlen($title) < 3) {
            return 'The title field must have at least three characters!';
        }
        if ($fini !== 0 && $fini !== 1) {
            return 'The TaskFinished field must be either 0 or 1!';
        }
        return null; 
    }

    public function validateSubTaskTitle(string $title): ?string 

    {
        if (empty($title)) {
            return 'The title subTask field is required!';
            
        }
        if (strlen($title) < 3) {
            return 'The title  field must have at least three characters!';
        }
        return null;
    }

   

}



