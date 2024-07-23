<?php

namespace RMS\Interfaces;

interface Subject
{
    public function getId(): string;

    public function getSchool(): School;

    public function getAssessment(): ?Assessment;
}
