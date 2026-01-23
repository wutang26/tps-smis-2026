<?php

namespace App\Imports;
use App\Models\Student;
use App\Models\Post;
use App\Models\StudentPost;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;
use Exception;

class StudentPostImport implements ToCollection, ToModel
{
private $num = 0;
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
    }

    public function model(array $row)
    {
        $this->num++;
        if ($this->num > 5) {
            if (empty($row[1])) {
                return;  // or you can use continue; depending on where the loop is
            }
            try {  
                            
                $student = Student::where('force_number', $row[0])->first();
                    if(!$student){
                        Log::warning('student not found.');
                        return;
                    }
                    Log::info($student); 
                    $post = Post::where('session_programme_id',1)->first();
                    $studentPost = StudentPost::updateOrCreate(
                        ['student_id' => $student->id, // Search criteria
                        'post_id' => $post->id],
                        [
                            'region'   => $row[2],
                            'district' => $row[3],
                            'unit'     => $row[4],
                            'office'   => $row[5],
                        ] // Values to update or create
                    );


                }
             catch (\Exception $e) {
                Log::error("Error creating student/user: " . $e->getMessage());
            }

        }
    }
}
