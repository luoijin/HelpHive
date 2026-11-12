<?php
declare(strict_types=1);

final class HomeController
{
    /** GET / — Guest & Authenticated users. */
    public function index(): void
    {
        view('home/index', [
            'title' => 'HelpHive — Be a Little Buzz of Hope',
        ]);
    }
}
