<?php

namespace App\Mail;

use App\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CourseApproved extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var Course
     */
    private $course;

    /**
     * Create a new message instance.
     *
     * @param Course $course
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(__("Felicidades"))
            ->markdown('emails.course_approved')
            ->with('course', $this->course);
    }
}
