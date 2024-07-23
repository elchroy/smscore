<?php

namespace RMS\Interfaces;

interface Database
{
    public function findSchool(string $schoolCode): ?School;

    public function findSubject(string $subjectCode): ?Subject;
}
