<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $projectData = [
            'appName' => 'Si Gemays Portal',
            'course' => 'Praktikum Pengembangan Aplikasi Web (PAW) - Modul 6',
            'university' => 'Telkom University Surabaya',
            'teamMembers' => [
                [
                    'name' => 'Zaki Irsyada Putra',
                    'role' => 'Mahasiswa A (Project Leader & Home Portal)',
                    'branch' => 'zaki',
                    'status' => 'Lead'
                ],
                [
                    'name' => 'M Nabil Alhakim',
                    'role' => 'Mahasiswa B (Data CRUD Module)',
                    'branch' => 'nabil',
                    'status' => 'Member'
                ],
                [
                    'name' => 'Daniel Harley Kamasaan',
                    'role' => 'Mahasiswa C (Auth & User Profile)',
                    'branch' => 'daniel',
                    'status' => 'Member'
                ],
                [
                    'name' => 'Deva Mahendra Putra',
                    'role' => 'Mahasiswa D (Reports & Feedback Module)',
                    'branch' => 'deva',
                    'status' => 'Member'
                ]
            ]
        ];

        return view('welcome', compact('projectData'));
    }
}
